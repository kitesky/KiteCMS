<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use app\common\model\SiteConfig;
use app\common\model\Member;
use app\common\model\MemberScore;

class Score extends Controller
{
    // 注册增加积分
    public function memberRegister($params)
    {
        return $this->setScore($params, 'register_score');
    }

    // 登录增加积分
    public function memberLogin($params)
    {
        return $this->setScore($params, 'login_score');
    }

    // 发布文档增加积分
    public function memberCreateDocument($params)
    {
        return $this->setScore($params, 'publish_score');
    }

    // 评论增加积分
    public function memberComments($params)
    {
        return $this->setScore($params, 'comment_score');
    }

    protected function setScore($params, $type)
    {
        // 积分配置
        $score = SiteConfig::getCofig($params['site_id'], $type);
        // 设置会员表积分总数
        $memberObj = new member;
        $hasScore = $memberObj->getScore($params['mid']);
        $sum = $score + $hasScore;
        $memberObj->setScore($params['mid'], $sum);

        // 增加积分记录
        $scoreObj = new MemberScore;
        $logData = [
            'site_id' => $params['site_id'],
            'mid'     => $params['mid'],
            'sum'     => $sum,
            'score'   => $score,
            'source'  => $type,
        ];

        return $scoreObj->allowField(true)->save($logData);
    }
}