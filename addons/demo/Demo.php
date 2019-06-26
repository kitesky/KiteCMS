<?php
namespace addons\demo;

use app\common\controller\Addon;

class Demo extends Addon{

    public $info = array(
      'name'        => 'demo',
      'title'       => '演示插件',
      'description' => '用于演示插件',
      'status'      => 1,
      'author'      => 'kitecms',
      'version'     => '1.0'
    );

    public $admin_list = array(
      'list_grid' => array(
        'id:ID',
        'title:文件名',
        'size:大小',
        'update_time_text:更新时间',
        'document_title:文档标题'
      ),
      'model'=>'Demo',
      'order'=>'id asc'
    );

    public $custom_adminlist = 'adminlist.html';

    // 实现了AdminIndex钩子
    public function AdminIndex()
    {
        //输出要展示的内容
        echo '这是一个钩子演示；QQ群号3337800';
    }

    public function install(){
      return true;
    }

    public function uninstall(){
      return true;
    }

}
