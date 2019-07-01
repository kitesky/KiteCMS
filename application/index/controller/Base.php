<?php
namespace app\index\controller;

use think\facade\Config;
use think\facade\Request;
use app\common\controller\IndexCommon;

class Base extends IndexCommon
{
    /**
     * 重载模板fetch父方法
     * 模板加载规则
     * 1 优先加载用户配置的模板
     * 2 用户未配置模板，则按照系统规则进行加载模板
     * 3 系统规则找不到模板，使用default版本
     */ 
    protected function fetch($coustom_temp = '', $vars = [], $config = [])
    {
        $temp_path = strtolower(DIRECTORY_SEPARATOR . strtolower(Request::controller() . DIRECTORY_SEPARATOR . Request::action()));

        // 模板文件目录
        $view_dir = Config::get('template.view_path');

        // 获取用户配置模板
        $user_path = $view_dir . $this->theme . DIRECTORY_SEPARATOR . $coustom_temp . '.html';

        // 获取系统侦察模板
        $sys_path = $view_dir . $this->theme . $temp_path . '.html';

        // 设置模板替换字符
        $static_dir = $view_dir . $this->theme;

        if (file_exists($user_path)) {
            // 1 用户自定义模板
            $template = $this->theme . DIRECTORY_SEPARATOR . $coustom_temp;
        } else if (file_exists($sys_path)) {
            // 2 系统规则侦察模板
            $template = $this->theme . $temp_path;
        } else {
            // 3 默认 default 模板
            $template = 'default' . $temp_path;
        }

        return parent::fetch($template, $vars, $config);
    }
}