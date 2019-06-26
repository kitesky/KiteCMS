<?php
namespace app\common\validate;

use think\Validate;

class RuleValidate extends Validate
{
    protected $rule = [
        'name'          => 'require',
        'url'           => 'require',
        'lang_var'      => 'require',
    ];

    protected $message = [
        'name.require'          => '请输入权限名称',
        'url.require'           => '请输入URL',
        'lang_var.require'      => '请输入语言标识',
    ];
    
    protected $scene = [
        'create' => ['name', 'url', 'lang_var'],
    ];
}