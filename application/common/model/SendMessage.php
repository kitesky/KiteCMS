<?php
namespace app\common\model;

use think\facade\Config;
use app\common\model\SiteConfig;
use app\common\model\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Aliyun\DySDKLite\SignatureHelper;

class SendMessage
{
    // email配置信息
    protected $emailConfig;

    // SMS配置信息
    protected $SMSConfig;
    
    // 网站ID
    protected $site_id;

    // 错误信息
    protected $error = '';

    public function __construct($site_id = '')
    {
        $this->site_id = $site_id;

        $emailConfig = Config::get('site.email');
        $SMSConfig   = Config::get('site.sms');

        // email 配置
        $newEmailConfig = [];
        foreach ($emailConfig as $k => $v) {
            if (!empty(SiteConfig::getCofig($site_id, $k))) {
                $newEmailConfig[$k] = SiteConfig::getCofig($site_id, $k);
            }
        }
        $this->emailConfig = array_merge($emailConfig, $newEmailConfig);

        // SMS 配置
        $newSMSConfig = [];
        foreach ($SMSConfig as $k => $v) {
            if (!empty(SiteConfig::getCofig($site_id, $k))) {
                $newSMSConfig[$k] = SiteConfig::getCofig($site_id, $k);
            }
        }
        $this->SMSConfig = array_merge($SMSConfig, $newSMSConfig);
    }

    /**
     * 获取错误
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 校验手机短信码
     * @param $email 手机号
     * @param $code 短信码
     * @return boolean
     */
    public function checkEmail($email, $code)
    {
        $message = new Message();
        $check = $message->where('email', $email)->where('body', 'like', '%'.$code.'%')->find();

        if (!empty($check)) {
            // 验证码有效时间
            $minute = 5 * 60 * 60; //5分钟
            if ((strtotime($check->create_at) + $minute) > time()) {
                // 验证成功 验证码置为失效
                $check->status = 1;
                $check->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }

    /**
     * 发送email
     * @param $type 1 数字 2 字母  3混合
     * @param $length 长度 限制应为最大6位，因为很多短信服务商 限制到6位 阿里云最大也6位
     * @return array
     */
    public function sendEmail($email, $subject, $body)
    {
        $mail = new PHPMailer();

        try {
            //Server settings
            $mail->SMTPDebug = false;                               // Enable verbose debug output
            $mail->isSMTP();                                        // Set mailer to use SMTP
            $mail->Host = $this->emailConfig['email_host'];         // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                 // Enable SMTP authentication
            $mail->Username = $this->emailConfig['email_username']; // SMTP username
            $mail->Password = $this->emailConfig['email_password']; // SMTP password
            $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->emailConfig['email_port'];         // TCP port to connect to

            //Recipients
            $mail->setFrom($this->emailConfig['email_from_email'], $this->emailConfig['email_from_name']);
            $mail->addAddress($email);                            // Name is optional


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $send = $mail->send();
        } catch (Exception $e) {
            $this->error = $mail->ErrorInfo;
            $send = false;
        }

        // 记录发送信息日志
        $messageLog = [
            'type'        => 3,
            'site_id'     => $this->site_id,
            'subject'     => $subject,
            'body'        => $body,
            'email'       => $email,
            'send_status' => $send ? 'success' : 'fail',
            'send_error'  => $this->error,
            'status'      => 0,
        ];
        $this->record($messageLog);

        return $send;

    }

    /**
     * 校验手机短信码
     * @param $phoneNumber 手机号
     * @param $code 短信码
     * @return boolean
     */
    public function checkSMS($phoneNumber, $code)
    {
        $message = new Message();
        $check = $message->where('phone', $phoneNumber)->where('body', $code)->find();

        if (!empty($check)) {
            // 验证码有效时间
            $minute = 5 * 60 * 60; //5分钟
            if ((strtotime($check->create_at) + $minute) > time()) {
                // 验证成功 验证码置为失效
                $check->status = 1;
                $check->save();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }

    public function sendSMS($phoneNumber)
    {
        $code = $this->makeCode($type = 1, $length = 4);

        switch ($this->SMSConfig['sms_type']) {
            case 'dysms':
                $send = $this->aliyunSms($phoneNumber, $code);
                break;
            default:
                $send = $this->aliyunSms($phoneNumber, $code);
        }

        // 记录发送信息日志
        $messageLog = [
            'type'        => 2,
            'site_id'     => $this->site_id,
            'body'        => $code,
            'phone'       => $phoneNumber,
            'send_status' => $send ? 'success' : 'fail',
            'send_error'  => $this->error,
            'status'      => 0,
        ];
        $this->record($messageLog);


        return $send;
    }

    public function aliyunSms($phoneNumber, $code) {

        $params = array ();

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = $this->SMSConfig['ali_access_key'];
        $accessKeySecret = $this->SMSConfig['ali_access_key_secret'];

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $phoneNumber;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $this->SMSConfig['ali_sign_name'];

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $this->SMSConfig['ali_template_code'];

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => $code,
        );

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        try {
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
                // fixme 选填: 启用https
                // ,true
            );
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }

        // 发送状态
        if ($content->Code == 'ok') {
            return true;
        } else {
            $this->error = $content->Code;
            return false;
        }
    }
    
    /**
     * 生成 邮件/短信 验证码
     * @param $type 1 数字 2 字母  3混合
     * @param $length 长度 限制应为最大6位，因为很多短信服务商 限制到6位 阿里云最大也6位
     * @return array
     */
    public function makeCode($type = 1, $length = 4)
    {
        if (!in_array($type, [1,2,3])) {
            $type = 1;
        }

        switch ($type) {
            case 1:
                $chars = '0123456789';
                break;
            case 2:
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                break;
        }
        // 密码字符集，可任意添加你需要的字符 

        $code = '';
        for ( $i = 0; $i < $length; $i++ ) {
            //$chars[mt_rand(0, strlen($chars) - 1)];
            $code .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        return $code;
    }

    public function record($messageLog) {
        $obj = new Message();
        return $obj->allowField(true)->save($messageLog);
    }
}