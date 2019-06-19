<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\AuthUserRole;

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

    public function getUserRole($uid)
    {
        $obj = new AuthUserRole;
        return $obj->getRoleList($uid);
    }
}