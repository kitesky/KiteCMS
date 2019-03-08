<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\facade\Config;

class Test extends Base
{
    public function index()
    {
        $obj = new \app\common\model\UploadFile($this->site_id);
        var_dump($obj->remove('http://onxr8mt8y.bkt.clouddn.com/93aae0250672c707a0b6b0a9fcd543157f54bd4b.jpg'));
        // $obj = new \app\common\model\Message($this->site_id);
        // var_dump($obj->sendSMS('18780221108', '123'));
        // var_dump($obj->sendEmail('4995952@qq.com', '测试一下', 'html'));
        // $filePath ='http://onxr8mt8y.bkt.clouddn.com/483d463d562d34155102db45c6fb3621a8adea68.jpg';
        // $obj = new \app\common\model\ImageThumb;
        // return $obj->thumb($filePath, 220, 220);
        // return thumb($filePath, 222, 222);
        // var_dump(\think\facade\Request::param());die;
    }
}
