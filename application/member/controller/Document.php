<?php
namespace app\member\controller;

use think\facade\Request;
use think\facade\Config;
use think\facade\Lang;
use think\facade\Hook;
use app\common\model\BuildUrl;
use app\member\controller\Base;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentContentExtra;
use app\common\validate\DocumentContentValidate;

class Document extends Base
{
    public function index()
    {
        $request = Request::param();

        $documentObj = new DocumentContent;

        $map    = [];
        $params = [];
        $search = [];

        // 状态
        if (isset($request['status'])) {
            $status           = ['status','=',$request['status']];
            $params['status'] = $request['status'];
            $search['status'] = $request['status'];
            array_push($map, $status);
        }else {
            $q = [];
            $search['status'] = '';
        }

        // 关键词
        if (isset($request['q'])) {
            $q           = ['title','like','%'.$request['q'].'%'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
            array_push($map, $q);
        }else {
            $q = [];
            $search['q'] = '';
        }

        // 类别
        if (isset($request['cid']) && is_numeric($request['cid'])) {
            $cid           = ['cid','=',$request['cid']];
            $params['cid'] = $request['cid'];
            $search['cid'] = $request['cid'];
            array_push($map, $cid);
        }else {
            $cid = '';
            $search['cid'] = '';
        }

        // 选项
        if (isset($request['option']) && !is_numeric($request['option'])) {
            $option           = [$request['option'],'=',1];
            $params['option'] = $request['option'];
            $search['option'] = $request['option'];
            array_push($map, $option);
        }else {
            $option = '';
            $search['option'] = '';
        }

        // 分页列表
        $list = $documentObj
            ->where($map)
            ->where('site_id', 'eq', $this->site_id)
            ->where('uid', 'eq', $this->uid)
            ->order('id desc')
            ->paginate(10, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        // 转换信息
        if (!empty($list)) {
            foreach ($list as $v) {
                $v->url = url('index/document/index', ['id' => $v->id]);;
                $cate = $cateObj->getCategoryById($this->site_id, $v->cid);
                $v->cate_name = $cate->title;
                $v->cate_url  = url('index/category/index', ['cat_id' =>$cate->id]);
            }
        }

        $data = [
            'search'   => $search,
            'category' => $category,
            'list'     => $list,
            'page'     => $list->render(),
            'option'   => Config::get('site.document_option'),
        
        ];

        return $this->fetch('document/index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isPost()) {
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

            // 文档创建监听钩子 Hook
            $docData = [
                'id'      => $contentObj->id,
                'uid'     => $this->uid,
                'site_id' => $this->site_id,
                'content' => $contentData,
                'extra'   => isset($request['extra']) ? $request['extra'] : [],
            ];
            Hook::listen('create_document', $docData);

            if (is_numeric($contentObj->id)) {
                return $this->response(200, Lang::get('success'));
            } else {
                return $this->response(201, Lang::get('fail'));
            }
        }

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        $data = [
            'category' => $category,
        ];

        return $this->fetch('document/create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isPost()) {
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
                $extraObj->deleteContentExtra($contentObj->id);
            }

            // 文档更新监听钩子 Hook
            $docData = [
                'id'      => $contentObj->id,
                'uid'     => $this->uid,
                'site_id' => $this->site_id,
                'content' => $contentData,
                'extra'   => isset($request['extra']) ? $request['extra'] : [],
            ];
            Hook::listen('edit_document', $docData);

            if (is_numeric($contentObj->id)) {
                return $this->response(200, Lang::get('success'));
            } else {
                return $this->response(201, Lang::get('fail'));
            }
        }

        $request = Request::param();

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        $contentObj = new DocumentContent;
        $info = $contentObj->where('id', $request['id'])->find();

        $data = [
            'category' => $category,
            'info'  => $info,
        
        ];

        return $this->fetch('document/edit', $data);
    }
    
    public function createInput()
    {
        $request = Request::param();

        // 查询model_id
        $cateObj = new DocumentCategory;
        $model_id = $cateObj->where('id', $request['cid'])->value('model_id');

        $inputObj = new DocumentContentExtra();

        // document_id 不为空则设置默认值
        if (!empty($request['document_id'])) {
            $inputObj->setInputDefaultData($request['document_id']);
        }

        return $inputObj->buildInput($this->site_id, $model_id);
    }
}
