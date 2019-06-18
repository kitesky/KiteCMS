<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;

class Member extends Controller
{
    // 校验权限
    public function ActionBegin($params)
    {
        $url = strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());

        // 查询需要登陆访问的规则集合
        $permission  = Db::name('user_nav')->where('url', $url)->find();

        // 如果当前URL在权限控制中 校验权限
        if (!empty($permission)) {
            $uid = Session::get('uid', 'index');
            if (!$uid) {
                $this->redirect('member/member/login');
            }
        }
    }
}