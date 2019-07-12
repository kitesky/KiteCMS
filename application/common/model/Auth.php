<?php
namespace app\common\model;

use think\Model;
use think\facade\Session;
use app\common\model\AuthRole;
use app\common\model\AuthUser;
use app\common\model\AuthRule;
use app\common\model\Site;

class Auth extends Model
{

    /**
     * 检测登陆
     * @param $resource 资源名称/URL
     * @return boolean
     */
    static public function check($resource)
    {
        // 获取rule_id
        $ruleObj = new AuthRule;
        $rule_id = $ruleObj->where('url', $resource)->value('id');

        // 当rule_id在权限控制表中 进行权限校验
        $bool = false;
        if (is_numeric($rule_id)) {
            // 校验资源权限
            $session = Session::get('user_auth', 'admin');
            $rule = $ruleObj->getUserRule($session['uid'], 'admin');

            if (!empty($rule)) {
                if (in_array($rule_id, array_column($rule, 'id'))) {
                    $bool = true;
                } else {
                    $bool = false;
                }
            }
        } else {
            $bool = true;
        }

        return $bool;
    }

    /**
     * 检测登陆
     *
     * @return boolean
     */
    static public function checkLogin()
    {
        $session = Session::get('user_auth', 'admin');
        if (is_array($session)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 面包屑
     *
     * @return array
     */
    static public function breadcrumb()
    {
        $ruleObj = new AuthRule;
        $rule = $ruleObj
            ->field('id,pid,name,description,url,lang_var')
            ->where('url', get_path_url())
            ->find();

        $rule_list = $ruleObj
            ->field('id,pid,name,description,url,lang_var')
            ->select();

        // 获取当前URL所有父ID
        $praents = get_parents($rule_list, $rule['id']);

        $breadcrumb = [];
        if (!empty($praents)) {
            foreach ($praents as $v) {
                if ($v['id'] == $rule['id']) {
                    $v['active'] = 'active';
                } else {
                    $v['active'] = '';
                    $v['url'] = url($v['url']);
                }
                array_push($breadcrumb, $v);
            }
        }

        return $breadcrumb;
    }
    
    /**
     * 菜单
     *
     * @return array
     */
    static public function menu()
    {
        $ruleObj = new AuthRule;
        $rule_id = $ruleObj
            ->where('url', get_path_url())
            ->value('id');

        // 获取session信息
        $session = Session::get('user_auth', 'admin');

        // 获取所有权限集合
        $ruleObj = new AuthRule;
        $rule = $ruleObj->getUserRule($session['uid'], 'admin');

        if (empty($rule)) {
            return false;
        }

         // 获取当前URL所有父ID
        $ids = get_parents_id($rule, $rule_id);

        // 遍历数组 增加active标识
        $menu = array();
        foreach($rule as $v) {
            // 给父级ID添加ACTIVE表示
            if(in_array($v['id'], $ids)) {
                $v['active'] = 'active'; // 该栏目是否高亮
            } else {
                $v['active']  = '';
            }

            // 生成URL
            if ($v['url'] != '#' || $v['url'] != ''){
                $v['url'] = url($v['url']);
            } else {
                $v['url'] = '#';
            }

            // 过滤不是菜单的URL
            if ($v['menu'] == 1) {
                array_push($menu, $v);
            }
        }

        return list_to_tree($menu);
    }

    /**
     * 注销session
     *
     * @return boolean
     */
    static public function logout()
    {
        Session::clear('admin');
    }

    /**
     * 生成SESSION信息
     *
     * @params $user array 用户信息
     * @return boolean
     */
    static public function createSession($user)
    {
        // 设置默认站点SESSION
        $site = self::getSite($user['uid']);
        if (isset($site)) {
            $default = reset($site);
            Session::set('site_id', $default['id'], 'admin');
            Session::set('site_name', $default['name'], 'admin');
        }

        // 生成用户数据SESSION
        $data = [
            'uid'        => $user['uid'],
            'username'   => $user['username'],
            'login_time' => time(),
        ];

        Session::set('user_auth', $data, 'admin');
        Session::set('user_auth_sign', sign($data), 'admin');

        return true;
    }

    /**
     * 获取用户所有网站
     *
     * @params $uid int 用户UID
     * @return array
     */
    static public function getSite($uid)
    {
        $userObj = new AuthUser;
        return $userObj->getUserSite($uid);
    }

    public function getSiteIds($uid)
    {
        $site_list = self::getSite($uid);
        if (!empty($site_list)) {
            return array_column($site_list, 'id');
        }

        return false;
    }

}