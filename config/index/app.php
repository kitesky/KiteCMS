<?php

return [
    'http_exception_template' => [
        // 定义404错误的模板文件地址
        404 =>  Env::get('root_path') . 'template/default/public/404.html',
        // 还可以定义其它的HTTP status
        401 =>  Env::get('root_path') . 'template/default/public/401.html',
    ]
];
