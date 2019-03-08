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
            return Url::build('index/index/index', ['site_alias' => $site->alias]);
        } else {
            return $site->domain;
        }
    }

    public function categoryUrl($request)
    {
        $params = [];
        if (!empty($request['id'])) {
            $cate = DocumentCategory::get($request['id']);
            $params = [
                'cate_alias' => $cate->alias,
            ];
        } else if (!empty($request['alias'])) {
            $params = [
                'cate_alias' => $request['alias'],
            ];
            unset( $request['alias']);
        }

        $params = array_merge($params, $request);
        return Url::build('index/category/index', $params);
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