<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\facade\Config;

class Index extends Base
{
    public function index()
    {

        return $this->fetch('index/index');
    }
}
