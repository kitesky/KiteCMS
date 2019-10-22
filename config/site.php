<?php

/**
 * 配置说明
 * 系统运行使用的配置存在于数据库 ："prefix_site_config" 表中。
 * 关于该文件配置的作用为默认配置，表中没有配置项时候，会读取该文件配置，基于此配置创建配置项。
 * 所以，您需要更改配置请到系统后台进行修改，修改此配置仅在数据表中没有配置项时候生效。
 */
return [

    // base
    'base' => [
        'member_allow_register' => 1,
        'member_allow_comment'  => 1,
        'list_rows'             => 15,
        'default_role'          => 3,
    ],


    // 支付宝
    'payment' => [
        'ali_appid'       => '1',
        'ali_private_key' => '1',
        'ali_public_key'  => '1',
        'ali_notify_url'  => '1',
        'ali_return_url'  => '1',

        'wx_appid'       => '1',
        'wx_mch_id'      => '1',
        'wx_notify_url'  => '1',
        'wx_key'         => '1',
    ],


    // 字段类型
    'filedType' => [
        'text'                => '字符串(text)',
        'textarea'            => '文本框(textArea)',
        'radio'               => '单选(radio)',
        'checkbox'            => '多选(checkBox)',
        'select'              => '下拉选择框(select)',
        'datetime'            => '日期(dateTime)',
        'imageupload'         => '图片上传(imageUpload)',
        'multipleimageupload' => '多图片上传(multipleImageUpload)',
        'videoupload'         => '视频上传(videoUpload)',
        'attachupload'        => '附件上传(attachUpload)',
    ],

    // 邮箱
    'email' => [
        'email_host'       => 'smtp.163.com',      // 邮件服务器
        'email_port'       => '465',               // 邮件服务器端口
        'email_username'   => 'kite365',           // 发信人邮件账户
        'email_password'   => 'wangzheng',         // 发信人邮件密码
        'email_from_email' => 'kite@kitesky.com',  // 发信人邮件密码
        'email_from_name'  => 'KiteCMS',           // 发信人邮件密码

        // 邮件模版
        'email_code_template'     => '尊敬的会员${username} ，您本次的验证码为：${code} ，验证码在5分钟内有效。',               // 验证码模版
        'email_register_template' => '尊敬的会员${username} ，您已经成功注册，请谨记您的用户名及密码。',               // 注册成功公职模版

        // 邮件通知选项
        'send_email_register'     => 0                     // 注册发送邮件
    ],

    // SMS短信接口
    'sms' => [
        'sms_type'              => 'dysms',             // 接口 dysms 阿里大鱼
        'ali_access_key'        => 'AccessKey ID',      // AccessKey ID
        'ali_access_key_secret' => 'Access Key Secret', // Access Key Secret
        'ali_sign_name'         => '19981.com',         // 签名
        'ali_template_code'     => 'SMS_1234',          // 模板代码
    ],

    // 文档选项属性
    'document_option' => [
        'image_flag'     => 'Image',
        'video_flag'     => 'Video',
        'attach_flag'    => 'Attach',
        'hot_flag'       => 'Hot',
        'recommend_flag' => 'Recommend',
        'focus_flag'     => 'Focus',
        'top_flag'       => 'Top',
    ],

    // 验证码默认配置
    'captcha' => [
        'captcha_useZh'            => 0,
        'captcha_useImgBg'         => 0,
        'captcha_fontSize'         => 14,
        'captcha_imageH'           => 0,
        'captcha_imageW'           => 0,
        'captcha_length'           => 6,
        'captcha_useCurve'         => 0,
        'captcha_useNoise'         => 0,
        'captcha_member_register'  => 0,
        'captcha_member_login'     => 0,
        'captcha_publish_document' => 0,
        'captcha_publish_comment'  => 0,
        'captcha_publish_feedback' => 0,
    ],

    // 图片水印默认配置
    'imageWater' => [
        'water_logo'     => '/static/admin/dist/img/nopic.png',
        'water_position' => 9,
        'water_quality'  => 80,
        'water_status'   => 0,
    ],

    // 上传图片默认配置
    'uploadFile' => [
        'upload_type'        => 'local',
        'upload_image_ext'   => 'jpg,png,gif',
        'upload_image_size'  => '2040000',
        'upload_video_ext'   => 'rm,rmvb,wmv,3gp,mp4,mov,avi,flv',
        'upload_video_size'  => '2040000',
        'upload_attach_ext'  => 'doc,xls,rar,zip',
        'upload_attach_size' => '2040000',
        'upload_path'        => 'upload',

        'alioss_key'      => '4H5C4jQbxBAsbwye',
        'alioss_secret'   => 'U5Be9VLZCpy8oCo7sTQSCq806swqGV',
        'alioss_endpoint' => 'oss-cn-shenzhen.aliyuncs.com',
        'alioss_bucket'   => 'kitesky',

        'qiniu_ak'        => '9VWzf1jiS3gEALBi_XtwELHaHzHJIeCXE5W4KtJt',
        'qiniu_sk'        => 'yHGWn3FwN37fkRWpOzzMhXC5jEfgr8',
        'qiniu_bucket'    => 'kitesky',
        'qiniu_domain'    => 'http://onxr8mt8y.bkt.clouddn.com',
    ],

    // 会员积分策略配置
    'memberScore' => [
        'register_score' => 100,
        'login_score'    => 1,
        'publish_score'  => 10,
        'comment_score'  => 5,
    ],

    // upload根目录
    'public_path' => Env::get('root_path'),
];
