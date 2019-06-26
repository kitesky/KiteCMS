<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Lang;
use think\facade\Request;
use app\admin\controller\Admin;
use app\common\validate\RuleValidate;

class Rule extends Admin
{
    public function index()
    {
        $query = Db::name('auth_rule')->order('sort asc')->select();
        $list = list_for_level($query);

        return view('index', ['list' => $list]);
    }

    public function remove()
    {
        $id = Request::param('id');
        $return = Db::name('auth_rule')->where('id', $id)->delete();
        if ($return !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new RuleValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $return = Db::name('auth_rule')->where('id', $request['id'])->update($request);
            if ($return !== false) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $query = Db::name('auth_rule')
            ->where('id', '<>', $id)
            ->order('sort asc')
            ->select();
        $list = list_for_level($query);
        $info = Db::name('auth_rule')->where('id', $id)->find();
        return view('edit', ['info' => $info, 'list' => $list]);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new RuleValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $exist = Db::name('auth_rule')->where('name', $request['name'])->value('id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $returnId = Db::name('auth_rule')->insertGetId($request);
            if (is_numeric($returnId)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }

        }

        $query = Db::name('auth_rule')->order('sort asc')->select();
        $list = list_for_level($query);
        return view('create', ['list' => $list]);
    }
}