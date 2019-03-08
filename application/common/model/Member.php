<?php
namespace app\common\model;

use think\Model;

class Member extends Model
{
    protected $pk = 'mid';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getScore($mid)
    {
        return self::where('mid', $mid)
            ->value('score');
    }

    public function setScore($mid, $number)
    {
        return self::where('mid', $mid)
            ->setField('score', $number);
    }

    public function getMemberInfoByMid($mid)
    {
        return self::where('m.mid', $mid)
            ->field('m.*,g.group_name')
            ->alias('m')
            ->join('member_group g', 'g.group_id = m.group_id', 'left')
            ->find();
    }
}