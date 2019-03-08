<?php
namespace app\common\model\upload;

use think\Image;
use think\facade\Config;

class Water
{
    // 水印配置信息
    protected $config = [
        'water_status'     => '0',
        'water_logo'       => '',
        'water_position'   => '1',
        'water_quality'    => '80',
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

    public function localWater($path)
    {
        $filePath = Config::get('site.public_path') . $path;
        if (!is_file($filePath)) {
            $this->error = '文件不存在';
            return false;
        }

        if ($this->config['water_status'] == 1) {
            $image = Image::open($filePath);
            return $image
                ->water(Config::get('site.public_path') . $this->config['water_logo'], $this->config['water_position'], $this->config['water_quality'])
                ->save($filePath);
        } else {
            return false;
        }
    }
}