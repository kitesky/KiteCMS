<?php
namespace app\common\model;

use think\Model;

class District extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getDistrictNameById($id)
    {
        return self::where('id', $id)
            ->value('name');
    }

    public function getDistrictForLevel($site_id)
    {
        $list = self::where('site_id', $site_id)
            ->order('initial asc')
            ->select();

        return list_for_level($list);
    }

    public function getParentList($site_id)
    {
        return self::where('site_id', $site_id)
            ->where('pid', 0)
            ->order('initial asc')
            ->select();
    }

    public function getChildList($site_id, $pid)
    {
        return self::where('site_id', $site_id)
            ->where('pid', $pid)
            ->order('initial asc')
            ->select();
    }
}