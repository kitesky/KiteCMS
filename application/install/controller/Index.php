<?php
namespace app\install\controller;

use think\Db;
use think\facade\Request;
use think\facade\Env;
use think\Controller;

class Index extends Controller
{
    protected $databaseRule = [
        'hostname' => 'require',
        'database' => 'require',
        'hostport' => 'require|number',
        'username' => 'require',
        'prefix'   => 'require|regex:/^[a-z0-9]+[_]{1}/',
    ];
    protected $databaseMssage = [
        'hostname.require' => '请填写服务器地址',
        'database.require' => '请填写数据库名称',
        'hostport.require' => '数据库端口不能空',
        'hostport.number'  => '数据库端口为数字',
        'username.require' => '请填写数据库用户名',
        'prefix.require'   => '表前缀不能空',
        'prefix.regex'     => '表前缀格式不正确',
    ];

    protected $userRule = [
        'user'   => 'require|length:4,32',
        'pass'   => 'require|length:4,32',
        'repass' => 'require|confirm:pass'
    ];
    protected $userMssage = [
        'user.require'   => '请填写管理员账号',
        'user.length'    => '账号长度为4-32位字符',
        'pass.require'   => '请填写密码',
        'pass.length'    => '账号长度为4-32位字符',
        'repass.require' => '请确认密码',
        'repass.confirm' => '两次输入密码不一致',
    ];

    public function index()
    {
        $step = Request::param('step');
        $lock = Env::get('app_path') . 'install' . DIRECTORY_SEPARATOR . 'install.lock';
        if (file_exists($lock)) {
            $this->redirect('index/index/index');
        }
        switch ($step) {
            case 2:
                session('error', false);
                return $this->step2();
                break;
            case 3:
                if (session('error')) {
                    return $this->error('环境检测未通过！');
                }
                return $this->step3();
                break;
            case 4:
                if (session('error')) {
                     return $this->step4();
                    return $this->error('环境检测未通过！');
                }
                return $this->step4();
                break;
            case 5:
                if (session('error')) {
                    return $this->error('初始失败！');
                }
                return $this->step5();
                break;
            
            default:
                session('error', false);
                return $this->fetch();
                break;
        }
    }

    protected function step2()
    {
        $data = [
            'env'  => $this->checkEnv(),
            'dir'  => $this->checkDir(),
            'func' => $this->checkFunc(),
            'msg'  => session('error') ? 'error' : 'success',
        ];

        return $this->fetch('step2', $data);
    }

