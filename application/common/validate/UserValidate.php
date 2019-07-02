<?php
namespace app\common\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username'   => 'require',
        'ids'        => 'require',
        'password'   => 'require',
        'repassword' => 'require|confirm:password',
        'email'      => 'require',
    ];

    protected $message = [
        'username.require'   => '请输入用户名',
        'ids.require'        => '请勾选归属角色',
        'password.require'   => '请输入密码',
        'repassword.require' => '请再次输入密码',
        'repassword.confirm' => '两次密码输入不一致',
        'email.require'      => '请输入电子邮箱',
    ];
    
    protected $scene = [
        'create'         => ['username', 'ids', 'password', 'repassword', 'email'],
        'edit'           => ['ids', 'email'],
        'updatePassword' => ['password', 'repassword'],
        'login'          => ['username', 'password'],
        'register'       => ['username', 'password'],
        'forget'         => ['phone', 'code', 'password', 'repassword'],
    ];
}