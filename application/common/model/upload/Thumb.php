<?php
namespace app\common\model\upload;

use think\Image;
use think\facade\Config;
use app\common\model\SiteFile;

class Thumb
{
    // 图片宽度
    protected $width;

    // 图片高度
    protected $height;

    public function thumb($filePath, $width = 0, $height = 0)
    {
        $fileInfo = SiteFile::where('url', $filePath)->find();
        if (!isset($fileInfo)) {
            return false;
        }

        $this->filePath = $filePath;
        $this->width = $width;
        $this->height = $height;

        switch ($fileInfo->upload_type)
        {
            case 'local':
                return $this->local($fileInfo);
                break;
            case 'alioss':
                return $this->aliOss($fileInfo);
                break;
            case 'qiniuoss':
                return $this->qiniuOss($fileInfo);
                break;
        }
    }
    
    public function local($fileInfo)
    {
        $path = str_replace('\\','/',Config::get('site.public_path') . $fileInfo->url);

        if (!is_file($path)) {
            return false;
        }
        $urlArr = explode('.', $fileInfo->url);
        $image = Image::open($path);

        // echo $this->width;
        $saveName = $urlArr[0] . '_' . $this->width . 'x' . $this->height . '.' . $urlArr[1];
        $savePath = str_replace('\\','/',Config::get('site.public_path') . $saveName);

        if (is_file($savePath)) {
            $image = Image::open($savePath);
            if ($image->width() == $this->width && $image->height() == $this->height) {
                return $saveName;
            }
        } else {
            $image->thumb($this->width,$this->height,2)->save($savePath);
            // 记录缩略图
            $fileObj = new SiteFile;
            $fileObj->setImageThumb($fileInfo->id, $saveName);
            return $saveName;
        }
    }

    public function aliOss($fileInfo)
    {
        $str = '?x-oss-process=image/resize,m_fill,h_%s,w_%s';
        $params = sprintf($str, $this->height, $this->width);
        $url = $fileInfo->url . $params;
        return $url;
    }

    public function qiniuOss($fileInfo)
    {
        $str = '?imageView2/1/w/%s/h/%s';
        $params = sprintf($str, $this->width, $this->height);
        $url = $fileInfo->url . $params;
        return $url;
    }
}