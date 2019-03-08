<?php
namespace app\member\controller;

use think\Db;
use think\facade\Url;
use think\facade\View;
use think\facade\Request;
use app\common\controller\IndexCommon;

class Base extends IndexCommon
{
    public function __construct()
    {
        View::share('menu', $this->getMemberMenu());

        parent::__construct();
    }
    protected function getMemberMenu()
    {
        $url = strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());

        $permission = Db::name('member_permission')->where('menu', 1)->select();

        $permission_id = Db::name('member_permission')
            ->where('url', $url)
            ->value('id');

         // 获取当前URL所有父ID
        $praents = get_parents($permission, $permission_id);
        $ids = [];
        if (!empty($praents)) {
            foreach ($praents as $v) {
                array_push($ids, $v['id']);
            }
        }

        // 遍历数组 增加active标识
        $menu = array();
        foreach($permission as $v) {
            // 给父级ID添加ACTIVE表示
            if(in_array($v['id'], $ids)) {
                $v['active'] = 'active'; // 该栏目是否高亮
            } else {
                $v['active']  = '';
            }

            // 生成URL
            if ($v['url'] != '#' || $v['url'] != ''){
                $v['url'] = Url::build($v['url']);
            } else {
                $v['url'] = '#';
            }

            // 过滤不是菜单的URL
            if ($v['menu'] == 1) {
                array_push($menu, $v);
            }
        }

        return list_to_tree($menu);
    }
}