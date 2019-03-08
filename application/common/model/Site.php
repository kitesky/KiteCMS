<?php
namespace app\common\model;

use think\Model;

class Site extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getSiteByDomain($domain)
    {
        return self::where('domain', $domain)->find();
    }

    public function getDefaultSite()
    {
        return self::where('id', '>', 0)->order('id', 'asc')->find();
    }
}