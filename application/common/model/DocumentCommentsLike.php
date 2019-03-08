<?php
namespace app\common\model;

use think\Model;

class DocumentCommentsLike extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function unlikeCount($comments_id)
    {
        return self::where('comments_id', $comments_id)->where('unlike', 1)->count();
    }

    public function likeCount($comments_id)
    {
        return self::where('comments_id', $comments_id)->where('like', 1)->count();
    }

    public function isLike($ip, $comments_id)
    {
        return self::where('ip', $ip)
            ->where('comments_id', $comments_id)
            ->whereTime('create_at', 'today')
            ->find();
    }
}