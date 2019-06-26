<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\SiteConfig;

class DocumentModel extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getModeFieldForCategory($site_id, $model_id = '')
    {
        $category = SiteConfig::getCategoryConfig($site_id, 'field_category');
        $list = [];
        if (is_array($category)) {
            foreach ($category as $v) {
                // model_id 获取当前模型下未拥有的所有字段；否则获取所有字段
                if (empty($model_id)) {
                    $v['field'] = Db::name('document_field')
                        ->where('site_id', $site_id)
                        ->where('cid', $v['id'])
                        ->select();
                } else {
                    $v['field'] = Db::name('document_field')
                        ->where('site_id', $site_id)
                        ->where('cid', $v['id'])
                        ->whereNotIn('id', $this->getModelHasFieldIds($model_id))
                        ->select();
                }

                if (!empty($v['field'])) {
                    array_push($list, $v);
                }
            }
        }

        return $list;
    }

    // 获取模型未拥有的字段
    public function getModelHasNoteField($model_id)
    {
        return Db::name('document_field')
            ->whereNotIn('id', $this->getModelHasFieldIds($model_id))
            ->select();
    }

    // 获取模型下拥有的所有字段
    public function getModelHasField($model_id)
    {
        return Db::name('document_model_field')
            ->field('f.*')
            ->alias('mf')
            ->join('document_field f','f.id = mf.field_id')
            ->where('mf.model_id', $model_id)
            ->order('mf.sort asc')
            ->select();
    }

    // 获取模型下所有字段的ID集合
    public function getModelHasFieldIds($model_id)
    {
        $fields = Db::name('document_model_field')
            ->where('model_id', $model_id)
            ->order('sort asc')
            ->select();
        // echo Db::name('document_model_field')->getLastSql();die;
        $list = [];
        if (is_array($list)) {
            foreach ($fields as $v) {
                array_push($list, $v['field_id']);
            }
        }

        return $list;
    }

    // 写入数据
    public function insertModelField($data)
    {
        return Db::name('document_model_field')->insertGetId($data);
    }

    public function deleteModelField($model_id)
    {
        return Db::name('document_model_field')->where('model_id', $model_id)->delete();
    }
}