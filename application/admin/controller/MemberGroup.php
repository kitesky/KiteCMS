<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Lang;
use think\facade\Request;
use app\common\model\MemberGroup as MemberGroupModel;
use app\admin\controller\Admin;
use app\common\validate\MemberGroupValidate;

class MemberGroup extends Admin
{
    public function index()
    {
        $groupObj = new MemberGroupModel;
        $list = $groupObj
            ->where('site_id', 'eq', $this->site_id)
            ->order('weighing asc')
            ->select();
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
            $validate = new MemberGroupValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $groupObj = new MemberGroupModel;
            $exist = $groupObj->where('group_name', $request['group_name'])->value('group_id');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 权限ID不为空
            if (isset($request['ids'])) {
                $ids = implode(',' ,$request['ids']);
            }
            
            $data = [
                'site_id' => $this->site_id,
                'permission_ids' => isset($ids) ? $ids : '',
            ];
            $data = array_merge($request, $data);
            $groupObj->allowField(true)->save($data);

            if (is_numeric($groupObj->group_id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $data = [
            'permission' => MemberGroupModel::getPermission(),
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new MemberGroupValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $groupObj = new MemberGroupModel;
            $exist = $groupObj->where('group_name', $request['group_name'])->value('group_id');
            if (is_numeric($exist) && $exist != $request['group_id']) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            // 权限ID不为空
            if (isset($request['ids'])) {
                $ids = implode(',' ,$request['ids']);
            }

            $data = [
                'permission_ids' => isset($ids) ? $ids : '',
            ];

            $data = array_merge($request, $data);
            $groupObj->isUpdate(true)->allowField(true)->save($data);

            if (is_numeric($groupObj->group_id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');
        $info = MemberGroupModel::get($id);
        $data = [
            'info'       => $info,
            'permission' => MemberGroupModel::getPermission(),
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');
        $des = MemberGroupModel::destroy($id);

        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
}