<?php
namespace app\admin\controller;

use think\facade\Request;
use app\admin\controller\Admin;
use app\common\model\UploadFile;

class Upload extends Admin
{
    public function uploadFile()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file, 'image');

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }

    // 图片上传
    public function uploadImage()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file, 'image');

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }

    // 视频上传
    public function uploadVideo()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file, 'video');

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }

    // 附件上传
    public function uploadAttach()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file, 'attach');

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }
}