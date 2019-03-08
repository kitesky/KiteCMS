<?php
namespace app\common\validate;

use think\Validate;

class SliderValidate extends Validate
{
    protected $rule = [
        'name'   => 'require',
        'image'  => 'require',
        'url'    => 'require|url',
        'cid'    => 'require|number',
    ];

    protected $message = [
        'name.require'  => '请输入幻灯片标题',
        'image.require' => '请上传图片',
        'url.require'   => '请输入链接地址',
        'url.url'       => '网址不正确',
        'cid.require'   => '请选择分类',
        'cid.number'    => '请选择分类',
    ];

    protected $scene = [
        'create'         => ['name', 'url', 'cid', 'image'],
        'edit'           => ['name', 'url', 'cid', 'image'],
    ];
}