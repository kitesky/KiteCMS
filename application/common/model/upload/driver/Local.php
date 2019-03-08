<?php
namespace app\common\model\upload\driver;

use think\Image;
use think\facade\Config;
use app\common\model\upload\Water;

class Local
{
    // 上传配置信息
    protected $config = [
        'upload_path'      => '',
        'upload_type'      => '',
    ];

    // 错误信息
    protected $error = '';

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    public function getError()
    {
        return $this->error;
    }

    public function upload($file)
    {
        $uploadPath = $this->config['upload_path'];
        if (!is_dir($uploadPath)) {
           mkdir(iconv("UTF-8", "GBK", $uploadPath), 0777, true);
        }

        // 上传文件
        $result = $file->rule('date')->move($uploadPath);

        if(empty($result)){
            // 上传失败获取错误信息
            $this->error = $file->getError();
            return false;
        }

        // 成功上传后 获取上传信息
        $info = $file->getInfo();
        $data = [
            'upload_type' => 'local',
            'title'       => $info['name'],
            'size'        => $info['size'],
            'name'        => $result->getFilename(),
            'ext'         => $this->getFileExt($info['name']),
            'url'         => str_replace('\\', '/', DIRECTORY_SEPARATOR . $this->config['upload_path'] . DIRECTORY_SEPARATOR . $result->getSaveName()),
        ];

        // 图片水印
        if (in_array($this->getFileExt($result->getFilename()), explode(',', $this->config['upload_image_ext']))) {
            $water = new Water($this->config);
            $water->localWater($data['url']);
        }

        return $data;
    }

    public function remove($path)
    {
        $filePath = config::get('site.public_path') . $path;
        if (is_file($filePath)) {
            if (unlink($filePath)) {
                return true;
            }
        } else {
            $this->error = 'no such file or directory';
        }

        return false;
    }

    /**
     * 获取文件扩展名
     * @params string $fileName 文件名
     * @return string
     */
    private function getFileExt($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }
}