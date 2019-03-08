<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


// 站点首页
Route::get('/', 'index/index/index');

Route::get('search', 'index/search/index');

// 栏目列表
Route::get('category/<cate_alias>', 'index/category/index')
    ->pattern(['cate_alias' => '[a-z]+']);

// 文档详情
Route::get('document/<id>$', 'index/document/index')
    ->pattern(['id' => '\d+']);

return [

];
