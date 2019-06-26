<?php
namespace app\admin\controller;

use think\facade\Request;
use think\facade\Lang;
use app\common\model\DocumentCategory;
use app\common\model\DocumentContent;
use app\common\model\DocumentModel;
use app\admin\controller\Admin;
use app\common\validate\DocumentCategoryValidate;
use app\common\model\Site;

class Category extends Admin
{
    public function index()
    {
        $request = Request::param();

        $categoryObj = new DocumentCategory;
        $list = $categoryObj->getCategoryForLevel($this->site_id);

        $data = [
            'list' => list_for_level($list),
        ];

        return $this->fetch('index', $data);
    }

    public function create()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentCategoryValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $cateObj = new DocumentCategory;

            $insertData = [
                'site_id' => $this->site_id,
            ];
            $insertData = array_merge($request, $insertData);
            $cateObj->allowField(true)->save($insertData);

            if (is_numeric($cateObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        // 获取模型列表
        $modelObj = new DocumentModel;
        $model = $modelObj->where('site_id', $this->site_id)->order('sort asc')->select();

        // 查询站点模版
        $siteObj = new Site;
        $template = $siteObj->where('id', $this->site_id)->value('theme');

        $data = [
            'category'  => $category,
            'model'     => $model,
            'list_tpl'   => $this->getTpl($template, 'category'),
            'detail_tpl' => $this->getTpl($template, 'document'),
        ];

        return $this->fetch('create', $data);
    }

    public function edit()
    {
        // 处理AJAX提交数据
        if (Request::isAjax()) {
            $request = Request::param();

            // 验证数据
            $validate = new DocumentCategoryValidate();
            $validateResult = $validate->scene('create')->check($request);
            if (!$validateResult) {
                return $this->response(201, $validate->getError());
            }

            $cateObj = new DocumentCategory;
            $cateObj->isUpdate(true)->allowField(true)->save($request);

            if (is_numeric($cateObj->id)) {
                return $this->response(200, Lang::get('Success'));
            } else {
                return $this->response(201, Lang::get('Fail'));
            }
        }

        $id = Request::param('id');

        // 获取分类列表
        $cateObj = new DocumentCategory;
        $category = $cateObj->getCategoryForLevel($this->site_id);

        // 获取模型列表
        $modelObj = new DocumentModel;
        $model = $modelObj->where('site_id', $this->site_id)->order('sort asc')->select();

        // 获取分类信息
        $info = $cateObj::get($id);

        // 查询站点模版
        $siteObj = new Site;
        $template = $siteObj->where('id', $this->site_id)->value('theme');

        $data = [
            'category' => $category,
            'model'    => $model,
            'info'     => $info,
            'list_tpl'   => $this->getTpl($template, 'category'),
            'detail_tpl' => $this->getTpl($template, 'document'),
        ];

        return $this->fetch('edit', $data);
    }

    public function remove()
    {
        $id = Request::param('id');

        // 删除分类
        $des = DocumentCategory::destroy($id);

        // 删除分类后子分类fid设为0
        $child = DocumentCategory::where('pid', $id)->select();
        if (!empty($child)) {
            foreach ($child as $v) {
                DocumentCategory::where('id', $v['id'])->update(['pid' => 0]);
            }
        }

        // 删除分类后所有归属该类的文档cid全部设为null
        DocumentContent::where('cid', $id)->update(['cid' => 0]);
        
        if ($des !== false) {
            return $this->response(200, Lang::get('Success'));
        } else {
            return $this->response(201, Lang::get('Fail'));
        }
    }
}