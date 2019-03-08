<?php
namespace app\index\controller;

use think\facade\Request;
use app\index\controller\Base;
use app\common\model\SiteCaptcha;

class Captcha extends Base
{
    public function index()
    {
        $id = Request::param('id');
        $obj = new SiteCaptcha;
        return $obj->build($this->site_id, $id);
    }
}
