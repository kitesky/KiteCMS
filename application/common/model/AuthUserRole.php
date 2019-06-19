<?php
namespace app\common\model;

use think\Db;
use think\Model;

class AuthUserRole extends Model
{
    public function getRoleList($uid)
    {
        return Db::name('auth_user_role')
            ->field('r.role_id,r.role_name,r.lang_var')
            ->alias('ur')
            ->join('auth_role r','r.role_id = ur.role_id')
            ->where('ur.uid', $uid)
            ->order('weighing asc')
            ->select();
    }

    public function getRoleIds($uid)
    {
        $list = Db::name('auth_user_role')->where('uid', $uid)->select();
        $new_list = [];
        if (is_array($list)) {
            foreach ($list as $v) {
                array_push($new_list, $v['role_id']);
            }
        }

        return $new_list;
    }
}