<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\DocumentModel;
use app\common\model\DocumentField;
use app\common\model\District;
use app\common\model\UploadFile;

class DocumentContentExtra extends Model
{
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * input默认值数组
     * @var array
     */
    protected $data;

    /**
     * 验证错误信息
     * @var string
     */
    protected $error;

    /**
     * 设置input默认值
     * @access public
     * @param  mix $extra or $document_id 文档自定义字段信息  变量=>值
     * @return array
     */
    public function setInputDefaultData($extra)
    {
        if (is_numeric($extra)) {
            return $this->data = $this->getContentExtraFormat($extra);
        } else {
            return $this->data = $extra;
        }
    }

    /**
     * 获取验证失败错误提示
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * 自定义字段验证
     * @access public
     * @param  array $extraData 自定义字段input内容
     * @param  int $model_id 自定义模型ID
     * @return array
     */
    public function vlidate($extraData, $model_id)
    {
        $modelObj = new DocumentModel;
        $fields = $modelObj->getModelHasField($model_id);

        if (!empty($fields)) {
            foreach ($fields as $k => $v) {
                // 必填项验证
                if ($v['is_require'] == 1) {
                    // 必填项如果为空
                    if (empty($extraData[$v['variable']])) {
                        $this->error = $v['name'] . '为必填项';
                        return false;
                    }
                     // var_dump($v['variable']);
                }
                
                // 正则验证 仅仅验证 text textarea数据类型
                $type = ['text', 'textarea'];
                if (!empty($v['regular']) && !empty($extraData[$v['variable']] && in_array($v['type'], $type))) {
                    // 正则验证不通过
                    if (!preg_match($v['regular'], $extraData[$v['variable']], $matches)) {
                        $this->error = $v['msg'];
                        return false;
                    }
                }
            }
            return true;
        } else {
            return true;
        }
    }

