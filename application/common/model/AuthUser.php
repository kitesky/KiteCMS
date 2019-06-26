<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\AuthRole;

class AuthUser extends Model
{
    protected $pk = 'uid';
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getScore($uid)
    {
        return self::where('uid', $uid)->value('score');
    }

    public function setScore($uid, $number)
    {
        return self::where('uid', $uid)->setField('score', $number);
    }

    public function find($pk) {
        $user = AuthUser::where('uid|username', $pk)->find();
        if (!empty($user['role_ids'])) {
            $ids = explode(',', $user['role_ids']);
            foreach ($ids as $v) {
                $role[] = AuthRole::get($v);
            }
        }
        $user['role'] = $role;

        return $user;
    }
    public function getUserRole($uid)
    {
        $obj = new AuthUserRole;
        return $obj->getRoleList($uid);
    }
}