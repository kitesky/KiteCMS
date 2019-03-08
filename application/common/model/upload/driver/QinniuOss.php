<?php
namespace app\common\model\upload\driver;

use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

class QinniuOss
{
    // auth
    protected $auth;

    // token
    protected $token;

    // 上传操作句柄
    protected $uploadHandler = null;

    // 上传配置信息
    protected $config = [
        'qiniu_ak'     => '',
        'qiniu_sk'     => '',
        'qiniu_bucket' => '',
        'qiniu_domain' => '',
    ];

    // 错误信息
    protected $error = '';

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        
        $this->auth          = new Auth($this->config['qiniu_ak'], $this->config['qiniu_sk']);
        $this->token         = $this->auth->uploadToken($this->config['qiniu_bucket']);
        $this->uploadHandler = new UploadManager();
    }

    public function getError()
    {
        return $this->error;
    }

    public function upload($file)
    {
        $info = $file->getInfo();
        // 要上传文件的本地路径
        $filePath = $info['tmp_name'];
        // 上传到七牛后保存的文件名
        $key = $file->hash() . '.' . ltrim($this->getFileExt($info['name']));;
        list($ret, $err) = $this->uploadHandler->putFile($this->token, $key, $filePath);
        if ($err !== null) {
            $this->error = $err;
            return false;
        } else {
            // 成功上传后 获取上传信息
            $data = [
                'upload_type' => 'qiniuoss',
                'title'       => $info['name'],
                'size'        => $info['size'],
                'name'        => $key,
                'ext'         => $this->getFileExt($info['name']),
                'url'         => $this->config['qiniu_domain'] . '/' . $ret['key'],
            ];
            return $data;
        }
    }

    public function remove($path)
    {
        $key = basename($path);

        $config = new Config();
        $bucketManager = new BucketManager($this->auth, $config);
        $err = $bucketManager->delete($this->config['qiniu_bucket'], $key);

        if ($err == null) {
            return true;
        } else {
            $this->error = $err->message();
            return false;
        }
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