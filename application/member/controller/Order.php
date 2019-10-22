<?php
namespace app\member\controller;

use app\member\controller\Base;
use think\facade\Request;
use think\facade\Config;
use app\common\model\SiteConfig;
use app\common\model\DocumentContent;
use app\common\model\Order as OrderModel;

class Order extends Base
{
    private $alipay_config = [];
    private $wxpay_config = [];
  
    public function __construct() 
    {
        parent::__construct();

        $this->alipay_config = $this->alipayConfig();
        $this->wxpay_config  = $this->wxpayConfig();
    }

    public function detail()
    {
        $order_id = Request::param('order_id');

        $obj = new OrderModel;
        $info = $obj->find($order_id);
        $this->assign('info', $info);
        return $this->fetch('detail');
    }

    public function my()
    {
        $request = Request::param();

        $query = [
            'site_id' => $this->site_id,
            'uid' => $this->uid,
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

        return $this->fetch('my', $data);
    }

    public function alipayConfig()
    {
        $f_config = Config::get('site.payment');
        $db_config = [];
        foreach ($f_config as $k => $v) {
            $db_config[$k] = SiteConfig::getCofig($this->site_id, $k);
        }

        $config = [
          'app_id'      => $db_config['ali_appid'],
          'notify_url'  => $db_config['ali_notify_url'],
          'return_url'  => $db_config['ali_return_url'],
          'public_key'  => $db_config['ali_public_key'],
          'private_key' => $db_config['ali_private_key'],
        ];

        return $config;
    }
    
    public function wxpayConfig()
    {
        $f_config = Config::get('site.payment');
        $db_config = [];
        foreach ($f_config as $k => $v) {
            $db_config[$k] = SiteConfig::getCofig($this->site_id, $k);
        }

        $config = [
          'appid'      => $db_config['wx_appid'],
          'mch_id'     => $db_config['wx_mch_id'],
          'notify_url' => $db_config['wx_notify_url'],
          'key'        => $db_config['wx_key'],
        ];

        return $config;
    }

    public function return()
    {
        $this->success('支付成功', 'member/order/my');
    }

    public function aliNotify()
    {
        $obj = new \app\common\service\Alipay($this->alipay_config);
        $bool = $obj->verify($_POST);
        if (true == $bool) {
            $orderObj = new OrderModel;
            $upData = [
                'trade_no'     => $_POST['trade_no'],
                'payment_type' => 1,
                'status'       => 1,
            ];
            $orderObj->where('out_trade_no', $_POST['out_trade_no'])->update($upData);

            echo 'success';
        }
    }

    public function create()
    {
        $id = Request::param('id');
        $docObj = new DocumentContent;

        $map = [
            'site_id' => $this->site_id,
            'id'      => $id,
        ];
        $document = $docObj->field('title,image,price')->where($map)->find();
        if (!isset($document)) {
            $this->error('当前商品ID不正确');
        }

        $data = [
            'id'       => $id,
            'title'    => $document->title,
            'price'    => $document->price,
        ];

        return $this->fetch('create', $data);
    }

    public function pay()
    {
        $request = Request::param();
        $docObj = new DocumentContent;

        $map = [
            'site_id' => $this->site_id,
            'id'      => $request['id'],
        ]; 
        $document = $docObj->field('title,image,price')->where($map)->find();
        if (!isset($document)) {
            $this->error('当前商品ID不正确');
        }

        $out_trade_no = make_order_no();

        $payment_type = isset($request['payment_type']) ? $request['payment_type'] : 1;

        $orderData = [
            'site_id'      => $this->site_id,
            'out_trade_no' => $out_trade_no,
            'uid'          => $this->uid,
            'document_id'  => $request['id'],
            'total_amount' => $document->price,
        ];

        $orderObj = new OrderModel;
        $orderObj->allowField(true)->save($orderData);

        switch ($payment_type) {
            case 1;
                $order = [
                    'out_trade_no' => $out_trade_no,
                    'total_amount' => $document->price,
                    'subject'      => $document->title,
                ];
                $aliObj = new \app\common\service\Alipay($this->alipay_config);
                return $aliObj->webPay($order);
                break;
            case 2;
                $order = [
                    'out_trade_no' => $out_trade_no,
                    'total_fee'    => $document->price,
                    'body'         => $document->title,
                ];
                $wxObj = new \app\common\service\Wxpay($this->wxpay_config);
                var_dump($wxObj->webPay($order));
                break;
        }
    }
    
    public function test()
    {
              $obj = new \app\common\service\Alipay($this->alipay_config);
              
              $params = json_decode('{"gmt_create":"2019-06-29 18:40:43","charset":"utf-8","gmt_payment":"2019-06-29 18:40:50","notify_time":"2019-06-29 19:05:05","subject":"标签使用","sign":"eHFqXjO9UFJoi5ordGR0YdqWe1lHeaQARlilH7pHNHg0SxER3WG+S6x9MgO3bO46wTtP4Hfvxf4VGYdixlWg1Jz9yoosMvAhKT04s2Ik0nqO7i/xcfzRs6V/sFY9uJYT56pXa+pKXrrGALvpQfjAl8wYs+eUdrkfVVPJgqUgInbDFau8FPVI4U95PCUi6+EXsqtiRXBA+BrkOhXBFDHtL0wrEKs4hzSu3YdgfmCndEx3rITTY4cA70Yz4Ww02jKVbgB2ChueN2/d6RJA7cd3z6El35DpG58bWHVCAgDAeFFDWYzLm7AO3HZhZ8BMRPskSX8ZEIDN8dVDDTVn2LsoGg==","buyer_id":"2088002222030247","invoice_amount":"0.10","version":"1.0","notify_id":"2019062900222184050030240563281129","fund_bill_list":"[{\"amount\":\"0.10\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","out_trade_no":"2019062954985410","total_amount":"0.10","trade_status":"TRADE_SUCCESS","trade_no":"2019062922001430240530988334","auth_app_id":"2016032101230497","receipt_amount":"0.10","point_amount":"0.00","app_id":"2016032101230497","buyer_pay_amount":"0.10","sign_type":"RSA2","seller_id":"2088802807619823"}', true);
              $a = $obj->verify($params);
              var_dump($a);
    }
}
