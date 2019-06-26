<?php
namespace app\common\model;

use think\Model;

class DocumentCategory extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getCategoryChildByPid($pid)
    {
        return self::where('pid', $pid)
            ->order('sort asc')
            ->select();
    }

    public function getCategoryForLevel($site_id)
    {
        $list = self::where('c.site_id', $site_id)
            ->field('c.*, m.name as model_name')
            ->alias('c')
            ->order('sort asc')
            ->join('document_model m','c.model_id = m.id')
            ->select();

        return list_for_level($list);
    }

    public function getCategoryById($site_id, $id)
    {
        return self::where('site_id', '=', $site_id)
            ->where('id', $id)
            ->find();
    }

    public function getCategoryByAlias($site_id, $alias)
    {
        return self::where('site_id', '=', $site_id)
            ->where('alias', $alias)
            ->find();
    }
}