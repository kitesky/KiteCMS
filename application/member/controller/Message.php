<?php
namespace app\member\controller;

use think\facade\Request;
use app\common\model\SiteConfig;
use app\member\controller\Base;
use app\common\model\SendMessage;
use app\common\model\AuthUser;

class Message extends Base
{
    public function sendSMS()
    {
        $phoneNumber = Request::param('phone');

        if (empty($phoneNumber)) {
            return $this->response(201, '请输入手机号码');
        }

        $obj = new SendMessage($this->site_id);
        $send = $obj->sendSMS($phoneNumber);
        if (true == $send) {
            return $this->response(200, '发送成功');
        } else {
            return $this->response(201, '发送失败');
        }
    }

    public function sendEmail()
    {
        $email = Request::param('email');

        $member = new AuthUser;
        $user = $member->where('uid', $this->uid)->field('username,email')->find();
        if (empty($email)) {
            $email = $user->email;
        }

        if (empty($email)) {
            return $this->response(201, '请输入邮箱地址');
        }

        $obj = new SendMessage($this->site_id);

        // 替换
        $template = SiteConfig::getCofig($this->site_id, 'email_code_template');
        $template = str_ireplace('${username}', $user->username, $template);
        $template = str_ireplace('${code}', $obj->makeCode(), $template);

        $send = $obj->sendEmail($email, $template, $template);
        if (true == $send) {
            return $this->response(200, '发送成功');
        } else {
            return $this->response(201, '发送失败');
        }
    }
}
