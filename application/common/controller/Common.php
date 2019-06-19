<?php
namespace app\Common\controller;

use think\Controller;

class Common extends Controller
{
    protected function response($code, $msg = '', $data = [])
    {
        $response = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        return json($response, 200);
    }

    public function currentPathUrl()
    {
        return strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());
    }
}