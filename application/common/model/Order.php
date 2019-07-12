<?php
namespace app\common\model;

use think\Model;

class Order extends Model
{
    protected $pk = 'order_id';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getStatusTextAttr($value, $data)
    {
        $status = [0=>'未支付', 1=>'已付款', 2=>'退款'];
         return $status[$data['status']];
    }

    public function getPaymentTypeTextAttr($value, $data)
    {
        $paymentType = [0=>'未支付', 1=>'支付宝', 2=>'微信'];
         return $paymentType[$data['status']];
    }

    public function find($order_id)
    {
        return $this->where('order_id', $order_id)
            ->alias('o')
            ->field('o.*,d.title,u.username,u.phone')
            ->join('document_content d','d.id = o.document_id')
            ->join('auth_user u','u.uid = o.uid')
            ->find();
    }
    
    public function select($request)
    {
        $query = []; 

        // site_id
        if (!empty($request['query']['site_id'])) {
            $query[] = ['site_id', 'eq', $request['query']['site_id']];
        }

        // uid
        if (!empty($request['query']['uid'])) {
            $query[] = ['uid', 'eq', $request['query']['uid']];
        }

        // out_trade_no
        if (isset($request['query']['q']) && is_numeric($request['query']['q'])) {
            $query[] = ['out_trade_no','like','%'.$request['query']['q'].'%'];
        }

        // payment_type
        if (!empty($request['query']['payment_type'])) {
            $query[] = ['payment_type', 'eq', $request['query']['payment_type']];
        }

        // status
        if (!empty($request['query']['status'])) {
            $query[] = ['status', 'eq', $request['query']['status']];
        }

        // 字段过滤
        $field = '*';
        if (!empty($request['field'])) {
            $field = $request['field'];
        }

        // 排序
        $order = 'id desc';
        if (!empty($request['order'])) {
            $order = $request['order'];
        }

        // 分页参数
        $params = [];
        if (!empty($request['params'])) {
            $params = $request['params'];
        }

        // 每页数量
        $limit = 20;
        if (!empty($request['limit'])) {
            $limit = $request['limit'];
        }

        return $this->where($query)
            ->field($field)
            ->order($order)
            ->paginate($limit, false, [
            'type'     => 'bootstrap',
            'var_page' => 'page',
            'query'    => $params,
        ]);
    }
}