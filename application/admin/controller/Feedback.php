<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\admin\controller\Admin;
use app\common\model\Feedback as FeedbackModel;

class Feedback extends Admin
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

        return $this->fetch('index', $data);
    }


    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 写入
            $obj = new FeedbackModel;
            $obj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $request = Request::param();
        $obj = new FeedbackModel;
        $info = $obj->where('id', $request['id'])->find();

        $data = [
            'info'  => $info,
        ];

        return $this->fetch('edit', $data);
    }

    public function handle()
    {
        $request = Request::instance()->param();

        $obj = new FeedbackModel;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = $obj::destroy($v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'approval':
                foreach ($request['ids'] as $v) {
                    $result = $obj
                        ->where('id', $v)
                        ->setField('status', 1);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'progress':
                foreach ($request['ids'] as $v) {
                    $result = $obj
                        ->where('id', $v)
                        ->setField('status', 0);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
        }

        return $this->response(201, Lang::get('Fail'));
    }

    public function remove()
    {
        $id = Request::param('id');

        // 删除
        $des = FeedbackModel::destroy($id);

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
}