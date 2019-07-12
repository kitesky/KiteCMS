<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use think\facade\Config;
use app\common\model\DocumentContent;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContentExtra;
use app\admin\controller\Admin;
use app\common\validate\DocumentContentValidate;
use app\common\model\AuthRole;
use app\common\model\Tags;

class Document extends Admin
{
    public function index()
    {
        $request = Request::param();

        $query = [
            'site_id' => $this->site_id,
            'cid'     => isset($request['cid']) ? $request['cid'] : '',
            'q'       => isset($request['q']) ? $request['q'] : '',
            'option'  => isset($request['option']) ? $request['option'] : '',
        ];

        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'id desc',
            'params' => $query,
            'limit'  => 20,
        ];

        // 分页列表
        $documentObj = new DocumentContent;
        $list = $documentObj->select($args);


        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        $data = [
            'search'   => $query,
            'category' => $category,
            'list'     => $list,
            'page'     => $list->render(),
            'option'   => Config::get('site.document_option'),
        ];

        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentContentValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 验证自定义字段
            $extraObj = new DocumentContentExtra();
            if (isset($request['extra'])) {
                // 查询栏目信息 获取model_id
                $cate = DocumentCategory::get($request['cid']);
                // 验证自定义字段
                $validateExtraResult = $extraObj->vlidate($request['extra'], $cate['model_id']);
                if (!$validateExtraResult) {
                    return $this->response(201, $extraObj->getError());
                }
            }

            $contentObj = new DocumentContent;
            $exist = $contentObj->where('title', $request['title'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 提取文档内所有图片
            preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$request['content'],$matches);

            if (empty($request['image'])) {
                $request['image'] = isset($matches[2][0]) ? $matches[2][0] : null;
            }

            // 写入content内容
            $contentData = [
                'site_id' => $this->site_id,
                'uid'     => $this->uid,
                'album'   => isset($matches[2]) ? implode(',', $matches[2]) : null,
            ];

            $contentData = array_merge($request, $contentData);
            $contentObj->allowField(true)->save($contentData);

            // 写入extra内容
            if (isset($request['extra'])) {
                $extraObj->saveContentExtra($contentObj->id, $request['extra']);
            }

            // tag关系映射
            if (!empty($request['tag_id'])) {
                $tagObj = new Tags;
                $tagObj->createTagsMapp($contentObj->id, $request['tag_id']);
            }

            // 文档创建监听钩子 Hook
            $docData = [
                'document_id' => $contentObj->id,
                'uid'         => $this->uid,
                'site_id'     => $this->site_id,
                'content'     => $contentData,
                'extra'       => isset($request['extra']) ? $request['extra'] : [],
            ];
            Hook::listen('create_document', $docData);

            if (is_numeric($contentObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        // 获取角色组
        $roleObj = new AuthRole;
        $role = $roleObj->select();

        // 获取tags
        $tagObj = new Tags;
        $tags = $tagObj->select();

        $data = [
            'category' => $category,
            'role'     => $role,
            'tags'     => $tags,
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentContentValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 验证自定义字段
            $extraObj = new DocumentContentExtra();
            if (isset($request['extra'])) {
                // 查询栏目信息 获取model_id
                $cate = DocumentCategory::get($request['cid']);
                // 验证自定义字段
                $validateExtraResult = $extraObj->vlidate($request['extra'], $cate['model_id']);
                if (!$validateExtraResult) {
                    return $this->response(201, $extraObj->getError());
                }
            }

            // 写入content内容
            $contentObj = new DocumentContent;

            // 判断封面图是否设置
            $exist = $contentObj->where('id', $request['id'])->value('image');
            // 提取文档内所有图片
            preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',$request['content'],$matches);

            if (empty($request['image']) && empty($exist)) {
                $request['image'] = isset($matches[2][0]) ? $matches[2][0] : null;
            }

            // 文档类型标识
            $contentData = [
                'image_flag'     => isset($request['image_flag']) ? 1 : 0,
                'video_flag'     => isset($request['video_flag']) ? 1 : 0,
                'attach_flag'    => isset($request['attach_flag']) ? 1 : 0,
                'hot_flag'       => isset($request['hot_flag']) ? 1 : 0,
                'recommend_flag' => isset($request['recommend_flag']) ? 1 : 0,
                'focus_flag'     => isset($request['focus_flag']) ? 1 : 0,
                'top_flag'       => isset($request['top_flag']) ? 1 : 0,
                'album'          => isset($matches[2]) ? implode(',', $matches[2]) : null,
            ];

            $contentData = array_merge($request, $contentData);
            $contentObj->isUpdate(true)->allowField(true)->save($contentData);

            // 写入extra内容
            if (isset($request['extra'])) {
                $extraObj->saveContentExtra($contentObj->id, $request['extra']);
            } else {
                $extraObj->remove($this->site_id, $contentObj->id);
            }

            // tag关系映射
            if (!empty($request['tag_id'])) {
                $tagObj = new Tags;
                $tagObj->createTagsMapp($contentObj->id, $request['tag_id']);
            }

            // 文档更新监听钩子 Hook
            $docData = [
                'document_id' => $contentObj->id,
                'uid'         => $this->uid,
                'site_id'     => $this->site_id,
                'content'     => $contentData,
                'extra'       => isset($request['extra']) ? $request['extra'] : [],
            ];
            Hook::listen('edit_document', $docData);

            if (is_numeric($contentObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $request = Request::param();

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        $contentObj = new DocumentContent;
        $info = $contentObj->where('id', $request['id'])->find();

        // 获取角色组
        $roleObj = new AuthRole;
        $role = $roleObj->select();

        // 获取tags
        $tagObj = new Tags;
        $tags = $tagObj->select();
        $tagsMapp = $tagObj->getDocumentTags($info->id, true);

        $data = [
            'category' => $category,
            'info'     => $info,
            'role'     => $role,
            'tags'     => $tags,
            'tagsMapp' => $tagsMapp,
        ];

        return $this->fetch('edit', $data);
    }

    public function handle()
    {
        $request = Request::instance()->param();

        $contentObj = new DocumentContent;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = $contentObj->remove($this->site_id, $v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'approval':
                foreach ($request['ids'] as $v) {
                    $result = $contentObj
                        ->where('id', $v)
                        ->setField('status', 1);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'progress':
                foreach ($request['ids'] as $v) {
                    $result = $contentObj
                        ->where('id', $v)
                        ->setField('status', 0);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
        }

        return $this->response(201, Lang::get('Fail'));
    }

    public function createInput()
    {
        $request = Request::param();

        // 查询model_id
        $cateObj = new DocumentCategory;
        $model_id = $cateObj->where('id', $request['cid'])->value('model_id');

        $inputObj = new DocumentContentExtra();

        // extra 不为空则设置默认值
        if (!empty($request['extra'])) {
            $inputObj->setInputDefaultData($request['extra']);
        }

        return $inputObj->buildInput($this->site_id, $model_id);
    }

    public function remove()
    {
        $id = Request::param('id');

        // 删除文档内容
        $docObj = new DocumentContent;
        $des = $docObj->remove($this->site_id, $id);

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
}