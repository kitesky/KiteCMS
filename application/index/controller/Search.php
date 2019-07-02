<?php
namespace app\index\controller;

use think\facade\Request;
use app\index\controller\Base;
use app\common\model\BuildUrl;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentComments;

class Search extends Base
{
    public function index()
    {
        $request = Request::param();

        $documentObj = new DocumentContent;

        $map = [
            array('status', 'eq', 1),
            array('site_id', 'eq', $this->site_id),
        ];
        $params = [];
        $search = [];

        // 关键词
        if (isset($request['q'])) {
            $q           = ['title','like','%'.$request['q'].'%'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
            array_push($map, $q);
        }else {
            $q = [];
            $search['q'] = '';
        }

        // 分页列表
        $list = $documentObj
            ->where($map)
            ->order('id desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);
        $newList = [];
        if (!empty($list)) {
            $cateObj = new DocumentCategory;
            $commObj = new DocumentComments;
            foreach ($list as $v) {
                $v->category = $cateObj->where('id', $v->cid)->find();
                $v->category->url = BuildUrl::instance($this->site_id)->categoryUrl(['cat_id' => $v->category->id]);
                $v->comments = $commObj->getCommentsCount($v->id);
                $v->url = BuildUrl::instance($this->site_id)->documentUrl(['id' => $v->id]);
                array_push($newList,$v);
            }
        }

        $data = [
            'doclist' => $newList,
            'docpage'     => $list->render(),
            'search'   => $search,
        ];

        return $this->fetch('search/index', $data);
    }
}
