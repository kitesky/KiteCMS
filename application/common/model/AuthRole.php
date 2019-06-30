<?php
namespace app\common\model;

use think\Model;

class AuthRole extends Model
{
    protected $pk = 'role_id';

    public function getAll($role_ids)
    {
        return AuthRole::all($role_ids);
    }

    public function select($request = [])
    {
        // 查询条件
        $query = []; 

        // UID
        if (isset($request['query']['uid'])) {
            $query[] = ['uid', 'eq', $request['query']['uid']];
        }

        // 字段过滤
        $field = '*';
        if (!empty($request['field'])) {
            $field = $request['field'];
        }

        // 排序
        $order = 'role_id desc';
        if (!empty($request['order'])) {
            $order = $request['order'];
        }

        // 分页参数
        $params = [];
        if (!empty($request['params'])) {
            $params = $request['params'];
        }

        // 每页数量
        $limit = 20;
        if (!empty($request['limit'])) {
            $limit = $request['limit'];
        }

       return $this->where($query)->field($field)->order($order)->paginate($limit, true, [
              'type'     => 'bootstrap',
              'var_page' => 'page',
              'query'    => $params,
            ]);
    }

}