<?php
namespace app\common\behavior;

use think\Controller;
use think\facade\Hook;
use think\facade\Env;

class InitHook extends Controller
{
    //初始化钩子信息
    public function run($params)
    {
        // 验证是否安装系统
        $lock = Env::get('app_path') . 'install' . DIRECTORY_SEPARATOR . 'install.lock';
        if (!file_exists($lock)) {
            return false;
        }

        // 用户登录日志
        Hook::add('user_login_log',['app\\common\\behavior\\User']);

        // 用户注册
        Hook::add('user_register',['app\\common\\behavior\\Score']);

        // 会员登录
        Hook::add('user_login',['app\\common\\behavior\\Score']);

        // 会员发布评论
        Hook::add('user_comments',['app\\common\\behavior\\Score']);

        // 文档创建
        Hook::add('create_document',['app\\common\\behavior\\Document','app\\common\\behavior\\Score']);

        // 文档更新
        Hook::add('edit_document',['app\\common\\behavior\\Document']);

        $this->setHook();
    }
    
    public function setHook()
    {
        \think\Loader::addNamespace('addons', Env::get('root_path') . '/addons/');
        $hooks = \think\Db::name('hooks')->column('name,addons');
        foreach ($hooks as $key => $value) {
            if($value){
                $names = explode(',',$value);
                $data = \think\Db::name('addons')
                    ->where('status', 1)
                    ->where('name', 'in', $names)
                    ->column('id,name');
                if($data){
                    $addons = array_intersect($names, $data);
                    Hook::add($key,array_map('get_addon_class',$addons));
                }
            }
        }
    }
}