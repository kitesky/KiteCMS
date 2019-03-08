<?php
namespace app\common\model\upload\driver;

use OSS\OssClient;

class AliOss
{
    // 操作句柄
    protected $handler = null;

    // 上传配置信息
    protected $config = [
        'alioss_key'      => '',
        'alioss_secret'   => '',
        'alioss_endpoint' => '',
        'alioss_bucket'   => '',
    ];

    // 错误信息
    protected $error = '';

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->handler = $this->handler();
    }

    public function getError()
    {
        return $this->error;
    }

    public function handler()
    {
        $accessKeyId     = $this->config['alioss_key'];
        $accessKeySecret = $this->config['alioss_secret'];
        $endpoint        = $this->config['alioss_endpoint'];
        return new OssClient($accessKeyId, $accessKeySecret, $endpoint);
    }

    public function upload($file)
    {
        $info = $file->getInfo();
        $object = $file->hash() . '.' . ltrim($this->getFileExt($info['name']));

        try {
            $result = $this->handler->uploadFile($this->config['alioss_bucket'], $object, $info['tmp_name']);
            // 成功上传后 获取上传信息
            $data = [
                'upload_type' => 'alioss',
                'title'       => $info['name'],
                'size'        => $info['size'],
                'name'        => $object,
                'ext'         => $this->getFileExt($info['name']),
                'url'         => $result['info']['url'],
            ];
            return $data;
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function remove($path)
    {
        $object = basename($path);

        // 判断object是否存在
        $doesExist = $this->handler->doesObjectExist($this->config['alioss_bucket'], $object);

        // 删除object
        if ($doesExist == true) {
            return $this->handler->deleteObject($this->config['alioss_bucket'], $object);
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