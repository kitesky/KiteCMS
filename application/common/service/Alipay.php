<?php
namespace app\common\service;

class Alipay
{
    protected $gateway = 'https://openapi.alipay.com/gateway.do';
    
    protected $postCharset = 'utf-8';
    
    protected $config = [];
    
    protected $rsaPrivateKeyFilePath = '';
    
    protected $rsaPublicKeyFilePath = '';
    
    protected $rsaPrivateKey = '';
    
    protected $alipayrsaPublicKey = '';
    
    protected $productCode = 'FAST_INSTANT_TRADE_PAY';

    public function __construct($config = [])
    {
          // 公共参数
          $this->config = [
              'app_id'         => $config['app_id'],
              'method'         => '',
              'format'         => 'JSON',
              'charset'        => 'utf-8',
              'sign_type'      => 'RSA2',
              'sign'           => '',
              'timestamp'      => date('Y-m-d H:i:s'),
              'version'        => '1.0',
              'notify_url'     => $config['notify_url'],
              'return_url'     => $config['return_url'],
              'biz_content'    => '',
          ];

          $this->rsaPrivateKey = $config['private_key'];
          $this->alipayrsaPublicKey  = $config['public_key'];
    }

    public function webPay($order)
    {
        $order['product_code'] = $this->productCode;
        $this->config['biz_content'] = json_encode($order);
        $this->config['method'] = 'alipay.trade.page.pay';
        $this->config['sign'] = $this->rsaSign($this->config, 'RSA2');

        return $this->buildRequestForm($this->config);
    }

    public function verify($params) {

      $sign = $params['sign'];

      $params['sign'] = null;
      $params['sign_type'] = null;

      return $this->rsaCheckV2($this->getSignContent($params), $sign, $this->rsaPublicKeyFilePath, 'RSA2');
    }

    function rsaCheckV2($data, $sign, $rsaPublicKeyFilePath, $signType) {

      if($this->checkEmpty($this->rsaPublicKeyFilePath)){

        $pubKey= $this->alipayrsaPublicKey;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
          wordwrap($pubKey, 64, "\n", true) .
          "\n-----END PUBLIC KEY-----";
      }else {
        //读取公钥文件
        $pubKey = file_get_contents($rsaPublicKeyFilePath);
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
      }

      ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');  

      //调用openssl内置方法验签，返回bool值
      $result = FALSE;
      if ("RSA2" == $signType) {
        $result = (openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256)===1);
      } else {
        $result = (openssl_verify($data, base64_decode($sign), $res)===1);
      }

      if($this->checkEmpty($this->alipayrsaPublicKey)) {
        //释放资源
        openssl_free_key($res);
      }

      return $result;
    }

    /**
       * 建立请求，以表单HTML形式构造（默认）
       * @param $para_temp 请求参数数组
       * @return 提交表单HTML文本
       */
    protected function buildRequestForm($para_temp)
    {
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."?charset=".trim($this->postCharset)."' method='POST'>";
        foreach ($para_temp as $key => $val) {
            if (false === $this->checkEmpty($val)) {
              //$val = $this->characet($val, $this->postCharset);
              $val = str_replace("'","&apos;",$val);
              //$val = str_replace("\"","&quot;",$val);
              $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
        }

        //submit按钮控件请不要含有name属性
          $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";

          $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

          return $sHtml;
    }

    public function rsaSign($params, $signType = "RSA2") {
        return $this->sign($this->getSignContent($params), $signType);
    }
  
    protected function sign($data, $signType)
    {
        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
          $priKey=$this->rsaPrivateKey;
          $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        }else {
          $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
          $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

        if ("RSA2" == $signType) {
          openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
          openssl_sign($data, $sign, $res);
        }

        if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
          openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
    
    
    public function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
          if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

            if ($i == 0) {
              $stringToBeSigned .= "$k" . "=" . "$v";
            } else {
              $stringToBeSigned .= "&" . "$k" . "=" . "$v";
            }
            $i++;
          }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    //此方法对value做urlencode
    public function getSignContentUrlencode($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
          if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

            if ($i == 0) {
              $stringToBeSigned .= "$k" . "=" . urlencode($v);
            } else {
              $stringToBeSigned .= "&" . "$k" . "=" . urlencode($v);
            }
            $i++;
          }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
      if (!isset($value))
        return true;
      if ($value === null)
        return true;
      if (trim($value) === "")
        return true;

      return false;
    }

}