<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\admin\controller\Admin;
use app\common\model\Hooks as HooksModel;
use app\common\validate\HooksValidate;

class Hooks extends Admin
{
    public function index()
    {
        $request = Request::param();

        $query = [];
        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'id desc',
            'params' => $query,
            'limit'  => 15,
        ];

        // 分页列表
        $obj = new HooksModel;
        $list = $obj->select($args);

        $data = [
            'search'   => $query,
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
            $validate = new HooksValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $obj = new HooksModel;
            $exist = $obj->where('name', $request['name'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 写入content内容
            $obj->allowField(true)->save($request);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }
        return $this->fetch('create');
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new HooksValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 写入
            $obj = new HooksModel;
            $obj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $obj = new HooksModel;
        $info = $obj->find($id);
        $addons = str2arr($info->addons);
        $this->assign('info', $info);
        $this->assign('addons', $addons);
        return $this->fetch('edit');
    }

    public function remove()
    {
        $id = Request::param('id');
        $des = HooksModel::destroy($id);
        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
}
