<?php
namespace app\common\model;

use think\Model;

class Feedback extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 查询列表
     * @param $request 参数集合
     * @return array/object
     */
    public function select($request)
    {
        $query = []; 

        // status
        if (isset($request['query']['status']) && is_numeric($request['query']['status'])) {
            $query[] = ['status', 'eq', $request['query']['status']];
        }

        // site_id
        if (!empty($request['query']['site_id'])) {
            $query[] = ['site_id', 'eq', $request['query']['site_id']];
        }

        // 字段过滤
        $field = '*';
        if (!empty($request['field'])) {
            $field = $request['field'];
        }

        // 排序
        $order = 'id desc';
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

        $list = $this->where($query)
            ->field($field)
            ->order($order)
            ->paginate($limit, true, [
                    'var_page' => 'page',
                    'query'    => $params,
                ]);

        return $list;
    }
}