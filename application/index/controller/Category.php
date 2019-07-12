<?php
namespace app\index\controller;

use think\facade\Request;
use app\common\model\BuildUrl;
use app\common\model\Kite;
use app\common\model\SiteConfig;
use think\exception\HttpException;
use app\index\controller\Base;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentContentExtra;
use app\common\model\DocumentContentLike;
use app\common\model\DocumentComments;
use app\common\model\Tags;

class Category extends Base
{
    public function index()
    {
        $cat_id = Request::param('cat_id');

        // 当前栏目信息
        $cateObj = new DocumentCategory;
        $category = $cateObj->find($cat_id);
        if (empty($category)) {
            $this->error('您访问的页面不正确');
        }

        // 筛选条件
        $request = Request::param();
        $cmsObj = new Kite;
        $filter = $cmsObj->filter($this->site_id, $request);

        // 文档信息
        $docObj = new DocumentContent;
        $extraObj = new DocumentContentExtra;
        $likeObj = new DocumentContentLike;
        $commObj = new DocumentComments;
        $tagsObj = new Tags;
        
        $site_list_rows = !empty(SiteConfig::getCofig($this->site_id, 'list_rows')) ? SiteConfig::getCofig($this->site_id, 'list_rows') : 10;
        $list_rows      = !empty($category->list_rows) ? $category->list_rows : $site_list_rows;
        $list = $docObj->getDocmentPaginateByFilter($this->site_id, $category->id, $list_rows, $request);

        if (!$list->isEmpty()) {
            foreach ($list as $v) {

                $v->url = BuildUrl::instance($this->site_id)->documentUrl(['id' => $v->id]);
                $v->extra = $extraObj->getContentExtraFormatKeyValue($v->id);
                // 加载自定义扩展信息
                $v->extra = $extraObj->getContentExtraFormatKeyValue($v->id);

                // 图集
                $v->album = !empty($v->album) ? explode(',', $v->album) : [];

                // 评论总数
                $v->comments_total = $commObj->getCommentsCount($v->id);
                
                // 喜欢次数
                $v->like = $likeObj->likeCount($v->id);
                $v->unlike = $likeObj->unlikeCount($v->id);

                // Tags
                $v->tags = $tagsObj->getDocumentTags($v->id);
            }
        }

        $data = [
            'filter'  => $filter,
            'doclist' => $list,
            'catinfo' => $category,
            'docpage' => $list->render(),
        ];

        return $this->fetch($category->list_tpl, $data);
    }
}
