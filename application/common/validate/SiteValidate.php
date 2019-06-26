<?php
namespace app\common\validate;

use think\Validate;

class SiteValidate extends Validate
{
    protected $rule = [
        'name'          => 'require',
        'domain' => 'require|url',
    ];

    protected $message = [
        'name.require'   => '请输入站点名称',
        'domain.require' => '站点域名必须填写',
        'domain.url'     => '站点域名格式不正确',
    ];

    protected $scene = [
        'create' => ['name', 'domain'],
    ];
}