<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use app\admin\controller\Admin;
use app\common\model\Hooks;
use app\common\model\Addons as AddonsModel;

class Addons extends Admin
{
    public function index()
    {
        // 分页列表
        $obj = new AddonsModel;
        $list =$obj->getList();
        $data = [
            'list' => $list,
        ];

        return $this->fetch('index', $data);
    }

    public function execute()
    {
        $obj = new \addons\attachment\controller\Admin;
        var_dump($obj->Index());
        return 11;
    }

    /**
     * 设置插件页面
     */
    public function config(){
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            $ret = AddonsModel::where('id', $request['id'])->setField('config', json_encode($request));
            if (is_numeric($ret)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $addon = AddonsModel::get($id);
        $addon = $addon->toArray();
        if(!$addon) {
            $this->error('插件未安装');
        }
        $addon_class = get_addon_class($addon['name']);
        if(!class_exists($addon_class)) {
            $this->error('插件无法实例化');
        }

        $data = new $addon_class;
        $addon['addon_path'] = $data->addon_path;
        $addon['custom_config'] = $data->custom_config;
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;

        if($db_config){
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if($value['type'] != 'group'){
                    $addon['config'][$key]['value'] = $db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }

        $this->assign('data', $addon);
        if($addon['custom_config']) {
            $this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
        }
        return $this->fetch('config');
    }

    /**
     * 启用插件
     */
    public function enable()
    {
        $id  = Request::param('id');
        cache('hooks', null);
        $ret = AddonsModel::where('id', $id)->setField('status', 1);
        if (is_numeric($ret)) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

    /**
     * 禁用插件
     */
    public function disable()
    {
        $id  = Request::param('id');
        cache('hooks', null);
        $ret = AddonsModel::where('id', $id)->setField('status', 2);
        if (is_numeric($ret)) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
    
    /**
     * 插件安装
     */
    public function install(){
      $addons_name = Request::param('name');
      $class = get_addon_class($addons_name);
      if(!class_exists($class)) {
          return $this->response(201, '插件不存在');
      }
      $addon = new $class;
      $info = $addon->info;

      if(!$info || !$addon->checkInfo()) {
          return $this->response(201, '插件信息缺失');
      }

      $install_success = $addon->install();
      if(!$install_success){
          return $this->response(201, '插件预安装失败');
      }

      if(is_array($addon->admin_list) && $addon->admin_list !== []){
          $info['has_adminlist'] = 1;
      }else{
          $info['has_adminlist'] = 0;
      }

      $info['config'] = json_encode($addon->getConfig($addons_name));
      $data = AddonsModel::create($info);

      $hookObj = new Hooks;
      $hookStatus = $hookObj->updateHooks($addons_name);

      if($data && $hookStatus){
          return $this->response(200, Lang::get('Success'));
      }else{
          return $this->response(201, Lang::get('Fail'));
      }
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        $id = Request::param('id');
        $db_addons = AddonsModel::get($id);
        $class = get_addon_class($db_addons['name']);
        if(!$db_addons || !class_exists($class)) {
            return $this->response(201, '插件不存在');
        }

        $addons = new $class;
        $uninstall_flag = $addons->uninstall();
        if(!$uninstall_flag) {
            return $this->response(201, '执行插件预卸载操作失败');
        }

        $hookObj = new Hooks;
        $hooks_update = $hookObj->removeHooks($db_addons['name']);
        if($hooks_update === false){
            return $this->response(201, '卸载插件所挂载的钩子数据失败');
        }

        $delete = AddonsModel::where('name', $db_addons['name'])->delete();
        if($delete === false){
            return $this->response(201, Lang::get('Success'));
        }else{
            return $this->response(200, Lang::get('Fail'));
        }
    }
}