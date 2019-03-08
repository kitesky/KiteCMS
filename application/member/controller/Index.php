<?php
namespace app\member\controller;

use app\member\controller\Base;

class Index extends Base
{
    public function index()
    {
        return $this->fetch('index/index');
    }
}