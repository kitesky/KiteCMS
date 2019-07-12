<?php
namespace app\common\service;

class Wxpay
{
    protected $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder'; //346702a331d886fde5968bc513981ac6
  
    protected $key;

    protected $config;

    /*没有申请微信支付账户，未完成，待开发*/
    public function __construct($config = [])
    {
          // 公共参数
          $this->config = [
              'appid'            => $config['appid'],
              'mch_id'           => $config['mch_id'],
              'nonce_str'        => $this->getNonceStr(),
              'sign_type'        => 'MD5',
              'body'             => '',
              'out_trade_no'     => '',
              'total_fee'        => '',
              'spbill_create_ip' => $this->getIP(),
              'notify_url'       => $config['notify_url'],
              'trade_type'       => 'JSAPI',
              'sign'             => '',
          ];

          $this->key = $config['key'];
    }

    public function webPay($order)
    {
        $this->config['body'] = $order['body'];
        $this->config['out_trade_no'] = $order['out_trade_no'];
        $this->config['total_fee'] = $order['total_fee'];
        $this->config['sign'] = $this->MakeSign($this->config);

        $xml = $this->ToXml($this->config);
        $result = $this->postXmlCurl($xml, $this->url);
        return $this->FromXml($result);
    }


    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();

        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验

        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }

    /**
     * 输出xml字符
     * @throws WxPayException
    **/
    public function ToXml($params)
    {
        if(!is_array($params) || count($params) <= 0)
        {
            return false;
        }

        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
          if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
          }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
          }
          }
          $xml.="</xml>";
          return $xml; 
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /**
     * 生成签名
     */
    public function MakeSign($config)
    {
      ksort($config);
      $string = $this->ToUrlParams($config);
      $string = $string . "&key=".$this->key;
      $string = md5($string);
      $result = strtoupper($string);
      return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($params)
    {
      $buff = "";
      foreach ($params as $k => $v)
      {
        if($k != "sign" && $v != "" && !is_array($v)){
          $buff .= $k . "=" . $v . "&";
        }
      }
      
      $buff = trim($buff, "&");
      return $buff;
    }

    /**
     * 
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32) 
    {
      $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
      $str ="";
      for ( $i = 0; $i < $length; $i++ )  {  
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
      } 
      return $str;
    }

    /**
     * 请求者IP
     */
    public function getIP(){
        static $realip;
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

}