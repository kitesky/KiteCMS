<?php
namespace app\common\validate;

use think\Validate;

class DistrictValidate extends Validate
{
    protected $rule = [
        'pid'     => 'require',
        'name'    => 'require',
        'initial' => 'require|alpha',
    ];

    protected $message = [
        'pid.require'     => '请选择地区归属上级',
        'name.require'    => '请输入地区名称',
        'initial.require' => '请输入首字母',
        'initial.alpha'   => '首字母只能是字母',
    ];

    protected $scene = [
        'create' => ['name', 'pid'],
    ];
}