<?php
namespace app\common\model;

use think\Model;
use app\common\model\AuthUser;
use app\common\model\AuthRole;

class AuthRule extends Model
{
    protected $pk = 'id';

    public function getUserRule($uid, $rule_type = 'member') {
        $userObj = new AuthUser;
        $roleObj = new AuthRole;
        $user = $userObj->where('uid', $uid)->field('uid,role_ids')->find();

        $ruleIds = [];
        if (!empty($user->role_ids)) {
            $arr = str2arr($user->role_ids, ',');
            foreach ($arr as $v) {
                $ruleIds[] = $roleObj->where('role_id', $v)->value('rule_ids');
            }
        }
        $ruleIdsStr = join(',', $ruleIds);
        $ruleIdsArr = array_unique(str2arr($ruleIdsStr, ','));

        $map = [
            ['module', '=', $rule_type],
            ['id', 'in', $ruleIdsArr],
        ];

        return $this->where($map)->order('sort asc')->select()->toArray();
    }
}