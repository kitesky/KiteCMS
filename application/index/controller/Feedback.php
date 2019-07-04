<?php
namespace app\index\controller;

use think\facade\Request;
use app\index\controller\Base;
use app\common\model\Feedback as FeedbackModel;
use app\common\validate\FeedbackValidate;

class Feedback extends Base
{
    public function index()
    {
        $request = Request::param();

        // 查询条件
        $query = [
            'site_id' => $this->site_id,
            'status'  => isset($request['status']) ? $request['status'] : '',
        ];
        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'id desc',
            'params' => $query,
            'limit'  => 15,
        ];

        // 分页列表
        $obj = new FeedbackModel;
        $list = $obj->select($args);

        $data = [
            'search'   => $query,
            'list'     => $list,
            'page'     => $list->render(),
        ];

        return $this->fetch('feedback/index', $data);
    }

    public function create()
    {
        $request = Request::param();

        // 验证数据
        $validate = new FeedbackValidate();
        $validateResult = $validate->scene('create')->check($request);
        if (!$validateResult) {
            return $this->response(201, $validate->getError());
        }

        $insertData = [
            'site_id' => $this->site_id,
            'uid'     => !empty($this->uid) ? $this->uid : '',
        ];
        $insertData = array_merge($insertData, $request);

        // 写入
        $obj = new FeedbackModel;
        $obj->allowField(true)->save($insertData);

        if (is_numeric($obj->id)) {
            return $this->response(200, '操作成功');
        }

        return $this->response(201, '操作失败');
    }
}
