<?php
namespace app\common\model;

use think\Db;
use think\Model;

class AuthUserSite extends Model
{
    public function getMySiteIds($uid)
    {
        $list = Db::name('auth_user_site')
            ->field('site_id')
            ->where('uid', $uid)
            ->select();

        $ids = [];
        if (is_array($list)) {
            foreach ($list as $v) {
                array_push($ids, $v['site_id']);
            }
        }

        return $ids;
    }
}