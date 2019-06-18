<?php
namespace app\index\controller;

use think\facade\Request;
use think\facade\Lang;
use app\common\model\BuildUrl;
use think\exception\HttpException;
use app\index\controller\Base;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentContentExtra;
use app\common\model\DocumentComments;
use app\common\model\DocumentContentLike;
use app\common\model\DocumentCommentsLike;

class Document extends Base
{
    public function index()
    {
        $id = Request::param('id');

        // 文档信息
        $docObj = new DocumentContent;
        $extraObj = new DocumentContentExtra;
        $commObj = new DocumentComments;
        $likeObj = new DocumentContentLike;
        $commLikeObj = new DocumentCommentsLike;
        $document = $docObj->getDocumentById($this->site_id, $id);
        if (!isset($document)) {
            throw new HttpException(404, 'The page can not be found');
        } else {
            // 访问量+1
            $document->pv = $document->pv + 1;
            $document->save();

            // 加载自定义扩展信息
            $document->extra = $extraObj->getContentExtraFormatKeyValue($document->id);

            // 图集
            $document->album = !empty($document->album) ? explode(',', $document->album) : [];

            // 评论总数
            $document->comments_total = $commObj->getCommentsCount($document->id);
            
            // 喜欢次数
            $document->like = $likeObj->likeCount($document->id);
            $document->unlike = $likeObj->unlikeCount($document->id);
        }

        // 栏目信息
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryById($this->site_id, $document->cid);
        if (isset($category)) {
            $category->url = BuildUrl::instance($this->site_id)->categoryUrl(['cat_id' => $category->id]);
        }

        // 上一条信息
        $previous = $docObj->getDocumentPrevious($this->site_id, $id);
        if (isset($previous)) {
            $previous->url = BuildUrl::instance($this->site_id)->documentUrl(['id' => $previous->id]);
        }

        // 下一条信息
        $next = $docObj->getDocumentNext($this->site_id, $id);
        if (isset($next)) {
            $next->url = BuildUrl::instance($this->site_id)->documentUrl(['id' => $next->id]);
        }

        // 评论信息
        $comments = $commObj->getCommentsPaginate($this->site_id, $document->id);
        if (!empty($comments)) {
            foreach ($comments as $v) {
                $v->like = $commLikeObj->likeCount($v->id);
                $v->unlike = $commLikeObj->unlikeCount($v->id);
            }
        }

        $data = [
            'docinfo'  => $document,
            'catinfo'  => $category,
            'prev'     => $previous,
            'next'     => $next,
            'commlist' => list_for_level($comments),
            'commpage' => $comments->render(),
        ];

        return $this->fetch($category->detail_tpl, $data);
    }

    public function like()
    {
        $request = Request::param();

        $obj = new DocumentContentLike;

        $is_like = $obj->isLike(get_client_ip(), $request['id']);

        if (empty($is_like)) {
            $data = [
                'like'        => $request['type'] == 'like' ? 1 : 0,
                'unlike'      => $request['type'] == 'unlike' ? 1 : 0,
                'document_id' => $request['id'],
                'ip'          => get_client_ip(),
            ];

            $obj->save($data);

            if (true == $obj->id) {
                $ret  = [
                    'like'   => $obj->likeCount($request['id']),
                    'unlike' => $obj->unlikeCount($request['id']),
                ];

                return $this->response(200, Lang::get('success'), $ret);
            }
        } else {
            return $this->response(201, Lang::get('This record already exists'));
        }
    }
}