    protected function step3()
    {
        // 第三步 测试数据库连接
        if (Request::isAjax()) {
            $request = Request::param();
            $validate = $this->validate($request, $this->databaseRule, $this->databaseMssage);
            // 数据库字段验证
            if (true !== $validate) {
                return json(['code' => 201, 'msg' => $validate]);
            }

            $extreData = [
                'type'    => 'mysql', //数据库类型
                'charset' => 'utf8',  //默认使用utf8
            ];
            $config = array_merge($request, $extreData);
            $conn = Db::connect($config);
            try {
                $conn->query('select version()');
            } catch(\Exception $e) {
                return json(['code' => 201, 'msg' => '数据库连接失败，请检查填写是否正确']);
            }

            return json(['code' => 200, 'msg' => '连接成功']);
        }

        return $this->fetch('step3');
    }

    
    protected function step4()
    {
        if (Request::isAjax()) {
            $request = Request::param();
            $validate = $this->validate($request, $this->databaseRule, $this->databaseMssage);
            // 数据库字段验证
            if (true !== $validate) {
                return json(['code' => 201, 'msg' => $validate]);
            }

            // 数据库连接验证
            $extreData = [
                'type'    => 'mysql', //数据库类型
                'charset' => 'utf8',  //默认使用utf8
            ];
            $config = array_merge($request, $extreData);
            $conn = Db::connect($config);
            try {
                $conn->query('select version()');
            } catch(\Exception $e) {
                return json(['code' => 201, 'msg' => '数据库连接失败，请检查填写是否正确']);
            }

            // 管理账户字段验证
            $userValidate = $this->validate($request, $this->userRule, $this->userMssage);
            if (true !== $userValidate) {
                return json(['code' => 201, 'msg' => $userValidate]);
            }

            // 写入配置
            $configData = [
                'hostname' => $request['hostname'],
                'database' => $request['database'],
                'hostport' => $request['hostport'],
                'username' => $request['username'],
                'password' => $request['password'],
                'prefix'   => $request['prefix'],
            ];

            $fwrite = $this->updateDatabase($configData);
            if ($fwrite == false) {
                return json(['code' => 201, 'msg' => '数据库配置文件写入失败']);
            }

            // 验证数据库是否存在 并导入数据库文件
            $str1 = 'SELECT * FROM information_schema.schemata WHERE schema_name = "%s"';
            $sql1 = sprintf($str1, $config['database']);
            $checkDatabase = $conn->query($sql1);

            if ($checkDatabase) {
                $sqlList = $this->getSql($request['prefix']);
                if (isset($sqlList)) {
                    foreach ($sqlList as $v) {
                        try {
                            $conn->query($v);
                        } catch(\Exception $e) {
                            return json(['code' => 201, 'msg' => '导入数据库文件失败，请检查sql语句是否正确']);
                        }
                    }
                }
            } 

            // 设置管理员账户
            $userData = [
                    'username' => $request['user'],
                    'password' => password_hash($request['pass'], PASSWORD_DEFAULT),
                    'create_at' => time(),
                    'update_at' => time(),
            ];
            $table = $request['prefix'] . 'auth_user';
            $_updataAdminSql = "UPDATE %s SET username = '%s',password = '%s',create_at = %s ,update_at = %s WHERE uid = 1";
            $updataAdminSql = sprintf($_updataAdminSql, $table, $userData['username'], $userData['password'], $userData['create_at'], $userData['update_at']);
            $conn->query($updataAdminSql);

            // 设置默认站点
            $siteData = [
                'name' => '默认站点',
                'title' => '默认站点',
                'domain' => Request::domain(),
                'theme' => 'default',
                'create_at' => time(),
                'update_at' => time(),
            ];
            $site_table = $request['prefix'] . 'site';
            $_updataSiteSql = "UPDATE %s SET name = '%s',title = '%s',domain = '%s',theme = '%s' ,create_at = %s ,update_at = %s WHERE id = 1";
            $updataSiteSql = sprintf($_updataSiteSql, $site_table, $siteData['name'], $siteData['title'], $siteData['domain'], $siteData['theme'], $siteData['create_at'], $siteData['update_at']);
            $conn->query($updataSiteSql);

            // 生成lock文件
            $create_at = date('Y-m-d H:i:s');
            $lock = Env::get('app_path') . 'install' . DIRECTORY_SEPARATOR . 'install.lock';
            if (!file_exists($lock)) {
                $open = fopen($lock, "w");
                fwrite($open, $create_at);
                fclose($open);
            }

            return json(['code' => 200, 'msg' => '安装成功']);
        }
    }

    private function updateDatabase($configData)
    {
        $fileContent = <<<EOF
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

return [
    // 数据库类型
    'type'            => 'mysql',
    // 服务器地址
    'hostname'        => '%s',
    // 数据库名
    'database'        => '%s',
    // 用户名
    'username'        => '%s',
    // 密码
    'password'        => '%s',
    // 端口
    'hostport'        => '%s',
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => '%s',
    // 数据库调试模式
    'debug'           => true,
    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'deploy'          => 0,
    // 数据库读写是否分离 主从式有效
    'rw_separate'     => false,
    // 读写分离后 主服务器数量
    'master_num'      => 1,
    // 指定从服务器序号
    'slave_no'        => '',
    // 自动读取主库数据
    'read_master'     => false,
    // 是否严格检查字段是否存在
    'fields_strict'   => true,
    // 数据集返回类型
    'resultset_type'  => 'array',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
    // Builder类
    'builder'         => '',
    // Query类
    'query'           => '\\think\\db\\Query',
    // 是否需要断线重连
    'break_reconnect' => false,
    // 断线标识字符串
    'break_match_str' => [],
];
EOF;
        $fileContent = sprintf($fileContent, $configData['hostname'], $configData['database'], $configData['username'], $configData['password'], $configData['hostport'], $configData['prefix']);
        $file = Env::get('config_path') . 'database.php';
        if (is_writable($file)) {
            return file_put_contents($file, $fileContent);
        }else {
            return false;
        }
    }

