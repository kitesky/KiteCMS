<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\common\model\Navigation as NavigationModel;
use app\common\model\SiteConfig;
use app\common\model\DocumentCategory;
use app\admin\controller\Admin;
use app\common\validate\NavigationValidate;
use app\common\model\BuildUrl;

class Navigation extends Admin
{
    // 分类配置标识
    protected $category = 'navigation_category';

    public function index()
    {
        $request = Request::param();

        $nav = new NavigationModel;

        $map    = [];
        $params = [];
        $search = [];

        // 查询条件
        if (!empty($this->site_id)) {
            $site_id = ['site_id','=',$this->site_id];
            array_push($map, $site_id);
        }

        // 类别
        if (isset($request['cid'])) {
            $cid           = ['cid','=',$request['cid']];
            $params['cid'] = $request['cid'];
            $search['cid'] = $request['cid'];
            array_push($map, $cid);
        }else {
            $cid = [];
            $search['cid'] = '';
        }

        // 列表
        $list = $nav
            ->where($map)
            ->order('sort asc')
            ->select();

        // 获取栏目列表
        $cateObj = new DocumentCategory;
        $document_category = $cateObj->getCategoryForLevel($this->site_id);
        $doc_cate = [];
        if (!empty($document_category)) {
            foreach ($document_category as $v) {
                $vdata = [
                    'id'    => $v->id,
                    'pid'   => $v->pid,
                    'level' => $v->level,
                    'html'  => $v->html,
                    'name'  => $v->title,
                ];

                $doc_cate[] = $vdata;
            }
        }

        // 获取导航分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        // 菜单详情
        if (!empty($request['cid'])) {
            $cate_cid = $request['cid'];
            $cate_name = SiteConfig::getCategoryConfigName($this->site_id, $this->category, $request['cid']);
        } else if (!empty($category)) {
            $detail = reset($category);
            $cate_cid = $detail['id'];
            $cate_name = SiteConfig::getCategoryConfigName($this->site_id, $this->category, $detail['id']);
        } else {
            $cate_name = '';
            $cate_cid =  '';
        }

        $data = [
            'search'            => $search,
            'category'          => $category,
            'doc_cate'          => $doc_cate,
            'list'              => list_to_tree($list),
            'cate_name'         => $cate_name,
            'cate_cid'          => $cate_cid,
        ];

        return $this->fetch('index', $data);
    }

    public function sortNav()
    {
        $request = Request::instance()->param();

        $obj = new NavigationModel;
        if (!empty($request['ids'])) {
            foreach ($request['ids'] as $k => $v) {
                $menu = $obj::get($v['id']);
                $menu->sort = $k;
                $menu->pid = 0;
                $menu->save();
                if (!empty($v['children'])) {
                    foreach ($v['children'] as $kk => $vv) {
                        $menu = $obj::get($vv['id']);
                        $menu->sort = $kk;
                        $menu->pid = $v['id'];
                        $menu->save();
                        if (!empty($vv['children'])) {
                            foreach ($vv['children'] as $kkk => $vvv) {
                                $menu = $obj::get($vvv['id']);
                                $menu->sort = $kkk;
                                $menu->pid = $vv['id'];
                                $menu->save();
                            }
                        }
                    }
                }
            }
        }

    }
    public function addNav()
    {
        $request = Request::instance()->param();

        $obj = new NavigationModel;

        // 验证数据
        $validate = new NavigationValidate();

        if (!empty($request['ids'])) {
            $validateResult = $validate->scene('category')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $data = [];

            foreach ($request['ids'] as $v) {
                $cateObj = new DocumentCategory;
                $category = $cateObj::get($v);
                $data[] = [
                    'cid'     => $request['cid'],
                    'cat_id'  => $v,
                    'site_id' => $this->site_id,
                    'name'    => $category->title,
                    'type'    => 1,
                    'url'     => '',
                ];
            }

            $result = $obj->saveAll($data);
        } else {
            $validateResult = $validate->scene('custom')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $data = [
                'cid'     => $request['cid'],
                'site_id' => $this->site_id,
                'name'    => $request['name'],
                'type'    => 2,
                'url'     => $request['url'],
            ];

            $result = $obj->save($data);
        }

        if ($result !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

    public function remove()
    {
        $id = Request::param('id');

        $obj = new NavigationModel;
        $detail = $obj::get($id);
        $des = $detail->delete();

        // 查询类别 获取子级
        $list = $obj
            ->where('site_id', $this->site_id)
            ->where('cid', $detail->cid)
            ->select();
        
        $childrenIds = get_childs_id ($list, $id);
        if (!empty($childrenIds)) {
            $des = $obj::destroy($childrenIds);
        }


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