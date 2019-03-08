<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\common\model\District as DistrictModel;
use app\admin\controller\Admin;
use app\common\validate\DistrictValidate;

class District extends Admin
{
    public function index()
    {
        $request = Request::param();

        $obj = new DistrictModel;
        $list = $obj->getDistrictForLevel($this->site_id);
        $data = [
            'list' => $list,
        ];

        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DistrictValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $obj = new DistrictModel;
            $arr = preg_split('/\r\n/', $request['name']);
            $list = [];
            if (is_array($arr)) {
                foreach ($arr as $v) {
                    $insertData = [
                        'site_id' => $this->site_id,
                        'name'    => trim($v),
                        'pid'     => $request['pid'],
                        'initial' => get_first_charter($v),
                    ];

                    $exist = $obj
                        ->where('site_id', $this->site_id)
                        ->where('name', $v)
                        ->value('id');
                    if (!$exist) {
                        $list[] = $insertData;
                    }
                }
            }

            $res = $obj->allowField(true)->saveAll($list);
            if ($res != false) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 获取分类列表
        $obj = new DistrictModel;
        $district = $obj->getDistrictForLevel($this->site_id);

        $data = [
            'district'  => $district,
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DistrictValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $obj = new DistrictModel;
            $exist = $obj
                ->where('site_id', $this->site_id)
                ->where('name', $request['name'])
                ->value('id');
            if (is_numeric($exist) && $exist != $request['id']) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $insertData = [
                'name'    => trim($request['name']),
                'initial' => get_first_charter($request['name']),
            ];
            $insertData = array_merge($insertData, $request);
            $obj->isUpdate(true)->allowField(true)->save($insertData);

            if (is_numeric($obj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');

        // 获取地区列表
        $obj = new DistrictModel;
        $district = $obj->getDistrictForLevel($this->site_id);

        // 获取地区信息
        $info = $obj::get($id);

        $data = [
            'district' => $district,
            'info'     => $info,
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');

        // 删除分类
        $des = DistrictModel::destroy($id);

        // 删除分类后子分类fid设为0
        $child = DistrictModel::where('pid', $id)->select();
        if (!empty($child)) {
            foreach ($child as $v) {
                DistrictModel::where('id', $v['id'])->update(['pid' => 0]);
            }
        }

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
    
    public function getDistrictTree()
    {
        $obj = new DistrictModel;
        $list = $obj->where('site_id', $this->site_id)->select();
        $newList = [];
        if (!empty($list)) {
            foreach ($list as $v) {
                $data['name'] = $v->name;
                $data['value'] = $v->id;
                $data['pid'] = $v->pid;
                $data['id'] = $v->id;
                $newList[] = $data;
            }
        }

        return json(list_to_tree ($newList));
    }
}