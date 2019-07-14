<?php
namespace app\common\model;

use think\Model;
use think\Db;

class SiteConfig extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    static public function getCofig($site_id, $k)
    {
        $value = self::where('site_id', $site_id)
            ->where('k', $k)
            ->value('v');
        if (empty($value)) {
            return config('site.' . $k);
        }

        return $value;
    }

    static public function setCofig($data)
    {
        return self::create($data);
    }

    static public function saveCofig($site_id, $k, $v)
    {
        return self::where('site_id', $site_id)
            ->where('k', $k)
            ->update(['v' => $v]);
    }

    static public function getAll($site_id, $config)
    {
        $data = [];
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                $map = [
                    'site_id' => $site_id,
                    'k'       => $k,
                ];
                $value = self::where($map)->find();
                // 站点配置项为空 创建一个配置项
                if (empty($value)) {
                    $configData = [
                        'k' => $k,
                        'v' => $v,
                        'site_id' => $site_id,
                    ];
                    self::setCofig($configData);
                    // 获取最新设置的值
                    $data[$k] = self::getCofig($site_id, $k);
                } else {
                    $data[$k] = $value['v'];
                }
            }
        }

        return $data;
    }

    static public function getCategoryConfig($site_id, $k)
    {
        $value = self::where('site_id', $site_id)
            ->where('k', $k)
            ->find();
        // 类别不存在 创建一个
        if (!isset($value)) {
            $data = [
                'site_id' => $site_id,
                'k'       => $k,
            ];
            self::setCofig($data);
            return false;
        }

        if (isset($value->v)) {
            $list = json_decode($value->v, true);
            array_multisort(array_column($list, 'sort'),SORT_ASC,$list);
            return $list;
        } else {
            return false;
        }
    }

    static public function insertCategoryConfig($site_id, $k, $data)
    {
        $value = self::getCofig($site_id, $k);

        $list = json_decode($value, true);
        // 获取ID值 进行升序排列
        if (isset($list)) {
            $ids = array_column($list, 'id');
            asort($ids);
            $id = end($ids) + 1;
        } else {
            $list = [];
            $id = 1;
        }
        if (isset($data['name']) && isset($data['sort'])) {
            $data['id'] = $id;
            array_push($list, $data);
            $v = json_encode($list, JSON_UNESCAPED_UNICODE);
            return self::saveCofig($site_id, $k, $v);
        } else {
            return false;
        }
    }
    
    static public function updateCategoryConfig($site_id, $k, $data)
    {
        $value = self::getCofig($site_id, $k);
        $list = json_decode($value, true);
        $newList = [];
        foreach ($list as $v) {
            if ($v['id'] == $data['id']) {
                $v['name'] = $data['name'];
                $v['sort'] = $data['sort'];
            }

            $newList[] = $v;
        }

        $v = json_encode($newList, JSON_UNESCAPED_UNICODE);
        return self::saveCofig($site_id, $k, $v);
    }

    static public function getCategoryConfigName($site_id, $k, $cid)
    {
        $value = self::getCofig($site_id, $k);
        $list = json_decode($value, true);

        $newList = [];
        if (!empty($list)) {
            foreach ($list as $v) {
                if ($v['id'] == $cid) {
                    return $v['name'];
                }
            }
        } else {
            return null;
        }
    }

    static public function deleteCategoryConfig($site_id, $k, $id)
    {
        $value = self::getCofig($site_id, $k);
        $list = json_decode($value, true);
        $newList = [];
        foreach ($list as $v) {
            if ($v['id'] != $id) {
                $newList[] = $v;
            }
        }

        $v = json_encode($newList, JSON_UNESCAPED_UNICODE);
        return self::saveCofig($site_id, $k, $v);
    }
}