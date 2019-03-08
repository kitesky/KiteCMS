<?php
namespace app\common\validate;

use think\Validate;

class DocumentModelValidate extends Validate
{
    protected $rule = [
        'name'          => 'require',
    ];

    protected $message = [
        'name.require'           => '请输入模型名称',
    ];

    protected $scene = [
        'create' => ['name'],
    ];
}