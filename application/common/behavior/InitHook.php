<?php
namespace app\common\behavior;

use think\Controller;
use think\facade\Hook;

class InitHook extends Controller
{
    //初始化钩子信息
    public function run($params)
    {
        // 管理员登录
        Hook::add('user_login_log',['app\\common\\behavior\\User']);

        // 会员注册
        Hook::add('member_register',['app\\common\\behavior\\Score']);

        // 会员登录
        Hook::add('member_login',['app\\common\\behavior\\Member','app\\common\\behavior\\Score']);

        // 会员文档创建
        Hook::add('member_create_document',['app\\common\\behavior\\Score']);

        // 会员文档更新
        Hook::add('member_edit_document',['app\\common\\behavior\\Score']);

        // 会员发布评论
        Hook::add('member_comments',['app\\common\\behavior\\Score']);

        // Hook::add('action_begin',['app\\common\\behavior\\Member'])

        // 文档创建
        Hook::add('create_document',['app\\common\\behavior\\Document']);

        // 文档更新
        Hook::add('edit_document',['app\\common\\behavior\\Document']);
    }
}