<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\DocumentContentExtra;
use app\common\model\DocumentContentLike;
use app\common\model\UploadFile;
use app\common\model\Tags;

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
        return DocumentContent::where('cid', $cid)->count();
    }

    public function getDetail($docement_id)
    {
        $document = $this->where('id', $docement_id)->find();
        if (!empty($document)) {
            $extraObj = new DocumentContentExtra;
            $likeObj  = new DocumentContentLike;
            $tagsObj  = new Tags;

            // 自定义字段信息
            $document->extra = $extraObj->getContentExtraFormatKeyValue($document->id);
            // 图集
            $document->album = !empty($document->album) ? explode(',', $document->album) : [];
            // 喜欢次数
            $document->like = $likeObj->likeCount($document->id);
            $document->unlike = $likeObj->unlikeCount($document->id);
            // Tags
            $tags = $tagsObj->getDocumentTags($document->id);
            $newTags = [];
            if (!empty($tags)) {
                foreach ($tags as $v) {
                    $v['tag_url'] = url('index/tags/detail', ['tag_id' => $v['tag_id']]);
                    $newTags[] = $v;
                }
            }
            $document->tags = $newTags;
            // URL
            $document->url = url('index/document/index', ['id' => $document->id]);
        }

        return $document;
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

        return $this->where($query)->field($field)->order($order)->paginate($limit, true, [
            'type'     => 'bootstrap',
            'var_page' => 'page',
            'query'    => $params,
        ]);
    }

    public function remove($site_id, $id)
    {
        $document = $this->where('id', $id)->find();

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

            //删除tags
            $tagObj = new Tags;
            $tagObj->removeMapp($id);

            // 删除文档
            $document->delete(true);
        }
    }

    public function getDocumentById($site_id, $id)
    {
        return $this->where('site_id', '=', $site_id)
            ->where('id', $id)
            ->find();
    }

    public function getDocumentPrevious($site_id, $id)
    {
        return $this->where('site_id','=',$site_id)
            ->where('id','<',$id)
            ->order('id desc')
            ->find();
    }

    public function getDocumentNext($site_id, $id)
    {
        return $this->where('site_id','=',$site_id)
            ->where('id','>',$id)
            ->order('id asc')
            ->find();
    }
    
    public function getDocumentPaginate($site_id, $cid)
    {
        return $this->where('site_id','=',$site_id) ->where('cid',$cid)->where('status',1)->order('id desc')
        ->paginate(20, false, [
            'type'     => 'bootstrap',
            'var_page' => 'page',
        ]);
    }

    public function getDocmentPaginateByFilter($site_id, $cid, $list_rows = 10, $request)
    {
        $map = [];
        $subQuery = [];

        $categoryMap = [];
        // 分类参数 存在子栏目ID就查子栏目信息 否则查询当前栏目下所有子栏目
        $cateList = Db::name('document_category')->field('id,pid')->select();
        if (isset($request['category']) && is_numeric($request['category'])) {
            $catIds[] = $request['category'];
            unset($request['cat_id']);
            $categoryMap = array(['cid', 'in', $catIds]);
        } else {
            $catIds = get_childs_id($cateList, $cid);
            $catIds[] = $cid;
            $categoryMap = array(['cid', 'in', $catIds]);
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
        return $this->where('site_id',$site_id)->where('status',1)->where($categoryMap)->where($map)->order('id desc')->paginate($list_rows, false, [
                'type'     => 'bootstrap',
                'var_page' => 'page',
                // 'query'    => $request,
            ]);
    }
}