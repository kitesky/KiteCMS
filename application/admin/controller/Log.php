<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\admin\controller\Admin;
use app\common\model\Log as LogModel;

class Log extends Admin
{
    public function index()
    {
        $log = new LogModel;
        $logData = $log->getUserLoginLog();
        $this->assign('log', $logData);
        return $this->fetch('index');
    }


    public function handle()
    {
        $request = Request::instance()->param();

        switch ($request['type']) {
            case 'delete':
                $result = Db::name('log')->where('site_id', $this->site_id)->delete();
                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
        }

        return $this->response(201, Lang::get('Fail'));
    }
}
