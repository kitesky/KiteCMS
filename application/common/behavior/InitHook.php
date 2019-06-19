<?php
namespace app\common\behavior;

use think\Controller;
use think\facade\Hook;

class InitHook extends Controller
{
    //初始化钩子信息
    public function run($params)
    {
        // 用户登录日志
        Hook::add('user_login_log',['app\\common\\behavior\\User']);

        // 用户注册
        Hook::add('user_register',['app\\common\\behavior\\Score']);

        // 会员登录
        Hook::add('user_login',['app\\common\\behavior\\Score']);

        // 会员文档创建
        Hook::add('user_create_document',['app\\common\\behavior\\Score']);

        // 会员文档更新
        Hook::add('user_edit_document',['app\\common\\behavior\\Score']);

        // 会员发布评论
        Hook::add('user_comments',['app\\common\\behavior\\Score']);


        // 文档创建
        Hook::add('create_document',['app\\common\\behavior\\Document']);

        // 文档更新
        Hook::add('edit_document',['app\\common\\behavior\\Document']);
    }
}