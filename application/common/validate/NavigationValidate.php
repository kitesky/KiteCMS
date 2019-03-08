<?php
namespace app\common\validate;

use think\Validate;

class NavigationValidate extends Validate
{
    protected $rule = [
        'ids'       => 'require',
        'cid'       => 'require',
        'url'       => 'require|url',
        'name'      => 'require',
    ];

    protected $message = [
        'ids.require'  => '请选择栏目分类',
        'cid.require'  => '请选择菜单',
        'url.require'  => '请输入URL',
        'url.url'      => 'URL格式不正确',
        'name.require' => '请输入名称',
    ];

    protected $scene = [
        'category'         => ['ids', 'cid'],
        'custom'           => ['cid', 'url', 'name' ],
    ];
}