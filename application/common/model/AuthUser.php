<?php
namespace app\common\model;

use think\Model;
use think\Db;

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
}