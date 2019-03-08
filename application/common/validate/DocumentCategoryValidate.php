<?php
namespace app\common\validate;

use think\Validate;

class DocumentCategoryValidate extends Validate
{
    protected $rule = [
        'title'    => 'require',
        'alias'    => 'require|alpha',
        'pid'      => 'require',
        'model_id' => 'require',
    ];

    protected $message = [
        'title.require'    => '请输入栏目名称',
        'alias.require'    => '请输入栏目别名',
        'alias.alpha'      => '栏目别名只能是字母',
        'pid.require'      => '请选择栏目归属分类',
        'model_id.require' => '请选择栏目内容模型',
    ];

    protected $scene = [
        'create' => ['title', 'pid', 'model_id', 'alias'],
    ];
}