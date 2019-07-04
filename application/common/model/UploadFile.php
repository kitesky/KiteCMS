<?php
namespace app\common\model;

use think\Loader;
use think\facade\Config;
use app\common\model\SiteConfig;
use app\common\model\SiteFile;

class UploadFile
{
    // 上传配置信息
    protected $config;

    // 网站ID
    protected $site_id;

    // 错误信息
    protected $error = '';

    // 上传操作句柄
    protected $uploadHandler = null;

    // 删除操作句柄
    protected $removeHandler = null;

    public function __construct($site_id)
    {
        $this->site_id = $site_id;

        $uploadFileConfig = Config::get('site.uploadFile');
        $imageWaterConfig = Config::get('site.imageWater');
        $config = array_merge($uploadFileConfig, $imageWaterConfig);

        $newConfig = [];
        foreach ($config as $k => $v) {
            if (!empty(SiteConfig::getCofig($site_id, $k))) {
                $newConfig[$k] = SiteConfig::getCofig($site_id, $k);
            }
        }

        // 合并配置 以用户配置有限
        $this->config = array_merge($config, $newConfig);
        
        $this->uploadHandler = $this->uploadHandler();
    }

    public function getError()
    {
        return $this->error;
    }

    public function uploadHandler()
    {
        // 上传方式
        switch ($this->config['upload_type'])
        {
            case 'local':
                $type =  'Local';
                break;
            case 'alioss':
                $type =  'AliOss';
                break;
            case 'qiniuoss':
                $type =  'QinniuOss';
                break;
        }

        return Loader::factory($type, '\\app\common\model\upload\driver\\', $this->config);
    }

    public function removeHandler($type)
    {
        // 上传方式
        switch ($type)
        {
            case 'local':
                $type =  'Local';
                break;
            case 'alioss':
                $type =  'AliOss';
                break;
            case 'qiniuoss':
                $type =  'QinniuOss';
                break;
        }

        return $this->removeHandler = Loader::factory($type, '\\app\common\model\upload\driver\\', $this->config);
    }
    
    public function remove($path)
    {
        // 查询文件信息
        $fileObj = new SiteFile;
        $fileInfo = $fileObj->getFileDetailByPath($path);
        if (empty($fileInfo)) {
            $this->error = 'no such file or directory';
            return false;
        }

        // 构造删除操作句柄
        $this->removeHandler($fileInfo->upload_type);

        // 删除文件
        $ret = $this->removeHandler->remove($path);
        if ($ret == false) {
            $this->error = $this->removeHandler->getError();
        }

        // 如果有缩略图 删除缩略图
        if (!empty($fileInfo->thumb)) {
            $thumbArr = explode(',', $fileInfo->thumb);
            foreach ($thumbArr as $v) {
                $this->removeHandler->remove($v);
            }
        }

        // 删除图片记录
        $res = $fileInfo->delete();

        if ($res == true && $ret == true) {
            return true;
        } else {
            return false;
        }
    }

    public function upload($file, $fileType = 'image')
    {
        // 验证文件类型及大小
        switch ($fileType)
        {
            case 'image':
                $result = $file->check(['ext' => $this->config['upload_image_ext'], 'size' => $this->config['upload_image_size']*1024]);
                if(empty($result)){
                    // 上传失败获取错误信息
                    $this->error = $file->getError();
                    return false;
                }
                break;
            case 'video':
                $result = $file->check(['ext' => $this->config['upload_video_ext'], 'size' => $this->config['upload_video_size']*1024]);
                if(empty($result)){
                    // 上传失败获取错误信息
                    $this->error = $file->getError();
                    return false;
                }
                break;
            case 'attach':

                $result = $file->check(['ext' => $this->config['upload_attach_ext'], 'size' => $this->config['upload_attach_size']*1024]);
                if(empty($result)){
                    // 上传失败获取错误信息
                    $this->error = $file->getError();
                    return false;
                }
                break;
        }

        $result =  $this->uploadHandler->upload($file);
        $data   =  array_merge($result, ['site_id' => $this->site_id]);
        SiteFile::create($data);
        return $data;
    }

}