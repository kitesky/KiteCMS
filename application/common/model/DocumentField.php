<?php
namespace app\common\model;

use think\Model;
use think\Db;

class DocumentField extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public $rule = [
        0 => ['name' => '中文', 'regular'=> '/[\u4e00-\u9fa5]/'],
        1 => ['name' => '正整数', 'regular'=> '/^[0-9]*[1-9][0-9]*$/'],
        2 => ['name' => '英文字符串', 'regular'=> '/^[A-Za-z]+$/'],
        3 => ['name' => 'E-mail地址', 'regular'=> '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/'],
        4 => ['name' => 'URL', 'regular'=> '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/'],
        5 => ['name' => '邮政编码', 'regular'=> '/^[1-9]\d{5}$/'],
        6 => ['name' => '中文、英文、数字及下划线', 'regular' => '/^[\u4e00-\u9fa5_a-zA-Z0-9]+$/'],
        7 => ['name' => '手机号码', 'regular'=> '/^1[3|4|5|6|7|8|9][0-9]\d{4,8}$/'],
        8 => ['name' => '电话号码', 'regular'=> '/^(\(\d{3,4}-)|\d{3.4}-)?\d{7,8}$/'],
        9 => ['name' => '身份证号码', 'regular'=> '/^\d{15}|\d{18}$/'],
    ];

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getTypeTextAttr($value, $data)
    {
        $type = config('site.filedType');
        return $type[$data['type']];
    }

    public function getFields($site_id)
    {
        return self::where('site_id', $site_id)
            ->order('id desc')
            ->select();
    }
}