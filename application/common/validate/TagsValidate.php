<?php
namespace app\common\validate;

use think\Validate;

class TagsValidate extends Validate
{
    protected $rule = [
        'tag_name'     => 'require',
    ];

    protected $message = [
        'tag_name.require'       => '请输入Tag名称',
    ];

    protected $scene = [
        'create'         => ['tag_name'],
        'edit'           => ['tag_name'],
    ];
}