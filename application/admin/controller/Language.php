<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use think\facade\Cookie;
use think\facade\Lang;
use app\common\controller\Common;

class Language extends Common
{
    public function index()
    {

    }

    public function change()
    {
        $request = Request::param();

        if (empty($request['name'])) {
            return $this->response(201, Lang::get('Administrator login'));
        }

        // 判断语言是否存在并设置语言cookie
        $id = Db::name('language')->where('name', $request['name'])->value('id');
        if(is_numeric($id)) {
            Cookie::set('think_var', $request['name']);
        }
        
        if (Cookie::has('think_var')) {
            return $this->response(200, Lang::get('Success'));
        } else {
             return $this->response(201, Lang::get('Fail'));
        }
    }
}