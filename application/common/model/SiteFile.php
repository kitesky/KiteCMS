<?php
namespace app\common\model;

use think\Model;

class SiteFile extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 根据文件路径查询图片详情
     * @param $id 
     * @return boolean
     */
    public function getFileDetailByPath($path)
    {
        return self::where('url', $path)->find();
    }

    /**
     * 根据文件路径删除图片
     * @param $id 
     * @return boolean
     */
    public function removeFileByPath($path)
    {
        $imgInfo = self::where('url', $path)->find();
    }

    /**
     * 设置文件状态
     * @param $id 
     * @return boolean
     */
    public function setFileStatus($id)
    {
        $image = self::get($id);
        $image->status = 1;
        $image->save();
        return $image->id;
    }

    /**
     * 设置更新图片缩略图
     * @param $id 
     * @param $thumb
     * @return array
     */
    public function setImageThumb($id, $thumb)
    {
        $image = self::get($id);
        if (!empty($image->thumb)) {
            $thumbData = explode(',', $image->thumb);
        }
        $thumbData[] = $thumb;
        $thumb = implode(',', $thumbData);
        $image->thumb = $thumb;
        $image->save();
        return $image->id;
    }
    
    /**
     * 列出所有文件 用于百度编辑器上传功能
     * @param $start 
     * @param $size
     * @return array
     */
    public function listFile($site_id, $start, $size)
    {
        // 统计
        $total = self::where('site_id', '=', $site_id)->count();
        $list = self::where('site_id', '=', $site_id)->field('url')->order('id', 'desc')->limit($start, $size)->select();

        $data = [
            'state' => 'SUCCESS',
            'list'  => $list,
            'start' => $start,
            'total' => $total,
        ];

        return json_encode($data);
    }
}