    // 获取模型下所有筛选字段
    public function getFilterField($model_id)
    {
        $modelObj = new DocumentModel;
        $fields = $modelObj->getModelHasField($model_id);

        $filter = [];
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (in_array($field['type'], ['radio','checkbox','select'])) {
                    $arr = $this->toArr($field['option']);
                    // 添加一个默认选项 [全部]
                    $all = [
                        'key'    => 'all',
                        'value'  => '全部',
                        'active' => '',
                    ];
                    array_unshift($arr, $all);

                    $data = [
                        'name'     => $field['name'],
                        'variable' => $field['variable'],
                        'select'   => $arr,
                    ];

                    array_push($filter, $data);
                }

            }
        }

        return $filter;
    }

    // 获取模型地区筛选字段
    public function getDistrictFilterField($model_id)
    {
        $modelObj = new DocumentModel;
        $fields = $modelObj->getModelHasField($model_id);

        $filter = [];
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (in_array($field['type'], ['district'])) {
                    $data = [
                        'name'     => $field['name'],
                        'variable' => $field['variable'],
                    ];

                    array_push($filter, $data);
                }

            }
        }

        return $filter;
    }

    /**
     * 自定义字段的值格式化为['variable' => 'value']
     * @access public
     * @param  int $document_id 文档信息ID
     * @return array
     */
    public function getContentExtraFormatKeyValue($document_id)
    {
        $extra = Db::name('document_content_extra')
            ->where('document_id', $document_id)
            ->select();
        $newArr = [];
        if (!empty($extra)) {
            foreach ($extra as $v) {
                if ($v['type'] == 'multipleimageupload') {
                   $value = json_decode($v['value'], true);
                } else {
                   $value = $v['value'];
                }
                $newArr[$v['variable']] = $value;
            }
        }

        return $newArr;
    }
    
    /**
     * 自定义字段的值格式化为['variable' => 'key']
     * @access public
     * @param  int $document_id 文档信息ID
     * @return array
     */
    public function getContentExtraFormat($document_id)
    {
        $extra = Db::name('document_content_extra')
            ->where('document_id', $document_id)
            ->select();
        $newArr = [];
        if (!empty($extra)) {
            foreach ($extra as $v) {
                if ($v['type'] == 'checkbox') {
                   $key = explode(',', $v['key']);
                } else {
                   $key = $v['key'];
                }
                $newArr[$v['variable']] = $key;
            }
        }

        return $newArr;
    }

    /**
     * 根据键值获取所有值
     * @access public
     * @param  int|array $option_value 录入自定义字段保存的键值
     * @param  array $field 字段数组信息
     * @return string
     */
    public function getOpionValue($option_value, $field)
    {
        // 单选
        if (in_array($field['type'], ['radio','select'])) {
            if (!empty($field['option'])) {
                $optionArr = $this->toArr($field['option']);
                foreach ($optionArr as $v) {
                    if ($option_value == $v['key']) {
                        return $v['value'];
                    }
                }
            }
        }

        // 多选
        if (in_array($field['type'], ['checkbox'])) {
            $optionArr = $this->toArr($field['option']);
            if (is_array($option_value)) {
                $arr = [];
                if (!empty($optionArr)) {
                    foreach ($optionArr as $v) {
                        if (in_array($v['key'], $option_value)) {
                            array_push($arr, $v['value']);
                        }
                    }
                    return implode(',', $arr);
                }
            }
        }

        // 字符串
        if (in_array($field['type'], ['text','textarea','datetime','imageupload','videoupload','attachupload'])) {
            return $option_value;
        }

        // 地区联动
        if (in_array($field['type'], ['district'])) {
            if (is_array($option_value)) {
                $arr = [];
                foreach ($option_value as $v) {
                    $distObj = new District;
                    $arr[] = $distObj->getDistrictNameById($v);
                }
                return implode(',', $arr);
            }
        }

        // 多图
        if (in_array($field['type'], ['multipleimageupload'])) {
            if (is_array($option_value)) {
                $arr = [];

                foreach ($option_value['image'] as $k => $v) {
                    $arr[$k]['image']       = $option_value['image'][$k];
                    $arr[$k]['title']       = $option_value['title'][$k];
                    $arr[$k]['description'] = $option_value['description'][$k];
                }

                return json_encode($arr, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * 保存信息
     * @access public
     * @param  int $document_id 文档信息ID
     * @param  array $extra input收集的自定义字段值
     * @return boolean
     */
    public function saveContentExtra($document_id, array $extra)
    {
        if (!empty($extra)) {
            foreach ($extra as $k => $v) {
                $field = DocumentField::where('variable', $k)->find();
                // echo Db::name('document_field')->getLastSql();

                // 原始值 多个用逗号隔开
                if (in_array($field['type'], ['checkbox','district'])) {
                    $key = implode(',', $v);
                } else if (in_array($field['type'], ['multipleimageupload'])){
                    // 多图
                    $key = $this->getOpionValue($v, $field);//implode(',', $extra[$k]['image']);
                } else {
                    $key = $v;
                }

                // 转换后的值
                $extraData = [
                    'document_id'  => $document_id,
                    'type'         => $field['type'],
                    'name'         => $field['name'],
                    'variable'     => $k,
                    'value'        => $this->getOpionValue($v, $field),
                    'key'          => $key,
                ];

                // 写入数据之前判断是更新还是新建
                $id = Db::name('document_content_extra')
                    ->where('document_id', $document_id)
                    ->where('variable', $k)
                    ->value('id');
                if (is_numeric($id)) {
                    $res = Db::name('document_content_extra')
                        ->where('id', $id)
                        ->update($extraData);
                } else {
                    $res = Db::name('document_content_extra')->insertGetId($extraData);
                }
            }

            // 返回
            if ($res !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * 删除扩展信息
     * @access public
     * @param  int $document_id 文档信息ID
     * @return boolean
     */
    public function deleteContentExtra($document_id)
    {
        return DocumentContentExtra::where('document_id', 'eq', $document_id)->delete();
    }

    /**
     * 删除
     * @access public
     * @param  int $document_id 文档信息ID
     * @return boolean
     */
    public function remove($site_id, $document_id)
    {
        $list = self::where('document_id', $document_id)->select();

        $res = false;
        if (!empty($list)) {
            foreach ($list as $v) {
                if (in_array($v['type'], ['imageupload','attachupload','videoupload'])) {
                    $up = new UploadFile($site_id);
                    $res = $up->remove($v['value']);
                }

                $res = self::destroy($v['id']);
            }
        }

        return $res;
    }

    /**
     * 创建input HTML文本
     * @access public
     * @param  int $site_id 站点ID
     * @param  int $model_id 自定义模型ID
     * @return boolean
     */
    public function buildInput($site_id, $model_id)
    {
        // 查询model下所有自定义字段
        $modelObj = new DocumentModel;
        $field = $modelObj->getModelHasField($model_id);

        $str = '';
        if (is_array($field)) {
            foreach ($field as $v) {
                switch ($v['type']) {
                    case 'text':
                        $str .= $this->createText($v, $this->data);
                        break;
                    case 'textarea':
                        $str .= $this->createTextarea($v, $this->data);
                        break;
                    case 'radio':
                        $str .= $this->createRadio($v, $this->data);
                        break;
                    case 'checkbox':
                        $str .= $this->createCheckbox($v, $this->data);
                        break;
                    case 'select':
                        $str .= $this->createSelect($v, $this->data);
                        break;
                    case 'datetime':
                        $str .= $this->createDatetime($v, $this->data);
                        break;
                    case 'imageupload':
                        $str .= $this->createImageUpload($v, $this->data);
                        break;
                    case 'multipleimageupload':
                        $str .= $this->createMultipleImageUpload($v, $this->data);
                        break;
                    case 'videoupload':
                        $str .= $this->createVideoUpload($v, $this->data);
                        break;
                    case 'attachupload':
                        $str .= $this->createAttachUpload($v, $this->data);
                        break;
                    case 'district':
                        $str .= $this->createDistrict($v, $this->data);
                        break;
                }
            }
        }

        return $str;
    }

    /**
     * 将配置项解析为数组格式
     * @access public
     * @param  string $str 自定义字段配置项
     * @return array
     */
    public function toArr($str)
    {
        $arr = preg_split('/\r\n/', $str);

        $list = [];
        if (is_array($arr)) {
            foreach($arr as $v){
                $keyValue = explode("=", $v);
                $bool = preg_match('/^[1-9][=].*/', $v);
                if ($bool) {
                    $child = [
                        'key' => $keyValue[0],
                        'value' => $keyValue[1],
                    ];
                    array_push($list, $child);
                }
            }
        }

        return $list;
    }


    /**
     * 创建地区联动
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createDistrict($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = [];
        if (!empty($data[$field['variable']])) {
            $value = explode(',', $data[$field['variable']]);
        }
        $v1 = !empty($value[0]) ? $value[0] : null;
        $v2 = !empty($value[1]) ? $value[1] : null;
        $v3 = !empty($value[2]) ? $value[2] : null;

        $parse .= '</label>';
        $parse .= '<div id="element_id_'.$field['variable'].'">';
        $parse .= '  <div class="row">';
        $parse .= '    <div class="col-md-3"><select data-value="'.$v1.'" name="extra['.$field['variable'].'][]" class="form-control district_one_'.$field['variable'].'"></select></div>';
        $parse .= '    <div class="col-md-3"><select data-value="'.$v2.'" name="extra['.$field['variable'].'][]" class="form-control district_two_'.$field['variable'].'"></select></div>';
        $parse .= '    <div class="col-md-3"><select data-value="'.$v3.'" name="extra['.$field['variable'].'][]" class="form-control district_three_'.$field['variable'].'"></select></div>';
        $parse .= '</div></div></div>';

        $parse .= '<script>';
        $parse .= '$("#element_id_'.$field['variable'].'").cxSelect({';
        $parse .= '  url: "' . url('admin/district/getDistrictTree') . '",';
        $parse .= '  selects: ["district_one_'.$field['variable'].'", "district_two_'.$field['variable'].'", "district_three_'.$field['variable'].'"],';
        $parse .= '  emptyStyle: "none",jsonName: "name",jsonValue: "value",jsonSub: "child"';
        $parse .= '});';
        $parse .= '</script>';

        return $parse;
    }

    /**
     * 附件上传
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createAttachUpload($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-6">';
        $parse .= '<div class="input-group">';
        $parse .= '<input type="text" value="'.$value.'" id="hidden_extra_'.$field['variable'].'" name="extra['.$field['variable'].']" class="form-control input-sm">';
        $parse .= '<span class="input-group-btn"><button type="button" class="btn btn-sm btn-primary btn-flat" id="extra_'.$field['variable'].'">选择附件</button></span>';
        $parse .= '</div></div></div></div>';

        $parse .= '<script>';
        $parse .= 'layui.use("upload", function(){';
        $parse .= '  var $ = layui.jquery';
        $parse .= '  ,upload = layui.upload;';
        $parse .= '  upload.render({';
        $parse .= '    elem: "#extra_'.$field['variable'].'",accept: "file",multiple: false,number: 1';
        $parse .= '    ,url: "'.url('admin/upload/uploadAttach').'"';
        $parse .= '    ,done: function(res){';
        $parse .= '      if (res.code == 200) {';
        $parse .= '        $("#hidden_extra_'.$field['variable'].'").val(res.data.url);';
        $parse .= '      } else {';
        $parse .= '        layer.msg(res.msg);}}});});';
        $parse .= '</script>';

        return $parse;
    }

    /**
     * 视频上传
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createVideoUpload($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-6">';
        $parse .= '<div class="input-group">';
        $parse .= '<input type="text" value="'.$value.'" id="hidden_extra_'.$field['variable'].'" name="extra['.$field['variable'].']" class="form-control input-sm">';
        $parse .= '<span class="input-group-btn"><button type="button" class="btn btn-sm btn-primary btn-flat" id="extra_'.$field['variable'].'">选择视频</button></span>';
        $parse .= '</div></div></div></div>';

        $parse .= '<script>';
        $parse .= 'layui.use("upload", function(){';
        $parse .= '  var $ = layui.jquery';
        $parse .= '  ,upload = layui.upload;';
        $parse .= '  upload.render({';
        $parse .= '    elem: "#extra_'.$field['variable'].'",accept: "file",multiple: false,number: 1';
        $parse .= '    ,url: "'.url('admin/upload/uploadVideo').'"';
        $parse .= '    ,done: function(res){';
        $parse .= '      if (res.code == 200) {';
        $parse .= '        $("#hidden_extra_'.$field['variable'].'").val(res.data.url);';
        $parse .= '      } else {';
        $parse .= '        layer.msg(res.msg);}}});});';
        $parse .= '</script>';

        return $parse;
    }

    /**
     * 多图片上传
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createMultipleImageUpload($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="table-responsive">';
        $parse .= '<div class="btn-group">';
        $parse .= '  <button type="button" class="btn btn-xs" id="extra_'.$field['variable'].'">选择图片</button>';
        $parse .= '  <button type="button" class="btn btn-xs" id="extra_'.$field['variable'].'_Action">开始上传</button>';
        $parse .= '</div>';
        $parse .= '    <table class="table table-condensed">';
        $parse .= '      <thead>';
        $parse .= '        <tr><th>文件名</th>';
        $parse .= '        <th>标题</th>';
        $parse .= '        <th>描述</th>';
        $parse .= '        <th>大小</th>';
        $parse .= '        <th>状态</th>';
        $parse .= '        <th>操作</th>';
        $parse .= '      </tr></thead>';
        $parse .= '      <tbody id="extra_'.$field['variable'].'_List">';

        $list = [];
        if (is_array($value)) {
            foreach ($value['image'] as $k => $v) {
                $arr[$k]['image']       = $v;
                $arr[$k]['title']       = $value['title'][$k];
                $arr[$k]['description'] = $value['description'][$k];
            }
        } else {
            $list = json_decode($value, true);
        }

        if (!empty($list)) {
            foreach ($list as $v) {
                $parse .= ' <tr><td><input class="form-control input-sm" value="'.$v['image'].'" name="extra['.$field['variable'].'][image][]" type="text"></td>';
                $parse .= ' <td><input class="form-control input-sm" value="'.$v['title'].'" name="extra['.$field['variable'].'][title][]" type="text"></td>';
                $parse .= ' <td><input class="form-control input-sm" value="'.$v['description'].'" name="extra['.$field['variable'].'][description][]" type="text"></td>';
                $parse .= ' <td></td>';
                $parse .= ' <td></td>';
                $parse .= ' <td></td>';
                $parse .= ' </tr>';
            }
        }

        $parse .= '      </tbody>';
        $parse .= '    </table>';
        $parse .= '</div></div>';

        $parse .= '<script>';
        $parse .= 'layui.use("upload", function(){';
        $parse .= '  var $ = layui.jquery';
        $parse .= '  ,upload = layui.upload;';


        $parse .= '  var demoListView = $("#extra_'.$field['variable'].'_List")';
        $parse .= '  ,uploadListIns = upload.render({';
        $parse .= '    elem: "#extra_'.$field['variable'].'"';
        $parse .= '    ,url: "'.url('admin/upload/uploadImage').'"';
        $parse .= '    ,accept: "file"';
        $parse .= '    ,multiple: true';
        $parse .= '    ,auto: false';
        $parse .= '    ,bindAction: "#extra_'.$field['variable'].'_Action"';
        $parse .= '    ,choose: function(obj){';
        $parse .= '      var files = this.files = obj.pushFile();';
        $parse .= '      obj.preview(function(index, file, result){';
        $parse .= '        var tr = $([\'<tr id="upload-\'+ index +\'">\'';
        $parse .= '          ,\'<td>\'+ file.name +\'</td>\'';
        $parse .= '          ,\'<td><input class="form-control input-sm" name="extra['.$field['variable'].'][title][]" type="text"></td>\'';
        $parse .= '          ,\'<td><input class="form-control input-sm" name="extra['.$field['variable'].'][description][]" type="text"></td>\'';
        $parse .= '          ,\'<td>\'+ (file.size/1014).toFixed(1) +\'kb</td>\'';
        $parse .= '          ,\'<td>等待</td>\'';
        $parse .= '          ,\'<td>\'';
        $parse .= '            ,\'<span class="layui-btn layui-btn-xs extra-'.$field['variable'].'-reload layui-hide">重传</span>\'';
        $parse .= '            ,\'<span class="layui-btn layui-btn-xs layui-btn-danger extra-'.$field['variable'].'-delete">删除</span>\'';
        $parse .= '          ,\'</td>\'';
        $parse .= '        ,\'</tr>\'].join(""));';

        $parse .= '        tr.find(\'.extra-'.$field['variable'].'-reload\').on(\'click\', function(){';
        $parse .= '          obj.upload(index, file);';
        $parse .= '        });';

        $parse .= '        tr.find(\'.extra-'.$field['variable'].'-delete\').on(\'click\', function(){';
        $parse .= '          delete files[index];';
        $parse .= '          tr.remove();';
        $parse .= '          uploadListIns.config.elem.next()[0].value = "";';
        $parse .= '        });';
        $parse .= '        demoListView.append(tr);';
        $parse .= '      });';
        $parse .= '    }';
        $parse .= '    ,done: function(res, index, upload){';
        $parse .= '      if(res.code == 200){';
        $parse .= '        var tr = demoListView.find(\'tr#upload-\'+ index)';
        $parse .= '        ,tds = tr.children();';
        $parse .= '        tds.eq(4).html(\'<span style="color: #5FB878;">成功</span>\');';
        $parse .= '        tds.eq(5).html(\'<input class="form-control" name="extra['.$field['variable'].'][image][]" value="\'+res.data.url+\'" input-sm" type="hidden">\');';
        $parse .= '        return delete this.files[index];';
        $parse .= '      }';
        $parse .= '      this.error(res, index, upload);';
        $parse .= '    }';
        $parse .= '    ,error: function(res, index, upload){';
        $parse .= '      var tr = demoListView.find(\'tr#upload-\'+ index)';
        $parse .= '      ,tds = tr.children();';
        $parse .= '      tds.eq(4).html(\'<span style="color: #FF5722;">失败</span>\');';
        $parse .= '      tds.eq(5).find(\'.extra-'.$field['variable'].'-reload\').removeClass(\'layui-hide\');';
        $parse .= '      layer.msg(res.msg);';
        $parse .= '    }';
        $parse .= '  });';
        $parse .= '});';
        $parse .= '</script>';
        return $parse;
    }

    /**
     * 图片上传
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createImageUpload($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<div class="layui-upload-drag" id="extra_'.$field['variable'].'">';
        $parse .= '<input type="hidden" value="'.$value.'" id="hidden_extra_'.$field['variable'].'" name="extra['.$field['variable'].']" class="form-control input-sm">';
        $parse .= '<div class="layui-upload-list" id="extra_preview_'.$field['variable'].'">';
        if (empty($value)) {
            $parse .= '<p>点击上传</p>';
        } else {
            $parse .= '<img class="layui-upload-img img-responsive" src="'.$value.'" />';
        }
        $parse .= '</div></div></div></div></div>';

        $parse .= '<script>';
        $parse .= 'layui.use("upload", function(){';
        $parse .= '  var $ = layui.jquery';
        $parse .= '  ,upload = layui.upload;';
        $parse .= '  upload.render({';
        $parse .= '    elem: "#extra_'.$field['variable'].'",accept: "file",multiple: false,number: 1';
        $parse .= '    ,url: "'.url('admin/upload/uploadImage').'"';
        $parse .= '    ,done: function(res){';
        $parse .= '      if (res.code == 200) {';
        $parse .= '        $("#hidden_extra_'.$field['variable'].'").val(res.data.url);';
        $parse .= '        $("#extra_preview_'.$field['variable'].'").html("<img class=\'layui-upload-img img-responsive\' src=\'"+res.data.url+"\'>");';
        $parse .= '      } else {';
        $parse .= '        layer.msg(res.msg);}}});});';
        $parse .= '</script>';

        return $parse;
    }

    /**
     * 创建日期
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createDatetime($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<div class="input-group">';
        $parse .= '<input type="text" value="'.$value.'" id="extra_'.$field['variable'].'" name="extra['.$field['variable'].']" class="form-control input-sm">';
        $parse .= '<div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div>';
        $parse .= '</div></div></div></div>';

        $parse .= '<script>';
        $parse .= 'layui.use("laydate", function(){';
        $parse .= 'var laydate = layui.laydate;';
        $parse .= 'laydate.render({';
        $parse .= ' elem: "#extra_' . $field['variable'] . '"';
        $parse .= ' ,type: "datetime"';
        $parse .= '});';
        $parse .= '});';
        $parse .= '</script>';

        return $parse;
    }

    /**
     * 创建select
     * @access public
     * @param  array $field 字段数组信息
     * @param  array $data 模型下所有自定义字段的默认值集合
     * @return string
     */
    public function createSelect($field, $data=[])
    {
        $option = $this->toArr($field['option']);

        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<select  name="extra['.$field['variable'].']" class="form-control">';

        if (!empty($option)) {
            foreach ($option as $v) {
                // 默认值 选中状态
                $selected = '';
                if (!empty($data[$field['variable']]) && $data[$field['variable']] == $v['key']) {
                    $selected = 'selected';
                }
                $parse .= '<option '.$selected.' value="'.$v['key'].'">'.$v['value'].'</option>';
            }
        }

        $parse .= '</select>';
        $parse .= '</div></div></div>';
        return $parse;
    }

    public function createCheckbox($field, $data=[])
    {
        $option = $this->toArr($field['option']);

        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<div class="checkbox">';

        if (!empty($option)) {
            foreach ($option as $v) {
                // 默认值 选中状态
                $checked = '';
                if (!empty($data[$field['variable']]) && in_array($v['key'], $data[$field['variable']]) ) {
                    $checked = 'checked';
                }
                $parse .= '<label class="checkbox-inline">';
                $parse .= '<input type="checkbox" '.$checked.' name="extra['.$field['variable'].'][]" value="'.$v['key'].'">' . $v['value'];
                $parse .= '</label>';
            }
        }

        $parse .= '</div>';
        $parse .= '</div></div></div>';
        return $parse;
    }

    public function createRadio($field, $data=[])
    {
        $option = $this->toArr($field['option']);

        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<div class="radio">';
        
        if (!empty($option)) {
            foreach ($option as $v) {
                // 默认值 选中状态
                $checked = '';
                if (!empty($data[$field['variable']]) && $data[$field['variable']] == $v['key']) {
                    $checked = 'checked';
                }
                $parse .= '<label class="radio-inline">';
                $parse .= '<input type="radio" '.$checked.' name="extra['.$field['variable'].']" value="'.$v['key'].'">' . $v['value'];
                $parse .= '</label>';
            }
        }

        $parse .= '</div>';
        $parse .= '</div></div></div>';
        return $parse;
    }

    public function createText($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }

        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<input type="text" value="'.$value.'" name="extra['.$field['variable'].']" class="form-control input-sm">';
        $parse .= '</div></div></div>';
        return $parse;
    }

    public function createTextarea($field, $data=[])
    {
        $parse = '<div class="form-group">';
        $parse .= '<label>';
        $parse .= $field['name'];

        if ($field['is_require'] == 1) {
            $parse .= '<span class="text-red">*</span>';
        }
        // 默认值
        $value = '';
        if (!empty($data[$field['variable']])) {
            $value = $data[$field['variable']];
        }

        $parse .= '</label>';
        $parse .= '<div class="row">';
        $parse .= '<div class="col-md-12">';
        $parse .= '<textarea class="form-control input-sm" name="extra['.$field['variable'].']" rows="3">'.$value.'</textarea>';
        $parse .= '</div></div></div>';
        return $parse;
    }
}