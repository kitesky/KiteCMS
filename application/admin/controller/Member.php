<?php
namespace app\admin\controller;

use think\facade\Lang;
use think\facade\Request;
use think\facade\Hook;
use app\admin\controller\Admin;
use app\common\model\Member as MemberModel;
use app\common\model\MemberGroup;
use app\common\validate\MemberValidate;

use app\common\model\Site;

class Member extends Admin
{
    public function index()
    {
        $request = Request::param();

        $map    = [];
        $params = [];
        $search = [];

        // 查询条件
        if (isset($request['q'])) {
            $q           = ['m.username|m.phone|m.email|g.group_name', 'like', '%'.$request['q'].'%'];
            $params['q'] = $request['q'];
            $search['q'] = $request['q'];
            array_push($map, $q);
        }else {
            $q = [];
            $search['q'] = '';
        }

        $memberObj = new MemberModel;
        $list = $memberObj
            ->alias('m')
            ->field('m.mid,m.username,m.phone,m.email,m.score,m.status,g.group_name')
            ->where($map)
            ->where('m.site_id', 'eq', $this->site_id)
            ->join('member_group g','m.group_id = g.group_id', 'left')
            ->order('m.mid desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        $data = [
            'search' => $search,
            'list' => $list,
            'page' => $list->render()
        ];
        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $exist = MemberModel::where('username', $request['username'])->value('mid');
            if (is_numeric($exist)) {
                return $this->response(201, Lang::get('This record already exists'));
            }

            $memberObj = new MemberModel;
            // 写入会员信息
            $memberData = [
                'site_id'  => $this->site_id,
                'uid'      => $this->uid,
                'password' =>  password_hash($request['password'], PASSWORD_DEFAULT),
            ];
            $memberData = array_merge($request, $memberData);
            $memberObj->allowField(true)->save($memberData);

            if (is_numeric($memberObj->mid)) {
                // 监听会员注册
                $params = [
                    'mid'        => $memberObj->mid,
                    'group_id'   => $memberObj->group_id,
                    'time'       => time(),
                    'ip'         => get_client_ip(),
                ];
                Hook::listen('member_register', $params);

                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 会员组列表
        $groupObj = new MemberGroup;
        $memberList = $groupObj->order('weighing asc')->select();
        $this->assign('memberList', $memberList);
        return $this->fetch('create');
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();
            // 验证数据
            $validate = new MemberValidate();
            $validateResult = $validate->scene('edit')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            // 数据处理
            if (!empty($request['password'])) {
                // 验证密码
                $validateResult = $validate->scene('updatePassword')->check($request);
                if (!$validateResult) {
                    return $this->response(201, $validate->getError());
                }

                $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
            } else {
                unset($request['password']);
                unset($request['repassword']);
            }
            $memberObj = new MemberModel;
            $memberObj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($memberObj->mid)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 会员信息
        $id = Request::param('id');
        $info = MemberModel::where('mid', $id)->find();

        // 会员组列表
        $groupObj = new MemberGroup;
        $memberList = $groupObj->order('weighing asc')->select();

        $data = [
            'memberList' => $memberList,
            'info'       => $info,
        
        ];

        return $this->fetch('edit', $data);
    }

    public function handle()
    {
        $request = Request::instance()->param();

        $memberObj = new MemberModel;
        switch ($request['type']) {
            case 'delete':
                foreach ($request['ids'] as $v) {
                    $result = $memberObj::destroy($v);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'enabled':
                foreach ($request['ids'] as $v) {
                    $result = $memberObj
                        ->where('mid', $v)
                        ->setField('status', 0);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
            case 'disabled':
                foreach ($request['ids'] as $v) {
                    $result = $memberObj
                        ->where('mid', $v)
                        ->setField('status', 1);
                }

                if ($result !== false) {
                    return $this->response(200, Lang::get('Success'));
                }
                break;
        }

        return $this->response(201, Lang::get('Fail'));
    }

    public function remove()
    {
        $id = Request::param('id');

        $des = MemberModel::destroy($id);
        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }

}