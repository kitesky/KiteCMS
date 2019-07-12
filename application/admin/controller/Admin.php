<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Env;
use think\facade\Config;
use think\facade\Session;
use think\facade\Lang;
use think\facade\Request;
use app\common\model\Auth;
use app\common\model\Site;
use app\common\controller\Common;

class Admin extends Common
{

    public $uid;

    public $username;

    public $site_id;

    public function __construct()
    {
        // 检测登录
        if (!Auth::checkLogin()) {
            $this->redirect('admin/passport/login');
        } else {
            // 检测权限
            if (!Auth::check(get_path_url())) {
                $this->error(Lang::get('No permissions'));
            }
        }

        // 获取session
        $session        = Session::get('user_auth', 'admin');
        $this->uid      = $session['uid'];
        $this->username = $session['username'];
        $this->site_id  = !empty(Session::get('site_id', 'admin')) ? Session::get('site_id', 'admin') : 0;

        // 查询站点信息
        $siteObj = new Site;
        $site = $siteObj->where('id', $this->site_id)->find();

        // 管理员
        View::share('uid', $session['uid']);
        View::share('username', $session['username']);

        // 菜单赋值
        View::share('menu', Auth::menu());

        // 面包屑
        $breadcrumb = Auth::breadcrumb();
        $end = end($breadcrumb);
        View::share('breadcrumb', $breadcrumb);
        View::share('current', $end['lang_var']);

        // 站点赋值
        View::share('site_list', Auth::getSite($session['uid']));
        View::share('site_id', !empty($site->site_id) ? $site->site_id : null);
        View::share('site_name', !empty($site->name) ? $site->name : null);
        View::share('site_url', !empty($site->domain) ? $site->domain : Request::domain());

        parent::__construct();
    }

    /**
     * 获取模版目录列表
     * @return array
     */
    public function getThemeList() {
        $dir = Env::get('root_path') . 'theme' . DIRECTORY_SEPARATOR;
        $dirArray = [];
        if (false != ($handle = opendir( $dir ))) {
            $i=0;
            while ( false !== ($file = readdir ( $handle )) ) {
                //去掉"“.”、“..”以及“.*”后缀的文件
                if ($file != "." && $file != ".." && !strpos($file, ".")) {
                    $path = $file;
                    $dirArray[$i]['name'] = $path;
                    $i++;
                }
            }
            //关闭句柄
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 扫描目录中的文件
     * @params $dir string 目录
     * @return array
     */
    public function scanFilesForTree($dir)
    {
        $files = [];
        if (is_dir($dir)) {
            if($handle = opendir($dir)) {
                while(($file = readdir($handle)) !== false) {
                    if($file != "." && $file != "..") {
                        if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                            $files[$file] = $this->scanFilesForTree($dir . DIRECTORY_SEPARATOR . $file);
                        }
                        else {
                            $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                        }
                    }
                 }
                closedir($handle);
            }
        }

        return $files;
    }

    /**
     * 获取文件夹内所有文件
     * @param string $dir
     */
    function scanFiles($dir) {
        if (!is_dir($dir)) {
            return [];
        }
        // 兼容各操作系统
        $dir = rtrim(str_replace('\\', '/', $dir), '/').'/';
        // 栈，默认值为传入的目录
        $dirs = array($dir);
        // 放置所有文件的容器
        $rt = [];
        do {
            // 弹栈
            $dir = array_pop($dirs);
            // 扫描该目录
            $tmp = scandir($dir);
            foreach($tmp as $f) {
                if ($f == '.' || $f == '..') continue;
                // 组合当前绝对路径
                $path = $dir.$f;
                // 如果是目录，压栈。
                if (is_dir($path)) {
                    array_push($dirs, $path.'/');
                } else if (is_file($path)) { // 如果是文件，放入容器中
                    $rt[] = $path;
                }
            }
        } while ( $dirs ); // 直到栈中没有目录
        return $rt;
    }

    
    /**
     * 扫描目录中的文件
     * @params $templateDir string 目模板目录名称
     * @return array
     */
    public function getTpl($templateDir, $type = '')
    {
        $templateDir = $templateDir ? $templateDir : 'default';
        $dir = Env::get('root_path') . 'theme' . DIRECTORY_SEPARATOR . $templateDir;
        $files = $this->scanFiles($dir);

        $list = [];
        $pattern = '/'.$type.'/';

        if (!empty($files)) {
            foreach ($files as $v) {
                $ext = strtolower(pathinfo($v, PATHINFO_EXTENSION ));
                $len = strlen($dir) + 1;
                $tplName = substr($v,$len,-5);
                $tplTath = substr($v,$len);

                if (preg_match($pattern, $tplName) && $ext == 'html') {
                    $data = [
                        'name'      => $tplName,
                        'path'      => $tplTath,
                        'fullpath'  => $v,
                        'filesize'  => filesize($v),
                        'filectime' => date('Y-m-d H:i:s', filectime($v)),
                        'filemtime' => date('Y-m-d H:i:s', filemtime($v)),
                        'fileatime' => date('Y-m-d H:i:s', fileatime($v)),
                    ];

                    array_push($list, $data);
                }
            }
        }

        return $list;
    }
}