<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use app\common\model\SiteConfig;
use app\common\model\AuthUser;
use app\common\model\Score as ScoreModel;

class Score extends Controller
{
    // 注册增加积分
    public function userRegister($params)
    {
        return $this->setScore($params, 'register_score');
    }

    // 登录增加积分
    public function userLogin($params)
    {
        return $this->setScore($params, 'login_score');
    }

    // 发布文档增加积分
    public function CreateDocument($params)
    {
        return $this->setScore($params, 'publish_score');
    }

    // 评论增加积分
    public function userComments($params)
    {
        return $this->setScore($params, 'comment_score');
    }

    protected function setScore($params, $type)
    {
        // 积分配置
        $score = SiteConfig::getCofig($params['site_id'], $type);
        if (empty($score)) {
            return false;
        }

        // 设置会员表积分总数
        $memberObj = new AuthUser;
        $hasScore = $memberObj->getScore($params['uid']);
        $sum = $score + $hasScore;
        $memberObj->setScore($params['uid'], $sum);

        // 增加积分记录
        $scoreObj = new ScoreModel;
        $logData = [
            'site_id' => $params['site_id'],
            'uid'     => $params['uid'],
            'sum'     => $sum,
            'score'   => $score,
            'source'  => $type,
        ];

        return $scoreObj->allowField(true)->save($logData);
    }
}