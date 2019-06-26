<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\common\model\DocumentField as DocumentFieldModel;
use app\common\model\SiteConfig;
use app\admin\controller\Admin;
use app\common\validate\DocumentFieldValidate;

class DocumentField extends Admin
{
    // 分类配置标识
    protected $category = 'field_category';

    public function index()
    {
        $request = Request::param();

        $fieldObj = new DocumentFieldModel;

        $map    = [];
        $params = [];
        $search = [];

        // 查询条件
        if (!empty($this->site_id)) {
            $site_id = ['site_id','=',$this->site_id];
            array_push($map, $site_id);
        }

        // 关键词
        if (isset($request['q'])) {
            $q           = ['name','like','%'.$request['q'].'%'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
            array_push($map, $q);
        }else {
            $q = [];
            $search['q'] = '';
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

        // 分页列表
        $list = $fieldObj
            ->where($map)
            ->order('sort asc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        // 获取字段分类列表
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
            $validate = new DocumentFieldValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $fieldObj = new DocumentFieldModel;
            $exist = $fieldObj->where('variable', $request['variable'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $insertData = [
                'site_id' => $this->site_id,
            ];
            $insertData = array_merge($request, $insertData);
            $fieldObj->allowField(true)->save($insertData);

            if (is_numeric($fieldObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 获取字段分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $fieldObj = new DocumentFieldModel;
        $data = [
            'category' => $category,
            'type'     => config('site.filedType'),
            'rule'     => $fieldObj->rule,
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentFieldValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $fieldObj = new DocumentFieldModel;
            $exist = $fieldObj->where('variable', $request['variable'])->value('id');
            if (is_numeric($exist) && $exist != $request['id']) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $insertData = [
                'site_id' => $this->site_id,
            ];
            $insertData = array_merge($request, $insertData);
            $fieldObj->isUpdate(true)->allowField(true)->save($insertData);

            if (is_numeric($fieldObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $fieldObj = new DocumentFieldModel;
        // 获取字段分类列表
        
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);
        $info =  $fieldObj::get($id);
        $data = [
            'category' => $category,
            'type'     => config('site.filedType'),
            'info'     => $info,
            'rule'     => $fieldObj->rule,
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');

        $del = DocumentFieldModel::destroy($id);

        if ($del !== false) {
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
        // 获取字段分类列表
        $category = SiteConfig::getCategoryConfig($this->site_id, $this->category);

        $data = [
            'category' => $category,
        ];

        return $this->fetch('category', $data);
    }
}
