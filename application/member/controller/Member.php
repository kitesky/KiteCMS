<?php
namespace app\member\controller;

use app\member\controller\Base;
use app\common\model\SiteConfig;
use app\common\model\SiteCaptcha;
use app\common\model\SendMessage;
use think\facade\Request;
use think\facade\Session;
use think\facade\Hook;
use app\common\model\Member as MemberModel;
use app\common\model\MemberGroup;
use app\common\validate\MemberValidate;
use app\common\model\UploadFile;

class Member extends Base
{
    public function index()
    {
        return $this->fetch('member/index');
    }

    // 头像设置
    public function avatar()
    {
        // 获取表单上传文件
        $file = Request::file('file');

        $uploadObj = new UploadFile($this->site_id);
        $ret = $uploadObj->upload($file);

        // 修改图片路径
        $memberObj = new MemberModel;
        $member = $memberObj->where('mid', $this->mid)->find();
        $member->avatar = $ret['url'];
        $member->save();

        if ($ret) {
            return $this->response(200, '上传成功', $ret);
        } else {
            return $this->response(201, $uploadObj->getError());
        }
    }

    // 密码设置
    public function password()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('updatePassword')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('mid', $this->mid)->find();

            // 验证密码 
            if (!password_verify($request['oldpassword'], $userInfo->password)) {
                return $this->response(201, '旧密码不正确');
            }

