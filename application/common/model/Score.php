<?php
namespace app\common\model;

use think\Model;

class Score extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

}