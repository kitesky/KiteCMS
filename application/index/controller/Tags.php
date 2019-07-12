<?php
namespace app\index\controller;

use think\facade\Request;
use app\index\controller\Base;
use app\common\model\Tags as TagsModel;

class Tags extends Base
{
    public function detail()
    {
        $tag_id = Request::param('tag_id');

        $obj = new TagsModel;
        $taginfo = $obj->where('tag_id', $tag_id)->find();
        $list = $obj->getDocumenListByTagId($tag_id);

        $data = [
            'taginfo' => $taginfo,
            'doclist' => $list['list'],
            'docpage' => $list['page'],
        ];

        return $this->fetch('tags/index', $data);
    }

}
