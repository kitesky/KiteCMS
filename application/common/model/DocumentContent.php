<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\DocumentContentExtra;
use app\common\model\UploadFile;

class DocumentContent extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getDocumentTotalByCid($cid)
    {
        return self::where('cid', $cid)->count();
    }

    public function select($request)
    {
        $query = []; 

        // title
        if (isset($request['query']['q']) && is_numeric($request['query']['q'])) {
            $query[] = ['title','like','%'.$request['query']['q'].'%'];
        }

        // cid
        if (!empty($request['query']['cid'])) {
            $query[] = ['cid', 'eq', $request['query']['cid']];
        }

        // site_id
        if (!empty($request['query']['site_id'])) {
            $query[] = ['site_id', 'eq', $request['query']['site_id']];
        }

        // option
        if (!empty($request['query']['option'])) {
            $query[] = [$request['query']['option'], 'eq', 1];
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

        $list = self::where($query)
            ->field($field)
            ->order($order)
            ->paginate($limit, true, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                'query'    => $params,
            ]);

        return $list;
    }

    public function remove($site_id, $id)
    {
        $document = self::where('id', $id)->find();

        if (!empty($document)) {
            // 删除自定义信息
            $extraObj = new DocumentContentExtra;
            $extraObj->remove($site_id, $id);

            // 删除文档封面图片
            if (!empty($document->image)) {
                $file = new UploadFile($site_id);
                $file->remove($document->image);
            }

            // 删除文档图集
            if (!empty($document->album)) {
                $file = new UploadFile($site_id);
                $albumArr = explode(',', $document->album);
                foreach ($albumArr as $v) {
                    $file->remove($v);
                }
            }

            // 删除文档
            $document->delete(true);

        }
    }

    public function getDocumentById($site_id, $id)
    {
        return self::where('site_id', '=', $site_id)
            ->where('id', $id)
            ->find();
    }

    public function getDocumentPrevious($site_id, $id)
    {
        return self::where('site_id','=',$site_id)
            ->where('id','<',$id)
            ->order('id desc')
            ->find();
    }

    public function getDocumentNext($site_id, $id)
    {
        return self::where('site_id','=',$site_id)
            ->where('id','>',$id)
            ->order('id asc')
            ->find();
    }
    
    public function getDocumentPaginate($site_id, $cid)
    {
        return self::where('site_id','=',$site_id)
            ->where('cid',$cid)
            ->where('status',1)
            ->order('id desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);
    }

    public function getDocmentPaginateByFilter($site_id, $cid, $request)
    {
        $map = [];
        $subQuery = [];

        $categoryMap = [];
        // 分类参数 存在子栏目ID就查子栏目信息 否则查询当前栏目下所有子栏目
        if (isset($request['cat_id']) && is_numeric($request['cat_id'])) {
            $categoryMap = [['cid', '=', $request['cat_id']]];
        } else {
            $categoryMap = [['cid', '=', $cid]];
        }

        // 自定义字段参数 构造子查询语句
        if (!empty($request)) {
            foreach ($request as $key => $value) {
                $except = ['cat_id','category', 'page']; // 排除非自定义字段
                if (!in_array($key, $except) && $value != 'all')
                {
                    if (strripos($key, '_child')) {
                        $key = str_ireplace('_child', '', $key);
                    }

                    $extraMap1 = ['variable', '=', $key];
                    $extraMap2 = ['key', 'like', '%'.$value.'%'];

                    $subQuery[] = Db::name('document_content_extra')
                        ->alias('e')
                        ->field('e.document_id')
                        ->where('c.site_id',$site_id)
                        ->where('c.status',1)
                        ->where([$extraMap1])
                        ->where([$extraMap2])
                        ->join('document_content c','e.document_id = c.id')
                        ->fetchSql(true)
                        ->select();
                }
            }
        }

        if (!empty($subQuery)) {
            foreach ($subQuery as $sql) {
                $fetch = Db::query($sql);
                $ids = [];
                if (!empty($fetch)) {
                    foreach ($fetch as $v) {
                        $ids[] = $v['document_id'];
                    }
                }

                $map[] = ['id', 'in', $ids];
            }
        }

        // 查询数据列表
        $list = self::where('site_id',$site_id)
            ->where('status',1)
            ->where($categoryMap)
            ->where($map)
            ->order('id desc')
            ->paginate(20, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                // 'query'    => $request,
            ]);
        // echo self::getLastSql();
        return $list;
    }
}