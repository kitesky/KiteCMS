<?php
namespace app\common\model;

use think\Model;

class MemberScore extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

}