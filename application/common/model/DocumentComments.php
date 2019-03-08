<?php
namespace app\common\model;

use think\Model;

class DocumentComments extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getCommentsCount($document_id)
    {
        return self::where('document_id', $document_id)
            ->where('status', 1)
            ->count();
    }

    public function getCommentsPaginate($site_id, $document_id)
    {
        // 查询数据列表
        $list = self::where('c.site_id',$site_id)
            ->field('c.*,m.avatar,m.username')
            ->alias('c')
            ->where('c.status',1)
            ->where('document_id', $document_id)
            ->join('member m', 'c.mid = m.mid')
            ->order('c.id desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);

        return $list;
    }
}