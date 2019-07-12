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

Route::get('feedback', 'index/feedback/index');

// 栏目列表
Route::get('channel-<cat_id>', 'index/category/index')
    ->pattern(['cat_id' => '\d+']);

// 文档详情
Route::get('detail-<id>$', 'index/document/index')
    ->pattern(['id' => '\d+']);
    
// 标签
Route::get('tags-<tag_id>$', 'index/tags/detail')
    ->pattern(['tag_id' => '\d+']);

return [
    
];
