<?php
namespace app\common\validate;

use think\Validate;

class RoleValidate extends Validate
{
    protected $rule = [
        'role_name'     => 'require',
        'lang_var' => 'require',
    ];

    protected $message = [
        'role_name.require' => '请输入角色名称',
        'lang_var.require'  => '请输入语言标识',
    ];

    protected $scene = [
        'create' => ['role_name', 'lang_var'],
    ];
}