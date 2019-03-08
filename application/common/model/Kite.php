<?php
namespace app\common\model;

use think\facade\Request;
use app\common\model\BuildUrl;
use app\common\model\ImageThumb;
use app\common\model\DocumentCategory;
use app\common\model\Site;
use app\common\model\DocumentContent;
use app\common\model\DocumentContentExtra;
use app\common\model\Link;
use app\common\model\Slider;
use app\common\model\Block;
use app\common\model\District;
use app\common\model\Navigation;

class Kite
{
    /**
     * 筛选器
     * @param $request string 参数
     * @return string
     */
    public function filter($site_id, $request)
    {
        // 筛选条件结合
        $filter = [];

        // 查询栏目信息
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryByAlias($site_id, $request['cate_alias']);

        // 下级分类筛选  --1
        $categoryChild = $cateObj->getCategoryChildByPid($category->id);
        // 栏目列表格式
        $cateArr = [];
        if (!empty($categoryChild)) {
            foreach ($categoryChild as $child) {
                $select = [
                    'key'    => $child->id,
                    'value'  => $child->title,
                    'active' => '',
                ];
                array_push($cateArr, $select);
            }
            // 添加全部选项
            if (!empty($cateArr)) {
                $all = [
                    'key'    => 'all',
                    'value'  => '全部',
                    'active' => '',
                ];
                array_unshift($cateArr, $all);
            }

            $categoryField = [
                'name'     => '栏目',
                'variable' => 'category',
                'select'   => $cateArr,
            ];
            if (!empty($cateArr)) {
                array_push($filter, $categoryField);
            }
        }


        // 自定义字段筛选 --2
        $extraObj = new DocumentContentExtra;
        $filterField = $extraObj->getFilterField($category->model_id);
        // 合并到所有筛选字段 
        if (!empty($filterField)) {
            $filter = array_merge($filter, $filterField);
        }

        // 地区筛选项目  --3
        $dist = $extraObj->getDistrictFilterField($category->model_id);
        if (!empty($dist)) {
            $distObj = new District;
            foreach ($dist as $v) {
                // 地区筛选 
                $district_one = $distObj->getParentList($site_id);
                $oneArr = [];
                if (!empty($district_one)) {
                    foreach ($district_one as $val) {
                        $select = [
                            'key'    => $val->id,
                            'value'  => $val['name'],
                            'active' => '',
                        ];
                        array_push($oneArr, $select);
                    }

                    // 添加全部选项
                    if (!empty($oneArr)) {
                        $all = [
                            'key'    => 'all',
                            'value'  => '全部',
                            'active' => '',
                        ];
                        array_unshift($oneArr, $all);
                    }

                    $oneField = [
                        'name'     => $v['name'],
                        'variable' => $v['variable'],
                        'select'   => $oneArr,
                    ];
                    if (!empty($oneArr)) {
                        array_push($filter, $oneField);
                    }
                }
                
                // 地区二级
                if (!empty($request[$v['variable']]) && $request[$v['variable']] != 'all') {
                    $district_two = $distObj->getChildList($site_id, $request[$v['variable']]);
                    $twoArr = [];
                    if (!empty($district_two)) {
                        foreach ($district_two as $val) {
                            $select = [
                                'key'    => $val->id,
                                'value'  => $val['name'],
                                'active' => '',
                            ];
                            array_push($twoArr, $select);
                        }

                        // 添加全部选项
                        if (!empty($twoArr)) {
                            $all = [
                                'key'    => 'all',
                                'value'  => '全部',
                                'active' => '',
                            ];
                            array_unshift($twoArr, $all);
                        }

                        $twoField = [
                            'name'     => $v['name'] . '下级',
                            'variable' => $v['variable'] . '_child',
                            'select'   => $twoArr,
                        ];
                        if (!empty($twoArr)) {
                            array_push($filter, $twoField);
                        }
                    }
                }

            }
        }

        // 生成带链接的数组
        $new_filter = [];
        if (!empty($filter)) {
            foreach ($filter as $field) {
                // var_dump($filter);die;
                // 接收参数为空
                // print_r($request);
                $new_select = [];
                if (empty($request[$field['variable']])) {
                    foreach ($field['select'] as $select) {
                        $param = [
                            $field['variable'] => $select['key'],
                        ];
                        $select['active'] = '';
                        $param = array_merge($param, $request);
                        $select['url'] = BuildUrl::instance($site_id)->categoryUrl($param);
                        array_push($new_select, $select);
                    }
                } else {
                    foreach ($field['select'] as $select) {
                        $param = [
                            $field['variable'] => $select['key'],
                        ];
                        
                        $select['active'] = '';

                        // 循环接收的参数 
                        foreach ($request as $k=>$v) {
                            // 判断是否包含当前的选项
                            if ($field['variable'] != $k) {
                                $param[$k] = $v;
                                // 删除子集
                                if (strrpos($k, '_child')) {
                                    unset($param[$k]);
                                }
                            }

                            // 添加选中标识 active
                            if ($field['variable'] == $k && $select['key'] == $v) {
                                $select['active'] = 'active';
                            }
                        }


                        // 生成链接中不需要带分页参数，因为每次都分页都会自动传入
                        if (isset($param['page'])) {
                            unset($param['page']);
                        }

                        $select['url'] = BuildUrl::instance($site_id)->categoryUrl($param);
                        array_push($new_select, $select);
                    }
                }

                $field['select'] = $new_select;
                array_push($new_filter, $field);
            }
        }

        return $new_filter;
    }

