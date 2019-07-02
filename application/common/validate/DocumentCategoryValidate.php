<?php
namespace app\common\validate;

use think\Validate;

class DocumentCategoryValidate extends Validate
{
    protected $rule = [
        'title'    => 'require',
        'pid'      => 'require',
    ];

    protected $message = [
        'title.require'    => '请输入栏目名称',
        'pid.require'      => '请选择栏目归属分类',
    ];

    protected $scene = [
        'create' => ['title', 'pid'],
    ];
}