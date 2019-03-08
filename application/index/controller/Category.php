<?php
namespace app\index\controller;

use think\facade\Request;
use app\common\model\BuildUrl;
use app\common\model\Kite;
use think\exception\HttpException;
use app\index\controller\Base;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentContentExtra;
use app\common\model\DocumentContentLike;
use app\common\model\DocumentComments;

class Category extends Base
{
    public function index()
    {
        $cate_alias = Request::param('cate_alias');

        // 当前栏目信息
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryByAlias($this->site_id, $cate_alias);
        if (!isset($category)) {
            throw new HttpException(404, 'The page can not be found');
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
        $list = $docObj->getDocmentPaginateByFilter($this->site_id, $category->id, $request);
        // var_dump($docObj->getLastSql());
        if (!empty($list)) {
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
