<?php
namespace app\common\validate;

use think\Validate;

class BlockValidate extends Validate
{
    protected $rule = [
        'name'     => 'require',
        'variable' => 'require|alphaDash',
        'cid'      => 'require|number',
    ];

    protected $message = [
        'name.require'       => '请输入区块名称',
        'variable.require'   => '请输入区块变量标识',
        'variable.alphaDash' => '区块变量标识必须是字母数字下划线',
        'cid.require'        => '请选择分类',
        'cid.number'         => '请选择分类',
    ];

    protected $scene = [
        'create'         => ['name', 'variable', 'cid'],
        'edit'           => ['name', 'variable', 'cid'],
    ];
}