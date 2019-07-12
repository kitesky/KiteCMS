<?php
namespace app\member\controller;

use think\facade\View;
use app\common\model\AuthRule;
use app\common\controller\IndexCommon;

class Base extends IndexCommon
{
    public function __construct()
    {
        parent::__construct();

        View::share('menu', $this->getMemberMenu());
    }
    protected function getMemberMenu()
    {
        $ruleObj = new AuthRule;
        $rule    = $ruleObj->getUserRule($this->uid, 'member');
        $rule_id = $ruleObj->where('url', get_path_url())->value('id');

        // 验证登录
        if (!in_array($rule_id, array_column($rule, 'id'))){
            $this->error('没有访问权限');
        }

         // 获取当前URL所有父ID
        $ids = get_parents_id($rule, $rule_id);
        $menu = [];
        foreach($rule as $v) {
            // 给父级ID添加ACTIVE表示
            if(in_array($v['id'], $ids)) {
                $v['active'] = 'active'; // 该栏目是否高亮
            } else {
                $v['active']  = '';
            }

            // 生成URL
            if ($v['url'] != '#' || $v['url'] != ''){
                $v['url'] = url($v['url']);
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