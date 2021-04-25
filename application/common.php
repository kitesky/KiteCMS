<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\facade\Request;
use think\facade\Session;
use app\common\model\upload\Thumb;
use think\facade\App;
use think\Loader;


    /**
     * 获取用户头像
     * @return strng
     */
if (!function_exists('get_user_avatar')) {
    function get_user_avatar($uid){
        return \app\common\model\AuthUser::where('uid', $uid)->value('avatar');
    }
}

    /**
     * 获取用户简介
     * @return strng
     */
if (!function_exists('get_user_resume')) {
    function get_user_resume($uid){
        return \app\common\model\AuthUser::where('uid', $uid)->value('resume');
    }
}

    /**
     * 获取tags链接
     * @return strng
     */
if (!function_exists('get_tag_url')) {
    function get_tag_url($tag_id){
        return url('index/tags/detail', ['tag_id' => $tag_id]);
    }
}
    /**
     * 获取栏目URL
     * @return strng
     */
if (!function_exists('get_cat_url')) {
    function get_cat_url($cid){
        return url('index/category/index', ['cat_id' => $cid]);
    }
}

    /**
     * 获取栏目名称
     * @return strng
     */
    if (!function_exists('get_cat_title')) {
        function get_cat_title($cid){
            return \app\common\model\DocumentCategory::where('id', $cid)->value('title');
        }
    }

    /**
     * 获取用户名
     * @return strng
     */
    if (!function_exists('get_username')) {
        function get_username($uid){
            return \app\common\model\AuthUser::where('uid', $uid)->value('username');
        }
    }

    /**
     * 生成订单号
     * @return strng 订单号
     */
    if (!function_exists('make_order_no')) {
        function make_order_no(){
            return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        }
    }

    /**
     * 字符串转换为数组，主要用于把分隔符调整到第二个参数
     * @param  string $str  要分割的字符串
     * @param  string $glue 分割符
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    if (!function_exists('str2arr')) {
        function str2arr($str = '', $glue = ','){
            return explode($glue, $str);
        }
    }

    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array  $arr  要连接的数组
     * @param  string $glue 分割符
     * @return string
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    if (!function_exists('arr2str')) {
        function arr2str($arr = [], $glue = ','){
            return implode($glue, $arr);
        }
    }

    /**
     * 获取插件的模型名
     * @param strng $name 插件名
     * @param strng $model 模型名
     */
    if (!function_exists('get_addon_model')) {
        function get_addon_model($name, $model){
            $class = "addons\\{$name}\model\\{$model}";
            return $class;
        }
    }

    /**
     * 获取插件类的类名
     * @param strng $name 插件名
     */
    if (!function_exists('get_addon_class')) {
        function get_addon_class($name){
            $name = ucfirst($name);
            $class = "\\addons\\" . strtolower($name) . "\\{$name}";
            return $class;
        }
    }

    /**
     * 获取当前URL地址
     * @return string
     */
    if (!function_exists('get_path_url')) {
        function get_path_url()
        {
            return strtolower(Request::module() . '/' . Request::controller() . '/' .Request::action());
        }
    }

    /**
     * 判断前台是否登录
     * @return boolean
     */
    if (!function_exists('is_login')) {
        function is_login()
        {
            $user_auth = Session::get('user_auth', 'index');
            if (!$user_auth) {
                return false;
            } else {
                return $user_auth['uid'];
            }
        }
    }

    /**
     * 获取日期的某一部分
     * @param string $time 时间戳
     * @return string
     */
    if (!function_exists('get_date')) {
        function get_date($time, $t = 'y') {
            $time = strtotime($time);
            switch ($t) {
                case 'y':
                    return date('Y', $time);
                    break;
                case 'm':
                    return date('M', $time);
                    break;
                case 'd':
                    return date('d', $time);
                    break;
                default:
                return date('y-m-d', $time);
            }
        }
    }

    /**
     * 时间戳格式化
     * @param string $time 时间戳
     * @return string
     */
    if (!function_exists('format_time')) {
        function format_time($time = '', $format = '') {
            if(!preg_match('/^([\d]+)$/', $time)){
                $time = strtotime($time);
            }

            if (!empty($format)) {
                return date($format, $time);
            }

            $time = time() - $time;

            if ($time >= 31104000) { // N年前
                $num = (int)($time / 31104000);
                return $num.'年前';
            }
            if ($time >= 2592000) { // N月前
                $num = (int)($time / 2592000);
                return $num.'月前';
            }
            if ($time >= 86400) { // N天前
                $num = (int)($time / 86400);
                return $num.'天前';
            }
            if ($time >= 3600) { // N小时前
                $num = (int)($time / 3600);
                return $num.'小时前';
            }
            if ($time > 60) { // N分钟前
                $num = (int)($time / 60);
                return $num.'分钟前';
            }

            if ($time < 60) { // N秒前
                $num = (int)($time);
                return $num.'分钟前';
            }

            if ($time = 0) { // 刚刚
                $num = (int)($time);
                return '刚刚';
            }
        }
    }

    /**
     * 截取字符长度
     * @param $string
     * @return null|string
     */
    if (!function_exists('mbsubstr')) {
        function mbsubstr($string, $length, $dot = '...') {
            if(strlen($string) <= $length) {
                return $string;
            }

            $pre = chr(1);
            $end = chr(1);
            $string = str_replace(['&amp;', '&quot;', '&lt;', '&gt;'], [$pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end], $string);

            $strcut = '';
            if( 1 ) {
                $n = $tn = $noc = 0;
                while($n < strlen($string)) {

                    $t = ord($string[$n]);
                    if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                        $tn = 1; $n++; $noc++;
                    } elseif(194 <= $t && $t <= 223) {
                        $tn = 2; $n += 2; $noc += 2;
                    } elseif(224 <= $t && $t <= 239) {
                        $tn = 3; $n += 3; $noc += 2;
                    } elseif(240 <= $t && $t <= 247) {
                        $tn = 4; $n += 4; $noc += 2;
                    } elseif(248 <= $t && $t <= 251) {
                        $tn = 5; $n += 5; $noc += 2;
                    } elseif($t == 252 || $t == 253) {
                        $tn = 6; $n += 6; $noc += 2;
                    } else {
                        $n++;
                    }

                    if($noc >= $length) {
                        break;
                    }

                }
                if($noc > $length) {
                    $n -= $tn;
                }

                $strcut = substr($string, 0, $n);

            } else {
                $_length = $length - 1;
                for($i = 0; $i < $length; $i++) {
                    if(ord($string[$i]) <= 127) {
                        $strcut .= $string[$i];
                    } else if($i < $_length) {
                        $strcut .= $string[$i].$string[++$i];
                    }
                }
            }

            $strcut = str_replace([$pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end], ['&amp;', '&quot;', '&lt;', '&gt;'], $strcut);

            $pos = strrpos($strcut, chr(1));
            if($pos !== false) {
                $strcut = substr($strcut, 0, $pos);
            }

            return $strcut.$dot;
        }
    }

    /**
     * @name php获取中文字符拼音首字母
     * @param $str
     * @return null|string
     */
    if (!function_exists('get_first_charter')) {
        function get_first_charter($str)
        {
            if (empty($str)) {
                return '';
            }
            $fchar = ord($str[0]);
            if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str[0]);
            $s1 = iconv('UTF-8', 'gb2312', $str);
            $s2 = iconv('gb2312', 'UTF-8', $s1);
            $s = $s2 == $str ? $s1 : $str;
            $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
            if ($asc >= -20319 && $asc <= -20284) return 'A';
            if ($asc >= -20283 && $asc <= -19776) return 'B';
            if ($asc >= -19775 && $asc <= -19219) return 'C';
            if ($asc >= -19218 && $asc <= -18711) return 'D';
            if ($asc >= -18710 && $asc <= -18527) return 'E';
            if ($asc >= -18526 && $asc <= -18240) return 'F';
            if ($asc >= -18239 && $asc <= -17923) return 'G';
            if ($asc >= -17922 && $asc <= -17418) return 'H';
            if ($asc >= -17417 && $asc <= -16475) return 'J';
            if ($asc >= -16474 && $asc <= -16213) return 'K';
            if ($asc >= -16212 && $asc <= -15641) return 'L';
            if ($asc >= -15640 && $asc <= -15166) return 'M';
            if ($asc >= -15165 && $asc <= -14923) return 'N';
            if ($asc >= -14922 && $asc <= -14915) return 'O';
            if ($asc >= -14914 && $asc <= -14631) return 'P';
            if ($asc >= -14630 && $asc <= -14150) return 'Q';
            if ($asc >= -14149 && $asc <= -14091) return 'R';
            if ($asc >= -14090 && $asc <= -13319) return 'S';
            if ($asc >= -13318 && $asc <= -12839) return 'T';
            if ($asc >= -12838 && $asc <= -12557) return 'W';
            if ($asc >= -12556 && $asc <= -11848) return 'X';
            if ($asc >= -11847 && $asc <= -11056) return 'Y';
            if ($asc >= -11055 && $asc <= -10247) return 'Z';
            return null;
        }
    }

    /**
     * 联系方式脱敏处理 以****替换
     * @param $str 手机号，邮箱
     * @return string
     */
    if (!function_exists('hide_star')) {
        function hide_star($str) { 
            if (strpos($str, '@')) { 
                $email_array = explode("@", $str); 
                $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀 
                $count = 0; 
                $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count); 
                $ret = $prevfix . $str; 
            } else { 
                $pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i'; 
                if (preg_match($pattern, $str)) { 
                    $ret = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4); 
                } else { 
                    $ret = substr($str, 0, 3) . "***" . substr($str, -1); 
                }
            }

            return $ret; 
        }
    }

    /**
     * 生成验证码图片标签
     * @param $id
     * @return mixed
     */
    if (!function_exists('index_captcha_img')) {
        function index_captcha_img($id = "")
        {
            $image = \think\facade\Url::build('index/captcha/index', ['id' => $id]);
            return '<img width="100%" src="' . $image . '" alt="点击更换" onclick="this.src=\'' . $image . '?rand=\'+Math.random();" style="cursor:pointer"/>';
        }
    }

    /**
     * 缩略图函数
     * 该函数主要功能 1、返回图片缩略图
     * @param int $width 图片宽度
     * @param int $height 图片高度
     * @return string 图片URL路径
     */
    if (!function_exists('thumb')) {
         function thumb($path, $width, $height)
         {
             $obj = new Thumb;
             return $obj->thumb($path, $width, $height);
         }
    }

    /**
     * 回调函数 call_user_func_array
     * 该函数主要功能 1、识别站点site_id , 2、解析自定义标签传入的参数
     * @param string $classAction 类名@方法 例如: 'app\\admin\\model\\DocumentCategory@getAllCategoryTree'
     * @return array
     */
    if (!function_exists('callback')) {
        function callback($classAction)
        {
            $site_id = Session::get('site_id', 'index');

            // 参数中是否有site_id 
            $is_site_id = false;
            
            // 解析字符串
            list($class, $action) = explode('@',$classAction);
            if(!class_exists($class)){
                exit('找不到类');
            }

            // 实例化类 验证方法是否存在
            $obj = new $class;
            if(!method_exists($obj, $action)){
                exit('找不到方法');
            }

            // 通过反射获取方法的所有参数
            $refObj = new \ReflectionMethod($obj, $action);

            $methodParams = [];
            if (!empty($refObj->getParameters())) {
                foreach ($refObj->getParameters() as $param) {
                    if ($param->isOptional()) {
                        if ($param->name != 'site_id') {
                            $methodParams[] = $param->getDefaultValue(); //$param->name
                        } else {
                            $is_site_id = true;
                        }
                    }
                }
            }

            // 获取函数所有参数值
            $params = func_get_args();
            unset($params[0]);
            $params = array_values($params);

            // 判断接收的参数和方法的默认参数数量是否相等
            $count_a = count($methodParams);
            $count_b = func_num_args() - 1;

            $newParams = [];
            if (!empty($methodParams) && $count_a == $count_b) {
                foreach ($methodParams as $k => $v) {
                    $newParams[$k] = $params[$k];
                }
            }

            // 如果参数中有站点ID 把site_id 放到第一个
            if ($is_site_id == true) {
                array_unshift($newParams, $site_id);
            }

            return call_user_func_array([$obj, $action], $newParams); 
        }
    }

    /**
     * 传递一个子分类ID返回所有的父级分类
     * @return array
     */
    if (!function_exists('get_parents')) {
        function get_parents($list, $id)
        {
            $arr = array();

            foreach ($list as $v) {
                if ($v['id'] == $id) {
                    $arr[] = $v;
                    $arr = array_merge(get_parents($list, $v['pid']), $arr); 
                }
            }

            return $arr;
        }
    }

    /**
     * 传递一个子分类ID返回所有父分类ID
     * @return array
     */
    if (!function_exists('get_parents_id')) {
        function get_parents_id ($list, $id) {
            $arr = array();
            foreach ($list as $v) {
                if ($v['id'] == $id) {
                    $arr[] = $v['id'];
                    $arr = array_merge($arr, get_parents_id($list, $v['pid']));
                }
            }
            return $arr;
        }
    }

    /**
     * 传递一个父级分类ID返回所有子分类ID
     * @return array
     */
    if (!function_exists('get_childs_id')) {
        function get_childs_id ($list, $pid) {
            $arr = array();
            foreach ($list as $v) {
                if ($v['pid'] == $pid) {
                    $arr[] = $v['id'];
                    $arr = array_merge($arr, get_childs_id($list, $v['id']));
                }
            }
            return $arr;
        }
    }

    /**
     * 传递一个父级分类ID返回所有子分类
     * @return array
     */
    if (!function_exists('get_childs')) {
        function get_childs ($list, $pid) {
            $arr = array();
            foreach ($list as $v) {
                if ($v['pid'] == $pid) {
                    $arr[] = $v;
                    $arr = array_merge($arr, get_childs($list, $v['id']));
                }
            }
            return $arr;
        }
    }

    /**
     * 组合一维数组
     * @return array
     */
    if (!function_exists('list_for_level')) {
      function list_for_level($list, $html = '--', $pid = 0, $level = 0) {
          $arr = array();
          foreach ($list as $k => $v) {
              if ($v['pid'] == $pid) {
                  $v['level'] = $level + 1;
                  $v['html']  = str_repeat($html, $level);
                  $arr[] = $v;
                  $arr = array_merge($arr, list_for_level($list, $html, $v['id'], $level + 1));
              }
          }
          return $arr;
      }
    }

    /**
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @return array
     */
    if (!function_exists('list_to_tree')) {
        function list_to_tree ($list, $name = 'child', $pid = 0) {
            $arr = array();
            foreach ($list as $v) {
                if ($v['pid'] == $pid) {
                    $v[$name] = list_to_tree($list, $name, $v['id']);
                    $arr[] = $v;
                }
            }
            return $arr;
        }
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     */
    if (!function_exists('get_client_ip')) {
        function get_client_ip($type = 0, $adv = false) {
            $type      = $type ? 1 : 0;
            static $ip = NULL;

            if ($ip !== NULL) {
                return $ip[$type];
            }

            // 高级模式获取
            if ($adv) {
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                    $pos = array_search('unknown', $arr);
                    if (false !== $pos) {
                        unset($arr[$pos]);
                    }

                    $ip = trim($arr[0]);
                } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            // IP地址合法验证
            $long = sprintf("%u", ip2long($ip));
            $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
            return $ip[$type];
        }
    }

    /**
     * 数据签名认证
     * @param array $auth 被认证的数据
     * @return string 签名
     */
    if (!function_exists('sign')) {
        function sign($auth)
        {
            // 数据类型检测
            if (!is_array($auth)) {
                $auth = (array)$auth;
            }

            ksort($auth); //排序

            // url编码并生成query字符串
            $code = http_build_query($auth);

            // 生成签名
            $sign = sha1($code);
            return $sign;
        }
    }