<?php
namespace app\common\validate;

use think\Validate;

class DocumentContentValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'cid'   => 'require|number',
        
    ];

    protected $message = [
        'title.require' => '请输入文档标题',
        'cid.require'   => '请选择文档归类',
        'cid.number'    => '请选择文档归类',
    ];

    protected $scene = [
        'create' => ['title', 'cid'],
        'edit'   => ['title', 'cid'],
    ];
}