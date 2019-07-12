<?php
namespace app\common\controller;

use think\facade\View;
use think\facade\Request;
use think\facade\Session;
use app\common\model\Site;
use app\common\model\AuthUser;
use app\common\controller\Common;

class IndexCommon extends Common
{
    // 站点ID
    protected $site_id;

    // 站点别名
    protected $site_alias;

    // 站点名称
    protected $site_name;

    // 模板名称
    protected $theme;
    
    // 会员uid
    protected $uid;

    // 会员信息对象
    protected $user;

    // 会员组信息
    protected $role;

    public function __construct()
    {
        parent::__construct();

        // 查询站点信息
        $domain  = Request::domain();
        $siteObj = new Site;
        $siteInfo = $siteObj->getSiteByDomain($domain);
        // 未绑定的域名 访问默认站点
        if (!isset($siteInfo)) {
            $siteInfo = $siteObj->getDefaultSite();
        }

        // 没有站点信息抛出404
        if ($siteInfo->status == 1) {
            throw new \think\exception\HttpException(404, 'This site is closed');
        }

        // 站点信息赋值
        $this->site_id    = $siteInfo->id;
        $this->site_name  = $siteInfo->name;
        $this->theme      = $siteInfo->theme ? $siteInfo->theme : 'default';

        // 设置session
        Session::set('site_id', $siteInfo->id, 'index');

        $user_auth = Session::get('user_auth', 'index');
        $this->uid = isset($user_auth) ? $user_auth['uid'] : null;
        $this->role = isset($user_auth) ? $user_auth['role'] : null;

        // 获取用户信息
        $obj  = new AuthUser;
        $user = $obj->get($this->uid);
        $this->user = isset($user) ? $user : null;

        // 站点赋值
        View::share('member', $this->user);
        View::share('role', $this->role);
    }

    public function referer()
    {
        return url('member/passport/login') . '?referer=' . urlencode(Request::url(true));

    }
}