<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use think\facade\Env;
use think\View;
use think\Controller;

/**
 * 插件类
 * @author yangweijie <yangweijiester@gmail.com>
 */
abstract class Addon extends Controller{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    public $info                =   array();
    public $addon_path          =   '';
    public $config_file         =   '';
    public $custom_config       =   '';
    public $admin_list          =   array();
    public $custom_adminlist    =   '';
    public $access_url          =   array();

    public function __construct(){
        $name = $this->getName();
        $this->addon_path = Env::get('root_path') . 'addons' . DIRECTORY_SEPARATOR . "{$name}" . DIRECTORY_SEPARATOR;
        if (is_file($this->addon_path . 'config.php')) {
            $this->config_file = $this->addon_path . 'config.php';
        }

    }

    final public function getName(){
        $class = get_class($this);
        return strtolower(substr($class, strrpos($class, '\\') + 1));
    }

    // 检查插件配置
    final public function checkInfo() {
        $info_check_keys = array('name', 'title', 'description', 'status', 'author', 'version');
        foreach ($info_check_keys as $value) {
            if (!array_key_exists($value, $this->info)) {
                return false;
            }
        }
        return true;
    }

    // 获取插件的配置数组
    final public function getConfig($name=''){
        static $_config = array();
        if(empty($name)){
            $name = $this->getName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $map['name']    =   $name;
        $map['status']  =   1;
        $config = \think\Db::name('addons')->where($map)->value('config');
        if($config){
            $config = json_decode($config, true);
        }else{
            if (!empty($this->config_file)) {
              $temp_arr = include $this->config_file;
              foreach ($temp_arr as $key => $value) {
                  if($value['type'] == 'group'){
                      foreach ($value['options'] as $gkey => $gvalue) {
                          foreach ($gvalue['options'] as $ikey => $ivalue) {
                              $config[$ikey] = $ivalue['value'];
                          }
                      }
                  }else{
                      $config[$key] = $temp_arr[$key]['value'];
                  }
              }
            }
        }
        $_config[$name] = $config;
        return $config;
    }

    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();
}
