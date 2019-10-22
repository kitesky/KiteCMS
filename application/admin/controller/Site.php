<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Lang;
use think\facade\Config;
use app\common\model\Site as SiteModel;
use app\common\model\Auth;
use app\admin\controller\Admin;
use app\common\model\SiteConfig;
use app\common\validate\SiteValidate;

class Site extends Admin
{
    public function config()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            foreach ($request as $k => $v) {
                $res = SiteConfig::saveCofig($this->site_id, $k, $v);
            }

            if (is_numeric($res)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $config_menu = [
            'base'       => Lang::get('Base'), 
            'email'      => Lang::get('Email'), 
            'sms'        => Lang::get('Sms'), 
            'captcha'    => Lang::get('Captcha'), 
            'imageWater' => Lang::get('Watermarking'), 
            'uploadFile' => Lang::get('Upload'),
            'memberScore'=> Lang::get('Score'),
            'payment'    => Lang::get('Payment'),
        ];
        $config_name = Request::param('config_name', 'base');
        $config = Config::get('site.' . $config_name);
        $config_option = SiteConfig::getAll($this->site_id, $config);


        // 角色组
        $role = Db::name('auth_role')->field('role_id,role_name')->select();

        $data = [
            'config_name'   => $config_name,
            'config_menu'   => $config_menu,
            'config_option' => $config_option,
            'role'          => $role,
        ];

        return $this->fetch('config', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new SiteValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $siteObj = new SiteModel;
            $siteObj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($siteObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $info = SiteModel::get($id);

        $data = [
            'info'  => $info,
            'theme' => $this->getThemeList(),
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');

        $return = SiteModel::destroy($id);

        if ($return !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

    public function change()
    {
        $request = Request::param();

        if (empty($request['id'])) {
            return $this->response(201, Lang::get('Parameter missing'));
        }

        // 验证是否有权限管理该站点 (预防AJAX提交不属于自己管理的站点ID)
        $auth = new Auth;
        $ids = $auth->getSiteIds($this->uid);
        if (!in_array($request['id'], $ids)) {
            return $this->response(201, Lang::get('Fail'));
        }

        // 判断站点是否存在并设置session
        $siteObj = new SiteModel;
        $site =$siteObj
            ->field('id,name,domain,theme')
            ->where('id', $request['id'])
            ->find();

        if(isset($site)) {
            Session::set('site_id', $site['id'], 'admin');
            Session::set('site_name', $site['name'], 'admin');
            Session::set('site_url', $site['domain'], 'admin');
            Session::set('site_theme', isset($site['theme']) ? $site['theme'] : 'default', 'admin');
        }

        if (Session::has('site_id', 'admin')) {
            return $this->response(200, Lang::get('Success'));
        } else {
             return $this->response(201, Lang::get('Fail'));
        }
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new SiteValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $siteObj = new SiteModel;
            $siteObj->allowField(true)->save($request);

            if (is_numeric($siteObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $data = [
            'theme'      => $this->getThemeList(),
        ];

        return $this->fetch('create', $data);
    }

    public function index()
    {
        $request = Request::param();

        $siteObj = new SiteModel;

        $params = [];
        $search = [];

        if (isset($request['q'])) {
            $q           = $request['q'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
        }else {
            $q = '';
            $search['q'] = '';
        }

        // 验证是否有权限管理该站点 (预防AJAX提交不属于自己管理的站点ID)
        $auth = new Auth;
        $ids = $auth->getSiteIds($this->uid);

        // 分页列表
        $list = $siteObj
            ->whereOr('name|title','like','%'.$q.'%')
            ->where('id', 'in', $ids)
            ->order('sort asc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        $data = [
            'search' => $search,
            'list'   => $list,
            'page'   => $list->render(),
        
        ];

        return $this->fetch('index', $data);
    }
}