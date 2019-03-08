<?php
namespace app\common\model;

use think\Model;

class DocumentContentLike extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function unlikeCount($document_id)
    {
        return self::where('document_id', $document_id)->where('unlike', 1)->count();
    }

    public function likeCount($document_id)
    {
        return self::where('document_id', $document_id)->where('like', 1)->count();
    }

    public function isLike($ip, $document_id)
    {
        return self::where('ip', $ip)
            ->where('document_id', $document_id)
            ->whereTime('create_at', 'today')
            ->find();
    }
}