<?php
namespace app\common\model;

use think\Db;
use think\Model;
use app\common\model\DocumentContent;

class Tags extends Model
{
    protected $pk = 'tag_id';

    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getDocumenListByTagId($tag_id) {
        $map = [
            'tag_id' => $tag_id
        ];

        $list = Db::name('tags_mapping')
            ->where('tag_id', $tag_id)
            ->where($map)
            ->paginate(15, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $map,
            ]);
        
        $newList = [];
        if (!empty($list)) {
            foreach ($list as $v) {
                $docObj = new DocumentContent;
                $detail = $docObj->getDetail($v['document_id']);
                if (!empty($detail)) {
                  $newList[] = $detail;
                }
            }
        }

        $data = [
            'list' => $newList,
            'page' => $list->render(),
        ];
        
        return $data;
    }

    public function removeMapp($document_id)
    {
        return Db::name('tags_mapping')->where('document_id', $document_id)->delete();
    }

    public function getDocumentTags($document_id, $boolean = false)
    {
        $list = Db::name('tags_mapping')
            ->alias('m')
            ->field('t.*')
            ->where('m.document_id', $document_id)
            ->leftJoin('tags t','t.tag_id = m.tag_id')
            ->select();
        if ($boolean) {
            return array_column($list, 'tag_id');
        } else {
            return $list;
        }
    }


    public function createTagsMapp($document_id, array $tag_ids)
    {
        if (!empty($tag_ids)) {
            $docTagsMapp = $this->getDocumentTags($document_id, true);
            foreach ($tag_ids as $v) {
                if (!in_array($v, $docTagsMapp)) {
                    $data = [
                        'tag_id' => $v,
                        'document_id' => $document_id,
                    ];
                    Db::name('tags_mapping')->insertGetId($data);
                }
            }

            foreach ($docTagsMapp as $v) {
                if (!in_array($v, $tag_ids)) {
                    $map = [
                        'tag_id' => $v,
                        'document_id' => $document_id,
                    ];
                    Db::name('tags_mapping')->where($map)->delete();
                }
            }
        }

        return true;
    }


    /**
     * 查询列表
     * @param $request 参数集合
     * @return array/object
     */
    public function getList($request)
    {
        $query = []; 
        // site_id
        if (!empty($request['query']['site_id'])) {
            $query[] = ['site_id', 'eq', $request['query']['site_id']];
        }

        // 字段过滤
        $field = !empty($request['field']) ? $request['field'] : '*';
        // 排序
        $order = !empty($request['order']) ? $request['order'] : 'tag_id desc';
        // 分页参数
        $params = !empty($request['params']) ? $request['params'] : [];
        // 每页数量
        $limit = !empty($request['limit']) ? $request['limit'] : 20;

        $list = $this->where($query)
            ->field($field)
            ->order($order)
            ->paginate($limit, true, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        return $list;
    }
}