<?php
namespace app\common\validate;

use think\Validate;

class DocumentCategoryValidate extends Validate
{
    protected $rule = [
        'title'    => 'require',
        'pid'      => 'require',
        'model_id' => 'require',
    ];

    protected $message = [
        'title.require'    => '请输入栏目名称',
        'pid.require'      => '请选择栏目归属分类',
        'model_id.require' => '请选择栏目内容模型',
    ];

    protected $scene = [
        'create' => ['title', 'pid', 'model_id'],
    ];
}