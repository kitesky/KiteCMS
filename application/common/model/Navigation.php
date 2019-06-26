<?php
namespace app\common\model;

use think\Model;

class Navigation extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getIdByUrl($site_id, $url)
    {
        return self::where('site_id', $site_id)
            ->where('url', $url)
            ->value('id');
    }

    public function getNavigation($site_id, $cid, $order = 'sort asc')
    {
        return self::where('site_id', $site_id)
            ->where('cid', $cid)
            ->order($order)
            ->select();
    }
}