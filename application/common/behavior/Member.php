<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Log;

class Member extends Controller
{
    public function MemberLogin($params)
    {
        // 记录日志
        $log = new Log;
        return $log->allowField(true)->save($params);
    }

    // 校验权限
    public function ActionBegin($params)
    {
        $url = strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());

        // 查询权限
        $group_id = Session::get('group_id', 'index');
        $permissionIds = Db::name('member_group')->where('group_id', $group_id)->value('permission_ids');
        $permissionId  = Db::name('member_permission')->where('url', $url)->value('id');

        // 如果当前URL在权限控制中 校验权限
        if (!empty($permissionId)) {
            if (!empty($permissionIds)) {
                $list = explode(',', $permissionIds);
                if (!in_array($permissionId, $list)) {
                    $this->redirect('member/member/login');
                }
            } else {
                $this->redirect('member/member/login');
            }
        }
    }
}