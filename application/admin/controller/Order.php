<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\admin\controller\Admin;
use app\common\model\Order as OrderModel;

class Order extends Admin
{
    public function index()
    {
        $request = Request::param();

        $query = [
            'site_id'      => $this->site_id,
            'q'            => isset($request['q']) ? $request['q'] : '',
            'payment_type' => isset($request['payment_type']) ? $request['payment_type'] : '',
            'status'       => isset($request['status']) ? $request['status'] : '',
        ];
        $args = [
            'query'  => $query,
            'field'  => '',
            'order'  => 'order_id desc',
            'params' => $query,
            'limit'  => 15,
        ];

        // 分页列表
        $obj = new OrderModel;
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
        $order_id = Request::param('order_id');

        $obj = new OrderModel;
        $info = $obj->find($order_id);
        $this->assign('info', $info);
        return $this->fetch('edit');
    }

    public function remove()
    {
        $order_id = Request::param('order_id');
        $res = OrderModel::destroy($order_id);
        if ($res !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

}