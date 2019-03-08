<?php
namespace app\common\validate;

use think\Validate;

class SiteValidate extends Validate
{
    protected $rule = [
        'name'          => 'require',
        'alias' => 'require|alpha',
        'domain' => 'require|url',
    ];

    protected $message = [
        'name.require'   => '请输入站点名称',
        'alias.require'  => '请输入站点别名',
        'alias.alpha'    => '站点别名必须为字母',
        'domain.require' => '站点域名必须填写',
        'domain.url'     => '站点域名格式不正确',
    ];

    protected $scene = [
        'create' => ['name', 'alias', 'domain'],
    ];
}