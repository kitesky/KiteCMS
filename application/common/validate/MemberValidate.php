<?php
namespace app\common\validate;

use think\Validate;

class MemberValidate extends Validate
{
    protected $rule = [
        'username'   => 'require',
        'group_id'   => 'require',
        'oldpassword'   => 'require',
        'password'   => 'require',
        'repassword' => 'require|confirm:password',
        'email'      => 'require',
        'phone'      => 'require',
        'code'       => 'require',
    ];

    protected $message = [
        'username.require'    => '请输入会员账号',
        'ids.require'         => '请选择会员组',
        'oldpassword.require' => '请输入旧密码',
        'password.require'   => '请输入密码',
        'repassword.require' => '请再次输入密码',
        'repassword.confirm' => '两次密码输入不一致',
        'email.require'      => '请输入电子邮箱',
        'phone.require'      => '请输入手机号码',
        'code.require'       => '请输入动态码',
    ];
    
    protected $scene = [
        'create'         => ['username', 'group_id', 'password', 'repassword', 'email'],
        'edit'           => ['group_id', 'email'],
        'updatePassword' => ['oldpassword', 'password', 'repassword'],
        'login'          => ['username', 'password'],
        'register'       => ['username', 'password', 'repassword', 'email'],
        'mobileBind'     => ['phone', 'code'],
        'mobileUnbind'   => ['code'],
        'emailBind'      => ['email', 'code'],
        'emailUnbind'    => ['code'],
    ];
}