<?php
namespace app\common\model;

use think\Model;
use think\Db;
use think\facade\Session;
use think\facade\Request;
use think\facade\Url;

class Auth extends Model
{
    public static function getUrl()
    {
        return strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());
    }

    /**
     * 检测登陆
     * @param $resource 资源名称/URL
     * @return boolean
     */
    static public function check($resource)
    {
        // 获取permission_id
        $permission_id = Db::name('auth_permission')
            ->where('url', 'like', $resource)
            ->value('id');

        // 当permission_id在权限控制表中 进行权限校验
        $bool = false;
        if (is_numeric($permission_id)) {
            // 校验资源权限
            $session = Session::get('user_auth', 'admin');
            $permission = self::getPermission($session['uid']);
            $ids = [];
            if (!empty($permission)) {
                foreach ($permission as $v) {
                    array_push($ids, $v['id']);
                }
                if (in_array($permission_id, $ids)) {
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
        $permission = Db::name('auth_permission')
            ->field('id,pid,name,description,url,lang_var')
            ->where('url', self::getUrl())
            ->find();

        $permission_list = Db::name('auth_permission')
            ->field('id,pid,name,description,url,lang_var')
            ->select();

        // 获取当前URL所有父ID
        $praents = get_parents($permission_list, $permission['id']);

        $breadcrumb = [];
        if (!empty($praents)) {
            foreach ($praents as $v) {
                if ($v['id'] == $permission['id']) {
                    $v['active'] = 'active';
                } else {
                    $v['active'] = '';
                    $v['url'] = Url::build($v['url']);
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
        $permission_id = Db::name('auth_permission')
            ->where('url', self::getUrl())
            ->value('id');

        // 获取session信息
        $session = Session::get('user_auth', 'admin');

        // 获取所有权限集合
        $permission = self::getPermission($session['uid']);
        if (empty($permission)) {
            return false;
        }

         // 获取当前URL所有父ID
        $praents = get_parents($permission, $permission_id);
        $ids = [];
        if (!empty($praents)) {
            foreach ($praents as $v) {
                array_push($ids, $v['id']);
            }
        }

        // 遍历数组 增加active标识
        $menu = array();
        foreach($permission as $v) {
            // 给父级ID添加ACTIVE表示
            if(in_array($v['id'], $ids)) {
                $v['active'] = 'active'; // 该栏目是否高亮
            } else {
                $v['active']  = '';
            }

            // 生成URL
            if ($v['url'] != '#' || $v['url'] != ''){
                $v['url'] = Url::build($v['url']);
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
        $site = self::site($user['uid']);
        if (isset($site)) {
            $default = reset($site);
            Session::set('site_id', $default['id'], 'admin');
            Session::set('site_name', $default['name'], 'admin');
            Session::set('site_alias', $default['alias'], 'admin');
        }

        // 生成用户数据SESSION
        $data = [
            'uid'        => $user['uid'],
            'username'   => $user['username'],
            'login_time' => time(),
        ];

        return self::auth($data);
    }

    /**
     * 获取用户所有网站
     *
     * @params $uid int 用户UID
     * @return array
     */
    static public function site($uid)
    {
        return Db::name('auth_user_site')
            ->alias('us')
            ->field('s.id,s.name,s.alias,s.domain,s.theme')
            ->where('us.uid', $uid)
            ->join('site s','s.id = us.site_id')
            ->order('s.weighing', 'asc')
            ->select();
    }
    
    /**
     * 获取用户所有权限集合
     *
     * @params $uid int 用户UID
     * @return array
     */
    static public function getPermission($uid)
    {
        $roleList = Db::name('auth_user_role')
            ->where('uid', $uid)
            ->select();

        $permissionList = [];
        if (!empty($roleList)) {
            foreach ($roleList as $v) {
                $auth_role_permission = Db::name('auth_role_permission')
                    ->alias('rp')
                    ->field('p.*')
                    ->where('rp.role_id', $v['role_id'])
                    ->join('auth_permission p','p.id = rp.permission_id')
                    ->order('p.weighing', 'asc')
                    ->select();
                if (!empty($auth_role_permission)) {
                    foreach ($auth_role_permission as $vv) {
                        if (!in_array($vv, $permissionList))
                        array_push($permissionList, $vv);
                    }
                }
            }
        }

        return $permissionList;
    }

    /**
     * 创建用户登陆授权
     *
     * @params $auth array 用户信息
     * @return boolean
     */
    static protected function auth($auth)
    {
        if (!empty($auth)) {
            Session::set('user_auth', $auth, 'admin');
            Session::set('user_auth_sign', sign($auth), 'admin');
        } else {
            return false;
        }

        return true;
    }


}