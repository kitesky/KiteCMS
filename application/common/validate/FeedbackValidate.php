<?php
namespace app\common\validate;

use think\Validate;

class FeedbackValidate extends Validate
{
    protected $rule = [
        'content'        => 'require',
    ];

    protected $message = [
        'content.require'        => '请填写反馈内容',
    ];

    protected $scene = [
        'create'         => ['content'],
    ];
}