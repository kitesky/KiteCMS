<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Config;
use app\common\model\SiteConfig;
use app\admin\controller\Admin;

class Score extends Admin
{
    public function index()
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

        $config = Config::get('site.memberScore');
        $data = SiteConfig::getAll($this->site_id, $config);

        return $this->fetch('index', $data);
    }
}