    /**
     * 查询站点字段值
     * @param $field string 需要查询的字段
     * @return string
     */
    public function getDocumentList($site_id = 1, $cid = '1,2,3', $image_flag = 0, $video_flag = 0, $attach_flag = 0, $hot_flag = 0, $recommend_flag = 0, $focus_flag = 0, $top_flag = 0, $limit = 10, $order = 'id desc')
    {
        // 查询条件
        $map = [
            ['site_id', '=', $site_id],
            ['status', '=', 1],
        ];

        // 栏目ID 为 0查询所有栏目
        if (!empty($cid) || $cid != 0) {
            $ids = ['cid', 'in', $cid];
            array_push($map, $ids);
        }

        // 是否图片标志
        if (!empty($image_flag) || $image_flag != 0) {
            $image = ['image_flag', '=', $image_flag];
            array_push($map, $image);
        }

        // 是否视频标志
        if (!empty($video_flag) || $video_flag != 0) {
            $video = ['video_flag', '=', $video_flag];
            array_push($map, $video);
        }

        // 是否附件标志
        if (!empty($attach_flag) || $attach_flag != 0) {
            $attach = ['attach_flag', '=', $attach_flag];
            array_push($map, $attach);
        }

        // 是否热门标志 
        if (!empty($hot_flag) || $hot_flag != 0) {
            $hot = ['hot_flag', '=', $hot_flag];
            array_push($map, $hot);
        }

        // 是否推荐标志 
        if (!empty($recommend_flag) || $recommend_flag != 0) {
            $recommend = ['recommend_flag', '=', $recommend_flag];
            array_push($map, $recommend);
        }

        // 是否焦点标志 
        if (!empty($focus_flag) || $focus_flag != 0) {
            $focus = ['focus_flag', '=', $focus_flag];
            array_push($map, $focus);
        }

        // 是否置顶标志 
        if (!empty($top_flag) || $top_flag != 0) {
            $top = ['top_flag', '=', $top_flag];
            array_push($map, $top);
        }

        // 数量
        if (empty($limit) || $limit == 0) {
            $limit = 10;
        }

        // 排序
        if (empty($order)) {
            $order = 'id desc';
        }

        // 文档列表
        $docObj = new DocumentContent;
        $list = $docObj
            ->where($map)
            ->limit($limit)
            ->order($order)
            ->select();

        $cateObj = new DocumentCategory;
        $extraObj = new DocumentContentExtra;

        if (!empty($list)) {
            foreach ($list as $v) {
                $category = $cateObj->where('id', $v['cid'])->find();
                $category->url = BuildUrl::instance($site_id)->categoryUrl(['alias' => $category->alias]);
                $v->category = $category;
                $v->url = BuildUrl::instance($site_id)->documentUrl(['id' => $v->id]);
                $v->album = !empty($v->album) ? explode(',', $v->album) : [];
                $v->extra = $extraObj->getContentExtraFormatKeyValue($v->id);
                $v->catname = $category->title;
                $v->caturl = $category->url;
            }
        }

       return $list;
    }
    
    /**
     * 查询站点字段值
     * @param $field string 需要查询的字段
     * @return string
     */
    public function getSiteValue($site_id = 1, $field = '')
    {
        $siteObj = new Site;
        if ($field == 'url') {
            return BuildUrl::instance($site_id)->siteUrl();
        } else {
            $fieldValue = $siteObj
                ->where('id', $site_id)
                ->value($field);
            return $fieldValue;
        }
    }

    /**
     * 查询所有栏目分类 输出树状结构
     * @param $site_id int 站点ID
     * @param $pid int 栏目pid
     * @return array
     */
    public function getAllCategoryTree($site_id = 1, $pid = 0, $order = 'weighing asc')
    {
        $cateObj = new DocumentCategory;
        $docObj = new DocumentContent;

        // 查询条件
        $map = [
            ['site_id', 'eq', $site_id],
            ['status', 'eq', 1],
        ];

        if (isset($pid) && $pid != 0) {
            $map[] = ['pid', 'in', [$pid]];
        }

        $list = $cateObj
            ->where($map)
            ->order($order)
            ->select();

        // 获取栏目ID 及所有父级ID
        $cid = Request::param('cid');
        if (!isset($cid)) {
            $alias = Request::param('cate_alias');
            $cid = $cateObj->where('alias', $alias)->value('id');
        }
        // 如果获取不到栏目ID 根据文章ID获取cid
        if (!isset($cid)) {
            $docId = Request::param('id');
            $cid = $docObj->where('id', $docId)->value('cid');
        }

        $parents = get_parents($list, $cid);
        $ids = [];
        if (!empty($parents)) {
            foreach ($parents as $v) {
                array_push($ids, $v->id);
            }
        }

        // 栏目信息处理
        $new_list = [];
        if (!empty($list)) {
            foreach ($list as $v) {
                $v['url'] = BuildUrl::instance($site_id)->categoryUrl(['alias' => $v->alias]);
                if (in_array($v->id, $ids)) {
                    $v->active = 'active';
                } else {
                    $v->active = '';
                }
                $v['document_total'] = $docObj->getDocumentTotalByCid($v->id);
                array_push($new_list, $v);
            }
        }

       return list_to_tree($new_list, 'child', $pid);
    }

