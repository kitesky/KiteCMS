<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Env;
use think\exception\HttpException;
use app\common\model\Site;
use app\admin\controller\Admin;

class Template extends Admin
{
    public function filelist()
    {
        $siteObj = new Site;
        $template = $siteObj->where('id', $this->site_id)->value('theme');
        $data = [
            'list'     => $this->getTpl($template),
            'template' => $template ? $template : 'default',
        ];

        return $this->fetch('filelist', $data);
    }

    public function fileedit()
    {
        $path = Request::param('path');
        $siteObj = new Site;
        $template = $siteObj->where('id', $this->site_id)->value('theme');
        $rootpath = Env::get('root_path') . 'theme' . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR . $path;
        // 判断文件是否存在
        if (!file_exists($rootpath) && !preg_match("/theme/", $rootpath)) {
            throw new HttpException(404, 'This is not file');
        }

        if (Request::isPost()) {
            if (is_writable($rootpath)) {
                $html = file_put_contents($rootpath, htmlspecialchars_decode(Request::param('html')));
            } else {
                throw new HttpException(404, 'File not readabled');
            }
            if ($html) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        } else {
            if (is_readable($rootpath)) {
                $html = file_get_contents($rootpath);
            } else {
                throw new HttpException(404, 'File not readabled');
            }
            $data = [
                'html' => htmlspecialchars($html),
                'path' => $path,
                'name' => base64_decode(Request::param('name')),
            ];
    
            return $this->fetch('fileedit', $data);
        }
    }
}
