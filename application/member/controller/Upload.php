<?php
namespace app\member\controller;

use think\facade\Request;
use app\member\controller\Base;
use app\common\model\UploadFile;

class Upload extends Base
{
    public function uploadFile()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file);

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }

}