    private function getSql($prefix = '')
    {
        $sqlfile = Env::get('app_path') . 'install' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'install.sql';
        if (file_exists($sqlfile)) {
            $content = file_get_contents($sqlfile);
            $content = str_replace(["\r\n", "\r"], "\n", $content);
            $content = explode("\n", trim($content));
            $sql = [];
            if (!empty($content)) {
                $comment = false;
                foreach ($content as $key => $value) {
                    if ($value == '') {
                        continue;
                    }
                    if (preg_match("/^(#|--)/", $value)) {
                        continue;
                    }
                    if (preg_match("/^\/\*(.*?)\*\//", $value)) {
                        continue;
                    }
                    if (substr($value, 0, 2) == '/*') {
                        $comment = true;
                        continue;
                    }
                    if (substr($value, -2) == '*/') {
                        $comment = false;
                        continue;
                    }
                    if ($comment) {
                        continue;
                    }
                    if ($prefix != '') {
                        $value = str_replace('`kite_', '`' . $prefix, $value);
                    }
                    if ($value == 'BEGIN;' || $value =='COMMIT;') {
                        continue;
                    }
                    array_push($sql, $value);
                }
            }

            return array_filter(explode(";\n", implode($sql, "\n")));
        } else {
            return false;
        }
    }

    private function checkEnv()
    {
        $option = [
            'os'  => ['操作系统', 'Windows/Linux', '不限制', PHP_OS, 'yes'],
            'Server' => ['Server API', 'Apache/Nginx/IIS', '不限制', PHP_SAPI, 'yes'],
            'php' => ['PHP Version', '5.6.0及以上', '5.6.0', PHP_VERSION, 'yes'],
        ];
        if ($option['php'][3] < $option['php'][2]) {
            $option['php'][4] = 'no';
            session('error', true);
        }

        return $option;
    }

    private function checkDir()
    {
        $option = [
            ['dir', Env::get('app_path'), '读写', '读写', 'yes'],
            ['dir', Env::get('runtime_path'), '读写', '读写', 'yes'],
            ['dir', Env::get('root_path') .'theme' . DIRECTORY_SEPARATOR, '读写', '读写', 'yes'],
            ['dir', Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR, '读写', '读写', 'yes'],
            ['file', Env::get('root_path') . 'config' . DIRECTORY_SEPARATOR .'database.php', '读写', '读写', 'yes'],
        ];
        foreach ($option as &$v) {
            if ($v[0] == 'dir') {
                if(!is_writable($v[1])) {
                    if(is_dir($v[1])) {
                        $v[3] = '不可写';
                        $v[4] = 'no';
                    } else {
                        $v[3] = '不存在';
                        $v[4] = 'no';
                    }
                    session('error', true);
                }
            } else {
                if(!is_writable($v[1])) {
                    $v[3] = '不可写';
                    $v[4] = 'no';
                    session('error', true);
                }
            }
        }
        return $option;
    }

    private function checkFunc()
    {
        $option = [
            ['pdo', '支持', 'yes', 'class'],
            ['pdo_mysql', '支持', 'yes', 'extension'],
            ['openssl', '支持', 'yes', 'extension'],
            ['gd', '支持', 'yes', 'extension'],
            ['mb_strlen', '支持', 'yes', 'function'],
            ['mbstring', '支持', 'yes', 'extension'],
            ['zip', '支持', 'yes', 'extension'],
            ['fileinfo', '支持', 'yes', 'extension'],
            ['curl', '支持', 'yes', 'extension'],
            ['file_get_contents', '支持', 'yes', 'function'],
        ];

        foreach ($option as &$v) {
            if(('class'==$v[3] && !class_exists($v[0])) || ('extension'==$v[3] && !extension_loaded($v[0])) || ('function'==$v[3] && !function_exists($v[0])) ) {
                $v[1] = '不支持';
                $v[2] = 'no';
                session('error', true);
            }
        }

        return $option;
    }
}
