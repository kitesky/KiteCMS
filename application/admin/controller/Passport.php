<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\Lang;
use think\facade\Hook;
use think\Db;
use app\common\model\Auth;
use app\common\controller\Common;
use app\common\validate\UserValidate;

class Passport extends Common
{
    public function logout()
    {
        Auth::logout();
        $this->redirect('admin/passport/login');
    }

    public function login()
    {
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new UserValidate();
            $validateResult = $validate->scene('login')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $userInfo = Db::name('auth_user')
                ->field('uid,username,password,status')
                ->where('username', $request['username'])
                ->find();

            // 用户不存在
            if (!$userInfo) {
                return $this->response(201, Lang::get('user does not exist'));
            }

            // 用户被冻结
            if ($userInfo['status'] == 1) {
                return $this->response(201, Lang::get('the user was frozen'));
            }

            // 验证密码 
            if (!password_verify($request['password'], $userInfo['password'])) {
                return $this->response(201, Lang::get('password error'));
            }

            if (Auth::createSession($userInfo)) {
                // 记录登陆日志
                $logData = [
                    'uid' => $userInfo['uid'],
                    'ip'  => get_client_ip(),
                ];
                Hook::listen('user_login_log', $logData);

                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        return $this->fetch('login', ['lang' => Cookie::get('think_var')]);
    }
}
