<?php
namespace app\common\validate;

use think\Validate;

class HooksValidate extends Validate
{
    protected $rule = [
        'name'        => 'require|alphaDash',
        'description' => 'require',
        'type'        => 'require',
    ];

    protected $message = [
        'name.require'        => '请输入钩子名称',
        'name.alphaDash'      => '钩子名称必须是字母数字下划线',
        'description.require' => '请输入钩子描述信息',
        'type.require'        => '请选择钩子类型',
    ];

    protected $scene = [
        'create'         => ['name', 'description', 'type'],
        'edit'           => ['name', 'description', 'type'],
    ];
}