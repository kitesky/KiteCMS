<?php
namespace app\admin\controller;

use think\facade\Request;
use app\common\model\UploadFile;
use app\common\model\SiteFile;
use app\admin\controller\Admin;

class Ueditor extends Admin
{
    public function index()
    {
        $action = Request::param('action');
        $callback = Request::param('callback');

        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");

        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $this->getConfig()), true);
        switch ($action) {
            case 'config':
                $result =  json_encode($config);
                break;

            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $result = $this->uploadFile();
                break;

            /* 列出图片 */
            case 'listimage':
                $result = $this->listFile();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->listFile();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->catchImage();
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($callback)) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                echo htmlspecialchars($callback) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }

    public function uploadFile()
    {
        // 获取表单上传文件
        $file = Request::file('upfile');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file);
        
        return $this->getFileInfo($ret);
    }

    public function listFile()
    {
        $request = Request::param();

        $fileObj = new SiteFile;
        return $fileObj->listFile($this->site_id, $request['start'], $request['size']);
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo($ret)
    {
        $data = array(
            "state" => "SUCCESS",
            "url" => $ret['url'],
            "title" => $ret['name'],
            "original" => $ret['title'],
            "type" => $ret['ext'],
            "size" => $ret['size'],
        );

        return json_encode($data);
    }

    /**
     * ueditor配置
     * @return json
     */
    public function getConfig()
    {
        return '{
            "imageActionName": "uploadimage",
            "imageFieldName": "upfile",
            "imageMaxSize": 2048000,
            "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
            "imageCompressEnable": true,
            "imageCompressBorder": 1600,
            "imageInsertAlign": "none",
            "imageUrlPrefix": "",
            "imagePathFormat": "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",

            "scrawlActionName": "uploadscrawl",
            "scrawlFieldName": "upfile",
            "scrawlPathFormat": "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            "scrawlMaxSize": 2048000,
            "scrawlUrlPrefix": "", 
            "scrawlInsertAlign": "none",

            "snapscreenActionName": "uploadimage",
            "snapscreenPathFormat": "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            "snapscreenUrlPrefix": "",
            "snapscreenInsertAlign": "none",

            "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
            "catcherActionName": "catchimage",
            "catcherFieldName": "source",
            "catcherPathFormat": "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",
            "catcherUrlPrefix": "",
            "catcherMaxSize": 2048000,
            "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],

            "videoActionName": "uploadvideo",
            "videoFieldName": "upfile",
            "videoPathFormat": "/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}",
            "videoUrlPrefix": "",
            "videoMaxSize": 102400000,
            "videoAllowFiles": [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"],

            "fileActionName": "uploadfile",
            "fileFieldName": "upfile",
            "filePathFormat": "/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}",
            "fileUrlPrefix": "",
            "fileMaxSize": 51200000,
            "fileAllowFiles": [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ],

            "imageManagerActionName": "listimage",
            "imageManagerListPath": "/upload/image/",
            "imageManagerListSize": 20,
            "imageManagerUrlPrefix": "",
            "imageManagerInsertAlign": "none",
            "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"],

            "fileManagerActionName": "listfile",
            "fileManagerListPath": "/upload/file/",
            "fileManagerUrlPrefix": "",
            "fileManagerListSize": 20,
            "fileManagerAllowFiles": [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ]

        }';
    }
}
