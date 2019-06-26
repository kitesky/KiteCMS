<?php
namespace app\common\model;

use think\Model;

class Log extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getUserLoginLog()
    {
        return Log::field('l.*,u.username')
            ->alias('l')
            ->where('l.uid', '>=', 1)
            ->join('auth_user u', 'l.uid = u.uid')
            ->order('l.id desc')
            ->paginate(20, false, [
                'type'     => '\app\common\model\page\Bootstrap',
                'var_page' => 'page',
            ]);
    }
}