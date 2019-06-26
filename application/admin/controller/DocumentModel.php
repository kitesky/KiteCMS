<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\common\model\DocumentModel as DocumentModelModel;
use app\common\model\DocumentField;
use app\admin\controller\Admin;
use app\common\validate\DocumentModelValidate;

class DocumentModel extends Admin
{
    public function index()
    {
        $request = Request::param();

        $modelObj = new DocumentModelModel;

        $params = [];
        $search = [];

        if (isset($request['q'])) {
            $q           = $request['q'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
        }else {
            $q = '';
            $search['q'] = '';
        }

        // 分页列表
        $list = $modelObj
            ->field('id,name,sort,create_at')
            ->where('site_id', 'eq', $this->site_id)
            ->where('name','like','%'.$q.'%')
            ->order('sort asc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        $data = [
            'search' => $search,
            'list'   => $list,
            'page'   => $list->render(),
        
        ];

        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentModelValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $modelObj = new DocumentModelModel;
            $exist = $modelObj
                ->where('site_id', $this->site_id)
                ->where('name', $request['name'])
                ->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $insertData = [
                'site_id' => $this->site_id,
            ];
            $insertData = array_merge($request, $insertData);
            $modelObj->allowField(true)->save($insertData);

            // 模型字段
            if (isset($request['fieldIds'])) {
                foreach ($request['fieldIds'] as $k => $v) {
                    $data = [
                        'model_id' => $modelObj->id,
                        'field_id' => $v,
                        'sort' => $k
                    ];

                    $ins = $modelObj->insertModelField($data);
                }
            }

            if (is_numeric($modelObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $modelObj = new DocumentModelModel;
        $allField = $modelObj->getModeFieldForCategory($this->site_id);

        $data = [
            'allField'   => $allField,
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentModelValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $modelObj = new DocumentModelModel;
            $exist = $modelObj
                ->where('site_id', $this->site_id)
                ->where('name', $request['name'])
                ->value('id');
            if (is_numeric($exist) && $exist != $request['id']) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $insertData = [
                'site_id' => $this->site_id,
            ];
            $insertData = array_merge($request, $insertData);
            $modelObj->isUpdate(true)->allowField(true)->save($insertData);

            // 模型字段
            $modelObj->deleteModelField($request['id']);
            if (isset($request['fieldIds'])) {
                foreach ($request['fieldIds'] as $k => $v) {
                    $data = [
                        'model_id' => $request['id'],
                        'field_id' => $v,
                        'sort' => $k
                    ];

                    $ins = $modelObj->insertModelField($data);
                }
            }

            if (is_numeric($modelObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $modelObj = new DocumentModelModel;
        $info = $modelObj::get($id);

        // 获取所有字段
        $allField = $modelObj->getModeFieldForCategory($this->site_id, $id);

        // 获取当前模型下字段
        $modelField = $modelObj->getModelHasField($id);

        $data = [
            'info'       => $info,
            'allField'   => $allField,
            'modelField' => $modelField,
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');

        $des = DocumentModelModel::destroy($id);

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

}
