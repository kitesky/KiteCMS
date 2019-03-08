<?php
namespace app\common\validate;

use think\Validate;

class LinkValidate extends Validate
{
    protected $rule = [
        'name'   => 'require',
        'url'    => 'require|url',
        'cid'    => 'require|number',
    ];

    protected $message = [
        'name.require' => '请输入网站名称',
        'url.require'  => '请输入网站地址',
        'url.url'      => '网址不正确',
        'cid.require'  => '请选择分类',
        'cid.number'   => '请选择分类',
    ];

    protected $scene = [
        'create'         => ['name', 'url', 'cid'],
        'edit'           => ['name', 'url', 'cid'],
    ];
}