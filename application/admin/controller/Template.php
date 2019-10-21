<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
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
        if (Request::isAjax()) {
            $request = Request::param();
            $encode = mb_detect_encoding($request['html'], array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            $request['path'] = base64_decode($request['path']);
            if ($encode != 'UTF-8') {
                $request['html'] = iconv($encode, 'UTF-8', $request['html']);
            }

            if (file_exists($request['path']) && preg_match("/theme/", $request['path'])) {
                if (is_writable($request['path'])) {
                    $html = file_put_contents($request['path'], htmlspecialchars_decode($request['html']));
                } else {
                    throw new HttpException(404, 'File not readabled');
                }
            } else {
                throw new HttpException(404, 'This is not file');
            }

            if ($html) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $request = Request::param('path');
        $path = base64_decode($request);

        if (file_exists($path) && preg_match("/theme/", $path)) {
            if (is_readable($path)) {
                $html = file_get_contents($path);
            } else {
                throw new HttpException(404, 'File not readabled');
            }
        } else {
            throw new HttpException(404, 'This is not file');
        }
        $data = [
            'html' => htmlspecialchars($html),
            'path' => $request,
            'name' => base64_decode(Request::param('name')),
        ];

        return $this->fetch('fileedit', $data);
    }
}
