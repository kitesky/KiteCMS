<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\AuthRole;

class AuthUser extends Model
{
    protected $pk = 'uid';
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getScore($uid)
    {
        return $this->where('uid', $uid)->value('score');
    }

    public function setScore($uid, $number)
    {
        return $this->where('uid', $uid)->setField('score', $number);
    }

    public function getDetail($pk) {
        $user = $this->where('uid|username', $pk)->find();
        $role = [];
        if (!empty($user['role_ids'])) {
            $ids = str2arr($user['role_ids'], ',');
            foreach ($ids as $v) {
                $role[] = AuthRole::get($v);
            }
        }
        $user['role'] = $role;

        return $user;
    }

    public function getUserRole($uid)
    {
        $obj = new AuthUserRole;
        return $obj->getRoleList($uid);
    }
    
    public function getUserSite($uid)
    {
        $user = $this->getDetail($uid);
        $siteList = [];
        if (!empty($user['role'])) {
            foreach ($user['role'] as $v) {
                if (!empty($v['site_ids'])) {
                    $ids = str2arr($v['site_ids']);
                    $siteList = array_merge($siteList, $ids);
                }
            }
        }

        $newSiteList = [];
        $siteList = array_unique($siteList);
        if (!empty($siteList)) {
            foreach ($siteList as $v) {
                $newSiteList[] = Site::get($v);
            }
        }

        return $newSiteList;
    }
}