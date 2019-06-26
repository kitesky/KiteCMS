<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\common\model\Block as BlockModel;
use app\common\model\SiteConfig;
use app\admin\controller\Admin;
use app\common\validate\BlockValidate;

class Block extends Admin
{
    // 分类配置标识
    protected $category = 'block_category';

    public function index()
    {
        $request = Request::param();

        $query = [
            'site_id' => $this->site_id,
            'cid'     => isset($request['cid']) ? $request['cid'] : '',
        ];
        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'id desc',
            'params' => $query,
            'limit'  => 15,
        ];

        // 分页列表
        $obj = new BlockModel;
        $list = $obj->select($args);
        if (!empty($list)) {
            foreach ($list as $v) {
                $v->catename = SiteConfig::getCategoryConfigName($this->site_id, $this->category, $v->cid);
            }
        }

        // 获取分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $data = [
            'search'   => $query,
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
            $validate = new BlockValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $obj = new BlockModel;
            $exist = $obj->where('site_id', $this->site_id)->where('variable', $request['variable'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 写入content内容
            $contentData = [
                'site_id'    => $this->site_id,
                'uid'        => $this->uid,
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

        return $this->fetch('create', ['category' => $category]);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new BlockValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 写入
            $obj = new BlockModel;
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

        $obj = new BlockModel;
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

        $obj = new BlockModel;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = $obj::destroy($v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'active':
                foreach ($request['ids'] as $v) {
                    $result = $obj->where('id', $v)->setField('status', 1);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'freeze':
                foreach ($request['ids'] as $v) {
                    $result = $obj->where('id', $v)->setField('status', 0);
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
        $des = BlockModel::destroy($id);

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

        $obj = new BlockModel;
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