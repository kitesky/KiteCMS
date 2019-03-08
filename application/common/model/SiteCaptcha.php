<?php
namespace app\common\model;

use think\Model;
use think\captcha\Captcha;
use think\facade\Config;
use app\common\model\SiteConfig;

class SiteCaptcha extends Model
{
    public function build($site_id, $id = '')
    {
        $config = Config::get('site.captcha');
        $config = SiteConfig::getAll($site_id, $config);

        $captcha = new Captcha;
        $captcha->useZh    = $config['captcha_useZh'];
        $captcha->useImgBg = $config['captcha_useImgBg'];
        $captcha->fontSize = $config['captcha_fontSize'];
        $captcha->imageH   = $config['captcha_imageH'];
        $captcha->imageW   = $config['captcha_imageW'];
        $captcha->length   = $config['captcha_length'];
        $captcha->useCurve = $config['captcha_useCurve'];
        $captcha->useNoise = $config['captcha_useNoise'];

        return $captcha->entry($id);
    }

    public function check($value, $id = '')
    {
        $captcha = new Captcha();
        if(!$captcha->check($value, $id))
        {
            return false;
        }

        return true;
    }

}