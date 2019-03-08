<?php
namespace app\admin\controller;

use think\facade\Lang;
use think\facade\Request;
use app\admin\controller\Admin;
use app\common\model\AuthUser;
use app\common\model\AuthRole;
use app\common\model\AuthUserRole;
use app\common\model\AuthUserSite;
use app\common\validate\UserValidate;

use app\common\model\Site;

class User extends Admin
{
    public function index()
    {
        $request = Request::param();

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

        $userObj = new AuthUser;
        $list = $userObj
            ->field('uid,username,phone,email,status')
            ->whereOr('username|phone|email','like','%'.$q.'%')
            ->order('uid desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        $new_list = [];
        $userRoleObj = new AuthUserRole;
        if (isset($list)) {
            foreach ($list as $v) {
                $v['role'] = $userRoleObj->getRoleList($v['uid']);
                array_push($new_list, $v);
            }
        }

        $this->assign('search', $search);
        $this->assign('list', $new_list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new UserValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $exist = AuthUser::where('username', $request['username'])->value('uid');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $userObj = new AuthUser;
            $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
            $userObj->allowField(true)->save($request);

            if (is_array($request['ids'])) {
                $userRoleObj = new AuthUserRole;
                foreach($request['ids'] as $v) {
                    $roleData = [
                        'uid'     => $userObj->uid,
                        'role_id' => $v,
                    ];
                    $userRoleObj->allowField(true)->save($roleData);
                }
            }

            if (is_numeric($userObj->uid)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 角色列表
        $roleObj = new AuthRole;
        $list = $roleObj->order('weighing asc')->select();
        $this->assign('list', $list);
        return $this->fetch('create');
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new UserValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 数据处理
            if (!empty($request['password'])) {
                // 验证密码
                $validateResult = $validate->scene('updatePassword')->check($request);
                if (!$validateResult) {
                    return $this->response(201, $validate->getError());
                }

                $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
            } else {
                unset($request['password']);
                unset($request['repassword']);
            }
            $userObj = new AuthUser;
            $userObj->isUpdate(true)->allowField(true)->save($request);

            if (is_array($request['ids'])) {
                $userRoleObj = new AuthUserRole;
                $userRoleObj->where('uid', 'eq', $request['uid'])->delete();
                foreach($request['ids'] as $v) {
                    $roleData = [
                        'uid'     => $request['uid'],
                        'role_id' => $v,
                    ];
                    $userRoleObj->insert($roleData);
                }
            }

            if (is_numeric($userObj->uid)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 角色列表
        $id = Request::param('id');

        $info = AuthUser::where('uid', $id)->find();

        $roleObj = new AuthRole;
        $list = $roleObj->order('weighing asc')->select();

        $userRoleObj = new AuthUserRole;
        $role = $userRoleObj->getRoleIds($id);

        $this->assign('info', $info);
        $this->assign('list', $list);
        $this->assign('role', $role);
        return $this->fetch('edit');
    }

    public function auth()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            $userSiteObj = new AuthUserSite;

            // 如果为空 管理站点全部置空
            if (empty($request['ids'])) {
                $del = $userSiteObj->where('uid', $request['uid'])->delete();
                if (is_numeric($del)) {
                    return $this->response(200, Lang::get('Success'));
                }else {
                    return $this->response(201, Lang::get('Fail'));
                }
            } else {
                // 先删除全部
                $del = $userSiteObj->where('uid', $request['uid'])->delete();

                foreach ($request['ids'] as $v) {
                    $insertData = [
                        'uid' => $request['uid'],
                        'site_id' => $v,
                    ];
                    $ins = $userSiteObj->insertGetId($insertData);
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
        $info = AuthUser::get($id);

        // 获取所有站点列表
        $siteObj = new Site;
        $list = $siteObj
            ->field('id,name')
            ->order('id desc')
            ->select();

        // 获取当前用户管理的站点
        $_hasList = AuthUserSite::where('uid', $id)->select();
        $hasList = [];
        if (!empty($_hasList)) {
            foreach ($_hasList as $v) {
                array_push($hasList, $v['site_id']);
            }
        }

        $data = [
            'info' => $info,
            'list' => $list,
            'haiList' => $hasList,
        ];

        return $this->fetch('auth', $data);
    }

    public function handle()
    {
        $request = Request::instance()->param();

        $userObj = new AuthUser;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = AuthUser::destroy($v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'enabled':
                foreach ($request['ids'] as $v) {
                    $result = $userObj
                        ->where('uid', $v)
                        ->setField('status', 0);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'disabled':
                foreach ($request['ids'] as $v) {
                    $result = $userObj
                        ->where('uid', $v)
                        ->setField('status', 1);
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

        $userObj = new AuthUser;
        $return = $userObj->where('uid', $id)->delete();
        if ($return !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

}