<?php
namespace app\admin\controller;

use think\Db;
use app\admin\controller\Admin;

class Index extends Admin
{
    public function index()
    {
        $data = [
            'statistics' => $this->statistics(),
            'system'     => $this->systemInfo(),
            'devoloper'  => $this->devoloper(),
        ];

        return $this->fetch('index', $data);
    }

    /**
     * 开发信息
     * @return array
     */
    private function devoloper()
    {
        $data = [
            'website'   => 'http://www.kitesky.com',
            'community' => 'http://bbs.kitesky.com',
            'version'   => 'v1.0',
            'devoloper' => '风筝',
        ];
        
        return $data;
    }

    /**
     * 统计数据信息
     * @return array
     */
    private function statistics()
    {
        $data = [
            'document_count' => Db::name('document_content')->where('site_id', $this->site_id)->count(),
            'comments_count' => Db::name('document_comments')->where('site_id', $this->site_id)->count(),
            'member_count'   => Db::name('auth_user')->count(),
            'site_count'     => Db::name('site')->count(),
        ];
        
        return $data;
    }
    
    /**
     * 获取服务器信息
     * @return array
     */
    private function systemInfo()
    {
        // 查询mysql版本
        $mysqlVersion = Db::query('SELECT VERSION()');

        $data['PHP_OS']             = PHP_OS;
        $data['SERVER_SOFTWARE']    = $_SERVER['SERVER_SOFTWARE'];
        $data['zend_version']       = function_exists('Zend_Version') ? Zend_Version() : 'unkown';
        $data['mysql_version']      = !empty($mysqlVersion[0]['VERSION()']) ? $mysqlVersion[0]['VERSION()'] : 'unkown';
        $data['php_version']        = PHP_VERSION;
        $data['SERVER_ADDR']        = $_SERVER['SERVER_ADDR'] ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
        $data['SERVER_NAME']        = $_SERVER['SERVER_NAME'];
        $data['DOCUMENT_ROOT']      = $_SERVER['DOCUMENT_ROOT'];
        $data['SERVER_PORT']        = $_SERVER['SERVER_PORT'];
        $data['client_ip']          = get_client_ip();

        $data['max_upload']         = ini_get('upload_max_filesize') ? ini_get('upload_max_filesize') : 'OFF';
        $data['max_execution_time'] = ini_get('max_execution_time').' s';
        
        $data['allow_url_fopen']    = ini_get('allow_url_fopen') ? 'ON' : 'OFF';
        $data['safe_mode']          = ini_get('safe_mode') ? 'ON' : 'OFF';

        $data['memory_limit']       = ini_get('memory_limit');
        $data['file_uploads']       = ini_get('file_uploads') ? 'ON' : 'OFF';

        if( function_exists('imagealphablending') && function_exists('imagecreatefromjpeg') && function_exists('ImageJpeg') ){
            $data['gd2'] = 'ON';
        }else{
            $data['gd2'] = 'OFF';
        }

        return $data;
    }
}