    /**
     * 面包屑
     * @return array
     */
    public function getCrumb($site_id = 1)
    {
        // 面包导航数组
        $tree = [];

        // 默认首页
        $home = [
            'title' => '网站首页',
            'url' => BuildUrl::instance($site_id)->siteUrl(),
        ];
        array_push($tree, $home);

        $cateObj = new DocumentCategory;
        $list = $cateObj
            ->where('site_id', $site_id)
            ->where('status', 1)
            ->order('weighing asc')
            ->select();

        // 获取栏目ID 及所有父级ID
        $cid = Request::param('cid');
        $alias = Request::param('cate_alias');
        $docId = Request::param('id');
        $parents = [];
        if (isset($docId)) {
            $docObj = new DocumentContent;
            $doc = $docObj->where('id', $docId)->find();
            $parents = get_parents($list, $doc->cid);
        } else if (isset($cid)) {
            $parents = get_parents($list, $cid);
        } else if(isset($alias)) {
            $cid = $cateObj->where('alias', $alias)->value('id');
            $parents = get_parents($list, $cid);
        }

        // 如果栏目不为空 push到面包导航中
        if (isset($parents)) {
            foreach ($parents as $v) {
                $cate['url'] = BuildUrl::instance($site_id)->categoryUrl(['alias' => $v->alias]);
                $cate['title'] = $v->title;
                array_push($tree, $cate);
            }
        }

        return array_filter($tree);
    }

    /**
     * 查询友情链接
     * @param $site_id int 站点ID
     * @param $cid int 栏目pid
     * @return array
     */
    public function getLinkList($site_id = 1, $cid = 0, $limit = 10, $order = 'weighing asc')
    {
        $linkObj = new Link;
        return $linkObj
            ->where('site_id', $site_id)
            ->where('cid', $cid)
            ->where('status', 1)
            ->limit($limit)
            ->order($order)
            ->select();
    }

    /**
     * 查询站点列表
     * @param $site_id int 站点ID
     * @param $cid int 栏目pid
     * @return array
     */
    public function getSiteList($order = 'weighing asc')
    {
        $site = new Site;
        return $site
            ->where('status', 0)
            ->order($order)
            ->select();
    }

    /**
     * 查询幻灯片
     * @param $site_id int 站点ID
     * @param $cid int 栏目pid
     * @return array
     */
    public function getSliderList($site_id = 1, $cid = 0, $limit = 3, $order = 'weighing asc')
    {
        $sliderObj = new Slider;
        return $sliderObj
            ->where('site_id', $site_id)
            ->where('cid', $cid)
            ->where('status', 1)
            ->limit($limit)
            ->order($order)
            ->select();
    }

    /**
     * 获取区块内容
     * @param $site_id int 站点ID
     * @param $var string 变量标识
     * @return array
     */
    public function getBlockContent($site_id = 1, $variable = '')
    {
        $blockObj = new Block;
        return $blockObj
            ->where('site_id', $site_id)
            ->where('variable', $variable)
            ->where('status', 1)
            ->value('content');
    }

    /**
     * 导航Tree
     * @param $site_id int 站点ID
     * @param $cid int 导航菜单ID
     * @return array
     */
    public function getNavigationForTree($site_id = 1, $cid = 0, $order = 'weighing asc')
    {
        $alias = Request::param('cate_alias');

        $navObj = new Navigation;
        $list = $navObj->getNavigation($site_id, $cid, $order);

        // 获取所有父级ID
        $ids = [];
        if (!empty ($alias)) {
            $id = $navObj->getIdByUrl($site_id, $alias);
            $ids = get_parents_id ($list, $id);
        }

        if (!empty($list)) {
            foreach ($list as $v) {
                $v->active = '';
                if ($v->type == 1) {
                    if (in_array($v->id, $ids)) {
                        $v->active = 'active';
                    }
                    $v->url = url('index/category/index', ['cate_alias' => $v->url]);
                }
            }
        }

       return list_to_tree($list);
    }

    /**
     * 执行sql
     * @param $sql string sql语句
     * @return array
     */
    public function select($sql = '')
    {
        $exp = '/(SELECT.+?FROM)/is';
        if (preg_match($exp, $sql)) {
            return \think\Db::query($sql);
        } else {
            return [];
        }
    }
}