<?php
namespace app\common\model;

use think\Model;
use think\Db;

class MemberGroup extends Model
{
    protected $pk = 'group_id';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 获取用户所有权限集合
     *
     * @return array
     */
    static public function getPermission()
    {
        $list = Db::name('member_permission')
            ->order('weighing', 'asc')
            ->select();

        return list_for_level($list);
    }
}