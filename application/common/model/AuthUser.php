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
}