<?php
namespace app\member\controller;

use app\member\controller\Base;
use app\common\model\SiteConfig;
use app\common\model\SiteCaptcha;
use app\common\model\SendMessage;
use think\facade\Request;
use think\facade\Session;
use think\facade\Hook;
use app\common\model\AuthUser;
use app\common\validate\UserValidate;
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
        $memberObj = new AuthUser;
        $member = $memberObj->where('uid', $this->uid)->find();
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
            $validate = new UserValidate();
            $validateResult = $validate->scene('updatePassword')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new AuthUser;
            $userInfo = $obj->where('uid', $this->uid)->find();

            // 验证密码 
            if (!password_verify($request['oldpassword'], $userInfo->password)) {
                return $this->response(201, '旧密码不正确');
            }

            $userInfo->password = password_hash($request['password'], PASSWORD_DEFAULT);
            $userInfo->save();
            if (is_numeric($userInfo->uid)) {
                return $this->response(200, '密码变更成功');
            } else {
                return $this->response(201, '密码变更失败');
            }
        }
        
        $info = AuthUser::get($this->uid);

        return $this->fetch('member/password', ['info' =>$info]);
    }

    // 账户绑定页
    public function bind()
    {
        $info = AuthUser::get($this->uid);

        return $this->fetch('member/bind', ['info' =>$info]);
    }

    // 手机绑定
    public function mobileBind()
    {
        if (Request::isPost()) {
            $request = Request::param();

            // 验证数据
            $validate = new UserValidate();
            $validateResult = $validate->scene('mobileBind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new AuthUser;
            $userInfo = $obj->where('uid', $this->uid)->find();

            // 验证是否已经绑定过
            if (!empty($userInfo->phone)) {
                return $this->response(201, '请先解绑原有手机号码');
            }

            $userInfo->phone = $request['phone'];
            $userInfo->save();
            if (is_numeric($userInfo->uid)) {
                return $this->response(200, '手机号码绑定成功');
            } else {
                return $this->response(201, '手机号码绑定失败');
            }
        }

        $info = AuthUser::get($this->uid);
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
            $validate = new UserValidate();
            $validateResult = $validate->scene('mobileUnbind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new AuthUser;
            $userInfo = $obj->where('uid', $this->uid)->find();

            // 验证短信
            $message = new SendMessage($this->site_id);
            $check = $message->checkSMS($userInfo->phone, $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->phone = '';
            $userInfo->save();
            if (is_numeric($userInfo->uid)) {
                return $this->response(200, '号码已解绑');
            } else {
                return $this->response(201, '号码解绑失败');
            }
        }

        $info = AuthUser::get($this->uid);
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
            $validate = new UserValidate();
            $validateResult = $validate->scene('emailBind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new AuthUser;
            $userInfo = $obj->where('uid', $this->uid)->find();

            // 验证动态码
            $message = new SendMessage($this->site_id);
            $check = $message->checkEmail($request['email'], $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->email = $request['email'];
            $userInfo->save();
            if (is_numeric($userInfo->uid)) {
                return $this->response(200, '邮箱绑定成功');
            } else {
                return $this->response(201, '邮箱绑定失败');
            }
        }
        
        $info = AuthUser::get($this->uid);
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
            $validate = new UserValidate();
            $validateResult = $validate->scene('emailUnbind')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 并查询用户信息
            $obj = new AuthUser;
            $userInfo = $obj->where('uid', $this->uid)->find();

            // 验证动态码
            $message = new SendMessage($this->site_id);
            $check = $message->checkEmail($userInfo->email, $request['code']);
            if ($check != true) {
                return $this->response(201, '动态码错误');
            }

            // 解绑
            $userInfo->email = '';
            $userInfo->save();
            if (is_numeric($userInfo->uid)) {
                return $this->response(200, '邮箱已解绑');
            } else {
                return $this->response(201, '邮箱解绑失败');
            }
        }

        $info = AuthUser::get($this->uid);
        if (empty($info->email)) {
            $this->redirect('member/member/emailBind');
        }

        return $this->fetch('member/email_unbind', ['info' =>$info]);
    }

    // 个人资料
    public function profile()
    {
        $info = AuthUser::get($this->uid);

        return $this->fetch('member/profile', ['info' =>$info]);
    }
}
