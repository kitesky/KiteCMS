<?php
namespace app\common\validate;

use think\Validate;

class MemberGroupValidate extends Validate
{
    protected $rule = [
        'group_name' => 'require',
        'min_score'  => 'require|integer',
        'max_score'  => 'require|integer|>:min_score',
    ];

    protected $message = [
        'group_name.require' => '请输入会员组名称',
        'min_score.require'  => '请输入最低积分数值',
        'max_score.require'  => '请输入最高积分数值',
        'min_score.integer'  => '最低积分数值必须为整数',
        'max_score.integer'  => '最高积分数值必须为整数',
        'max_score.gt'       => '最高积分数必须大于最低积分数',
    ];

    protected $scene = [
        'create' => ['group_name', 'min_score', 'max_score'],
    ];
}