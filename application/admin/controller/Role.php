<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Lang;
use think\facade\Request;
use app\common\model\AuthRole;
use app\admin\controller\Admin;
use app\common\validate\RoleValidate;

class Role extends Admin
{
    public function index()
    {
        $list = Db::name('auth_role')->order('weighing asc')->select();
        return view('index', ['list' => $list]);
    }

    public function auth()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 如果为空 管理站点全部置空
            if (empty($request['ids'])) {
                $del = Db::name('auth_role_permission')->where('role_id', $request['role_id'])->delete();
                if (is_numeric($del)) {
                    return $this->response(200, Lang::get('Success'));
                }else {
                    return $this->response(201, Lang::get('Fail'));
                }
            } else {
                // 先删除全部
                $del = Db::name('auth_role_permission')->where('role_id', $request['role_id'])->delete();

                foreach ($request['ids'] as $v) {
                    $insertData = [
                        'role_id'       => $request['role_id'],
                        'permission_id' => $v,
                    ];

                    $ins = Db::name('auth_role_permission')
                        ->where('role_id', $request['role_id'])
                        ->insert($insertData);
                }

                if ($del !== false && $ins !== false) {
                    return $this->response(200, Lang::get('Success'));
                } else {
                    return $this->response(201, Lang::get('Fail'));
                }
            }
        }

        // 当前角色信息
        $id = Request::param('id');
        $info = Db::name('auth_role')
            ->where('role_id', $id)
            ->find();

        // 所有权限列表
        $query = Db::name('auth_permission')->order('weighing asc')->select();
        $list = list_for_level($query);

        // 查询角色拥有的权限集合
        $tmp_permission = Db::name('auth_role_permission')->field('permission_id')->where('role_id', $id)->select();
        $permission = [];
        if (!empty($tmp_permission)) {
            foreach ($tmp_permission as $v) {
                array_push($permission, $v['permission_id']);
            }
        }

        return view('auth', ['info' => $info, 'list' => $list, 'permission' => $permission]);
    }

    public function remove()
    {
        $id = Request::param('id');
        $return = Db::name('auth_role')->where('role_id', $id)->delete();
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
            $validate = new RoleValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $exist = Db::name('auth_role')->where('role_name', $request['role_name'])->value('role_id');
            if (!empty($exist) && $exist != $request['role_id']) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $return = Db::name('auth_role')->where('role_id', $request['role_id'])->update($request);
            if ($return !== false) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $info = Db::name('auth_role')->where('role_id', $id)->find();
        return view('edit', ['info' => $info]);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new RoleValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $exist = Db::name('auth_role')->where('role_name', $request['role_name'])->value('role_id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $returnId = Db::name('auth_role')->insertGetId($request);
            if (is_numeric($returnId)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }

        }

        $query = Db::name('auth_permission')->order('weighing asc')->select();
        $list = list_for_level($query);
        return view('create', ['list' => $list]);
    }
}