            $userInfo->password = password_hash($request['password'], PASSWORD_DEFAULT);
            $userInfo->save();
            if (is_numeric($userInfo->mid)) {
                return $this->response(200, '密码变更成功');
            } else {
                return $this->response(201, '密码变更失败');
            }
        }
        
        $info = MemberModel::get($this->mid);

        return $this->fetch('member/password', ['info' =>$info]);
    }

    // 账户绑定页
    public function bind()
    {
        $info = MemberModel::get($this->mid);

        return $this->fetch('member/bind', ['info' =>$info]);
    }

    // 手机绑定
    public function mobileBind()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('mobileBind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('mid', $this->mid)->find();

            // 验证是否已经绑定过
            if (!empty($userInfo->phone)) {
                return $this->response(201, '请先解绑原有手机号码');
            }

            $userInfo->phone = $request['phone'];
            $userInfo->save();
            if (is_numeric($userInfo->mid)) {
                return $this->response(200, '手机号码绑定成功');
            } else {
                return $this->response(201, '手机号码绑定失败');
            }
        }

        $info = MemberModel::get($this->mid);
        if (!empty($info->phone)) {
            $this->redirect('member/member/mobileUnbind');
        }

        return $this->fetch('member/mobile_bind', ['info' =>$info]);
    }

    // 手机解绑
    public function mobileUnbind()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('mobileUnbind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('mid', $this->mid)->find();

            // 验证短信
            $message = new SendMessage($this->site_id);
            $check = $message->checkSMS($userInfo->phone, $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->phone = '';
            $userInfo->save();
            if (is_numeric($userInfo->mid)) {
                return $this->response(200, '号码已解绑');
            } else {
                return $this->response(201, '号码解绑失败');
            }
        }

        $info = MemberModel::get($this->mid);
        if (empty($info->phone)) {
            $this->redirect('member/member/mobileBind');
        }

        return $this->fetch('member/mobail_unbind', ['info' =>$info]);
    }

    // 邮箱绑定
    public function emailBind()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('emailBind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('mid', $this->mid)->find();

            // 验证动态码
            $message = new SendMessage($this->site_id);
            $check = $message->checkEmail($request['email'], $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->email = $request['email'];
            $userInfo->save();
            if (is_numeric($userInfo->mid)) {
                return $this->response(200, '邮箱绑定成功');
            } else {
                return $this->response(201, '邮箱绑定失败');
            }
        }
        
        $info = MemberModel::get($this->mid);
        if (!empty($info->email)) {
            $this->redirect('member/member/emailUnbind');
        }

        return $this->fetch('member/email_bind', ['info' =>$info]);
    }

    // 邮箱解绑
    public function emailUnbind()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('emailUnbind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('mid', $this->mid)->find();

            // 验证动态码
            $message = new SendMessage($this->site_id);
            $check = $message->checkEmail($userInfo->email, $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->email = '';
            $userInfo->save();
            if (is_numeric($userInfo->mid)) {
                return $this->response(200, '邮箱已解绑');
            } else {
                return $this->response(201, '邮箱解绑失败');
            }
        }

        $info = MemberModel::get($this->mid);
        if (empty($info->email)) {
            $this->redirect('member/member/emailBind');
        }

        return $this->fetch('member/email_unbind', ['info' =>$info]);
    }

    // 个人资料
    public function profile()
    {
        $info = MemberModel::get($this->mid);

        return $this->fetch('member/profile', ['info' =>$info]);
    }

    // 登录
    public function login()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('login')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }
            
            // 登录验证码
            $is_captcha = SiteConfig::getCofig($this->site_id, 'captcha_member_login');
            if ($is_captcha == true) {
                if (empty($request['captcha'])) {
                    return $this->response(201, '验证码不能空');
                }

                $captchaObj = new SiteCaptcha;
                $checkCaptcha = $captchaObj->check($request['captcha']);
                if ($checkCaptcha == false) {
                    return $this->response(201, '验证码错误');
                }
            }

            // 并查询用户信息
            $obj = new MemberModel;
            $userInfo = $obj->where('username', $request['username'])->find();

            // 用户不存在
            if (!$userInfo) {
                return $this->response(201, '用户不存在');
            }

            // 用户被冻结
            if ($userInfo->status == 1) {
                return $this->response(201, '用户被冻结');
            }

            // 验证密码 
            if (!password_verify($request['password'], $userInfo->password)) {
                return $this->response(201, '密码不正确');
            }

            // 生成session
            Session::set('mid',$userInfo->mid,'index');
            Session::set('group_id',$userInfo->group_id,'index');
            Session::set('username',$userInfo->username,'index');

            // 监听会员登录
            $logData = [
                'mid'     => $userInfo->mid,
                'site_id' => $this->site_id,
                'ip'      => get_client_ip(),
            ];
            Hook::listen('member_login', $logData);

            return $this->response(200, '登录成功');
        }

        if (Session::get('mid', 'index')) {
            $this->redirect('member/index/index');
        }

        $is_captcha = SiteConfig::getCofig($this->site_id, 'captcha_member_login');

        return $this->fetch('member/login', ['captcha' => $is_captcha]);
    }

    // 注册
    public function register()
    {
        // 处理AJAX提交数据
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('register')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 登录验证码
            $is_captcha = SiteConfig::getCofig($this->site_id, 'captcha_member_register');
            if ($is_captcha == true) {
                if (empty($request['captcha'])) {
                    return $this->response(201, '验证码不能空');
                }

                $captchaObj = new SiteCaptcha;
                $checkCaptcha = $captchaObj->check($request['captcha']);
                if ($checkCaptcha == false) {
                    return $this->response(201, '验证码错误');
                }
            }

            // 校验记录
            $memberObj = new MemberModel;
            $exist = $memberObj->where('username', $request['username'])->value('mid');
            if (is_numeric($exist)) {
                return $this->response(201, '账户名称已经存在');
            }

            // 默认会员组
            $group_id = SiteConfig::getCofig($this->site_id, 'member_default_group');

            // 写入会员信息
            $memberData = [
                'site_id'  => $this->site_id,
                'group_id' => isset($group_id) ? $group_id : 0,
                'password' =>  password_hash($request['password'], PASSWORD_DEFAULT),
            ];
            $memberData = array_merge($request, $memberData);
            $memberObj->allowField(true)->save($memberData);

            if (is_numeric($memberObj->mid)) {
                // 监听会员注册
                $params = [
                    'mid'           => $memberObj->mid,
                    'username'      => $memberObj->mid,
                    'register_time' => time(),
                    'site_id'       => $this->site_id,
                    'register_ip'   => get_client_ip(),
                ];
                Hook::listen('member_register', $params);

                return $this->response(200, '注册成功');
            } else {
                return $this->response(201, '注册失败');
            }
        }

        $is_captcha = SiteConfig::getCofig($this->site_id, 'captcha_member_register');

        return $this->fetch('member/register', ['captcha' => $is_captcha]);
    }

    // 注销登录
    public function logout()
    {
        Session::delete('mid','index');
        Session::delete('username','index');
        Session::delete('group_id','index');
        return $this->redirect('member/member/login');
    }

    // 忘记密码找回
    public function forget()
    {
        echo 11;
    }
}
