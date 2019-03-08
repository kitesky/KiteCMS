<?php
namespace app\common\validate;

use think\Validate;

class DocumentFieldValidate extends Validate
{
    protected $rule = [
        'name'     => 'require',
        'variable' => 'require|alpha',
        'type'     => 'require',
        'cid'      => 'require|integer',
    ];

    protected $message = [
        'name.require'     => '请输入字段名称',
        'variable.require' => '请输入字段变量名称',
        'variable.alpha'   => '字段变量只能是字母',
        'type.require'     => '请选择字段类型',
        'cid.require'      => '请选择字段归类',
        'cid.integer'      => '请选择字段归类',
    ];

    protected $scene = [
        'create' => ['name', 'variable', 'type', 'cid'],
    ];
}