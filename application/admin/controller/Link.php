<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\common\model\Link as LinkModel;
use app\common\model\SiteConfig;
use app\admin\controller\Admin;
use app\common\validate\LinkValidate;

class Link extends Admin
{
    // 分类配置标识
    protected $category = 'link_category';

    public function index()
    {
        $request = Request::param();

        $linktObj = new LinkModel;

        // 查询条件
        $map    = [];
        $params = [];
        $search = [];

        // 类别
        if (isset($request['cid']) && is_numeric($request['cid'])) {
            $cid           = ['cid','=',$request['cid']];
            $params['cid'] = $request['cid'];
            $search['cid'] = $request['cid'];
            array_push($map, $cid);
        }else {
            $cid = [];
            $search['cid'] = '';
        }

        // 分页列表
        $list = $linktObj
            ->where($map)
            ->where('site_id', 'eq', $this->site_id)
            ->order('id desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        if (!empty($list)) {
            foreach ($list as $v) {
                $v->catename = SiteConfig::getCategoryConfigName($this->site_id, $this->category, $v->cid);
            }
        }

        // 获取分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $data = [
            'search'   => $search,
            'category' => $category,
            'list'     => $list,
            'page'     => $list->render(),
        
        ];

        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new LinkValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $obj = new LinkModel;
            $exist = $obj->where('name', $request['name'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 写入content内容
            $contentData = [
                'site_id' => $this->site_id,
                'uid'     => $this->uid,
            ];
            $contentData = array_merge($request, $contentData);
            $obj->allowField(true)->save($contentData);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 获取分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $data = [
            'category' => $category,
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new LinkValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 写入
            $obj = new LinkModel;
            $obj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $request = Request::param();

        // 获取分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $obj = new LinkModel;
        $info = $obj->where('id', $request['id'])->find();

        $data = [
            'category' => $category,
            'info'  => $info,
        ];

        return $this->fetch('edit', $data);
    }

    public function handle()
    {
        $request = Request::instance()->param();

        $linkObj = new LinkModel;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = $linkObj::destroy($v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'active':
                foreach ($request['ids'] as $v) {
                    $result = $linkObj
                        ->where('id', $v)
                        ->setField('status', 1);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'freeze':
                foreach ($request['ids'] as $v) {
                    $result = $linkObj
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

    public function remove()
    {
        $id = Request::param('id');

        // 删除
        $des = LinkModel::destroy($id);

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
    public function removeCategory()
    {
        $id = Request::param('id');
        $del = SiteConfig::deleteCategoryConfig($this->site_id, $this->category, $id);

        if ($del !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

    public function handleCategory()
    {
        $data = Request::instance()->param();

        switch ($data['type']) {
            case 'save':
                if (!empty($data['id'])) {
                    foreach ($data['id'] as $k => $v) {
                        $updateData = [
                            'id'       => $data['id'][$k],
                            'name'     => $data['name'][$k],
                            'sort' => $data['sort'][$k],
                        ];
                        $result = SiteConfig::updateCategoryConfig($this->site_id, $this->category, $updateData);
                    }
                }

                if (!empty($data['temp_name'])) {
                    foreach ($data['temp_name'] as $k => $v) {
                        $insertData = [
                            'name'     => $v,
                            'sort' => $data['temp_sort'][$k],
                        ];
                        $result = SiteConfig::insertCategoryConfig($this->site_id, $this->category, $insertData);
                    }
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
        }

        return $this->response(201, Lang::get('Fail'));
    }

    public function category()
    {
        // 获取分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);
        $data = [
            'category' => $category,
        ];

        return $this->fetch('category', $data);
    }
}