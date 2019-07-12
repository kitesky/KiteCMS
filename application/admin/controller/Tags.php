<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\common\model\SiteConfig;
use app\admin\controller\Admin;
use app\common\model\Tags as TagsModel;
use app\common\validate\TagsValidate;

class Tags extends Admin
{
    // 分类配置标识
    protected $category = 'link_category';

    public function index()
    {
        $request = Request::param();

        $query = [
            'site_id' => $this->site_id,
        ];
        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'tag_id desc',
            'params' => $query,
            'limit'  => 15,
        ];

        // 分页列表
        $obj = new TagsModel;
        $list = $obj->getList($args);

        $data = [
            'list'     => $list,
            'page'     => $list->render(),
        ];

        return $this->fetch('index', $data);
    }

    public function handle()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $data = Request::instance()->param();

            switch ($data['type']) {
                case 'save':
                    if (!empty($data['tag_id'])) {
                        foreach ($data['tag_id'] as $k => $v) {
                            $upData = [
                                'site_id'  => $this->site_id,
                                'tag_id'   => $data['tag_id'][$k],
                                'tag_name' => $data['tag_name'][$k],
                                'sort'     => $data['sort'][$k],
                            ];

                            $result = TagsModel::update($upData);
                        }
                    }

                    if (!empty($data['temp_tag_name'])) {
                        foreach ($data['temp_tag_name'] as $k => $v) {
                            $insertData = [
                                'site_id'  => $this->site_id,
                                'tag_name' => $v,
                                'sort'     => $data['temp_sort'][$k],
                            ];

                            $result = TagsModel::create($insertData);
                        }
                    }

                    if ($result !== false) {
                        return $this->response(200, Lang::get('Success'));
                    }
                    break;
            }

            return $this->response(201, Lang::get('Fail'));
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