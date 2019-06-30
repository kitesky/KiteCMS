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
        $rule = Db::name('user_nav')->select();

        $rule_id = Db::name('user_nav')
            ->where('url', get_path_url())
            ->value('id');
        
        // 验证登录
        if (in_array($rule_id, array_column($rule, 'id'))){
            if (!is_login()) {
                $this->redirect('member/passport/login');
            }
        }


         // 获取当前URL所有父ID
        $ids = get_parents_id($rule, $rule_id);

        // 遍历数组 增加active标识
        $menu = array();
        foreach($rule as $v) {
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