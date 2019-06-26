<?php
namespace app\common\model;

use think\Model;

class Addons extends Model
{
    public function find($id)
    {
        return Addons::get($id);
    }
  
    /**
     * 获取插件列表
     * @param string $addon_dir
     */
    public function getList(){
        $dirs = array_map('basename', glob('./addons/*', GLOB_ONLYDIR));
        if($dirs === false){
            $this->error = '插件目录不可读';
            return false;
        }
        $addons = array();

        if(empty($dirs)) {
            return $addons;
        }

        $list = $this->where('name', 'in', $dirs)->field(true)->select();
        foreach($list as $addon){
            $class = get_addon_class(ucfirst($addon['name']));
            if(!class_exists($class)){
                $has_config = 0;
            }else{
                $obj = new $class();
                $has_config = count($obj->getConfig());
            }
            if ($has_config > 0) {
                $addon['has_config'] = 1;
            } else {
                $addon['has_config'] = 0;
            }
            $addons[$addon['name']] = $addon;
        }

        foreach ($dirs as $value) {
            if(!isset($addons[$value])){
                $class = get_addon_class(ucfirst($value));
                if(!class_exists($class)){ // 实例化插件失败忽略
                    \think\facade\Log::record('插件'.$value.'的入口文件不存在！');
                    continue;
                }

                $obj = new $class;
                $addons[$value] = $obj->info;

                if($addons[$value]){
                    $addons[$value]['status'] = 0;
                }
            }
        }

        return $addons;
    }

}