<?php
namespace app\common\model;

use think\facade\Url;
use app\common\model\Site;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;

class BuildUrl
{
    // 实现单例模式
    private static $_instance;

    // 站点ID
    private static $site_id;

    private function __construct()
    {
        
    }

    // 防止被克隆
    private function __clone()
    {
        
    }

    // 单例访问
    public static function instance($site_id) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        self::$site_id = $site_id;
        return self::$_instance;
    }

    public function siteUrl()
    {
        $site = Site::get(self::$site_id);
        if (empty($site->domain)) {
            return Url::build('index/index/index', ['id' => $site->id]);
        } else {
            return $site->domain;
        }
    }

    public function categoryUrl($request)
    {
        return Url::build('index/category/index', $request);
    }

    public function documentUrl($request)
    {
        $params = [];
        if (!empty($request['id'])) {
            $params = [
                'id' => $request['id'],
            ];
        }

        return Url::build('index/document/index', $params);
    }
}