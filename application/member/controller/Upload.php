<?php
namespace app\member\controller;

use think\facade\Request;
use think\facade\Env;
use app\member\controller\Base;
use app\common\model\UploadFile;
use app\common\model\DocumentContent;
use app\common\model\Order;

class Upload extends Base
{
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
    
    public function download()
    {
        $doc_id = Request::param('doc_id');
        if (!is_login()) {
            $retunurl = Request::url();
            $this->error('请先登录账户', $this->referer());
        }

        // 查看文档信息
        $docObj = new DocumentContent;
        $document = $docObj->field('id,price,attach')->where('id', $doc_id)->find();

        // 查看是否购买
        $orederObj = new Order;
        $map = [
            'uid' => $this->uid,
            'document_id' => $doc_id,
            'status'      => 1,
        ];
        $order = $orederObj->where($map)->find();
 
        if (empty($order) && $document['price'] > 0) {
            $buy_url = url('member/order/create', ['id' => $doc_id]);
            $this->error('您无权限下载', $buy_url);
        }

        $filePath = Env::get('root_path') . $document['attach'];
        $file = new \SplFileInfo($filePath);
        if($file->isFile()) {
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file->getFilename() . '"');
            header('Content-Transfer-Encoding: binary');
            readfile($filePath);
        }else{
            $this->error('没有可用附件供下载');
        }

    }

}