<?php
namespace app\common\taglib;

use think\template\TagLib;

class Kite extends TagLib{
    /**
     * 定义全局标签列表
     *
     */
    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'siteinfo'    => ['attr' => 'var', 'close' => 0], //获取网站域名
        'link'        => ['attr' => 'var', 'close' => 1], //链接
        'crumb'       => ['attr' => 'var', 'close' => 1], //面包导航
        'slider'      => ['attr' => 'var', 'close' => 1], //幻灯片
        'block'       => ['attr' => 'var', 'close' => 0], //区块
        'sitelist'    => ['attr' => 'var,order', 'close' => 1], //站点列表
        'navbar'      => ['attr' => 'var,cid,order', 'close' => 1], //导航列表
        'catlist'     => ['attr' => 'var,pid,order', 'close' => 1], // 分类列表
        'doclist'     => ['attr' => 'var,cid,image_flag,video_flag,attach_flag,hot_flag,recommend_flag,size,order', 'close' => 1],
        'tags'        => ['attr' => 'var', 'close' => 1], //标签
        'select'      => ['attr' => 'var,sql', 'close' => 1], // sql语句
    ];

    /**
     * 获取域名
     *
     */
    public function tagSiteinfo($tag)
    {
        $var    = $tag['var'];
        $parse = '<?php ';
        $parse .= 'echo callback("app\\common\\model\\Kite@getSiteValue","'.$var.'"); ';
        $parse .= ' ?>';
        return $parse;
    }

    /**
     * 友情链接列表标签
     *
     */
    public function tagLink($tag, $content)
    {
        $var    = $tag['var'];
        $cid    = isset($tag['cid']) ? $tag['cid'] : 0;
        $limit  = isset($tag['limit']) ? $tag['limit'] : 10;
        $order  = isset($tag['order']) ? $tag['order'] : 'id desc';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getLinkList"';
        $parse .= ',"' . $cid . '"';
        $parse .= ',' . $limit;
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 面包导航列表标签
     *
     */
    public function tagCrumb($tag, $content)
    {
        $var    = $tag['var'];
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getCrumb");';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 幻灯片列表标签
     *
     */
    public function tagSlider($tag, $content)
    {
        $var    = $tag['var'];
        $cid    = isset($tag['cid']) ? $tag['cid'] : 0;
        $limit  = isset($tag['limit']) ? $tag['limit'] : 10;
        $order  = isset($tag['order']) ? $tag['order'] : 'id desc';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getSliderList"';
        $parse .= ',"' . $cid . '"';
        $parse .= ',' . $limit;
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 获取区块内容
     *
     */
    public function tagBlock($tag)
    {
        $var    = $tag['var'];
        $parse = '<?php ';
        $parse .= 'echo callback("app\\common\\model\\Kite@getBlockContent","'.$var.'"); ';
        $parse .= ' ?>';
        return $parse;
    }

    /**
     * 站点列表标签
     *
     */
    public function tagSitelist($tag, $content)
    {
        $var    = $tag['var'];
        $order  = isset($tag['order']) ? $tag['order'] : 'id desc';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getSiteList"';
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 站点导航标签
     *
     */
    public function tagNavbar($tag, $content)
    {
        $var    = $tag['var'];
        $cid    = isset($tag['cid']) ? $tag['cid'] : 0;
        $order  = isset($tag['order']) ? $tag['order'] : 'sort asc';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getNavigationForTree"';
        $parse .= ',"' . $cid . '"';
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 分类列表标签
     *
     */

    public function tagCatlist($tag, $content)
    {
        $var = $tag['var'];
        $pid = isset($tag['pid']) ? $tag['pid'] : 0;
        $order  = isset($tag['order']) ? $tag['order'] : 'sort asc';

        $parse = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getAllCategoryTree"';
        $parse .= ',"' . $pid . '"';
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 文档列表标签
     *
     */
    public function tagDoclist($tag, $content)
    {   $var           = $tag['var'];
        $cid            = isset($tag['cid']) ? $tag['cid'] : 0;
        $image_flag     = isset($tag['image_flag']) ? $tag['image_flag'] : 0;
        $video_flag     = isset($tag['video_flag']) ? $tag['video_flag'] : 0;
        $attach_flag    = isset($tag['attach_flag']) ? $tag['attach_flag'] : 0;
        $hot_flag       = isset($tag['hot_flag']) ? $tag['hot_flag'] : 0;
        $recommend_flag = isset($tag['recommend_flag']) ? $tag['recommend_flag'] : 0;
        $focus_flag     = isset($tag['focus_flag']) ? $tag['focus_flag'] : 0;
        $top_flag       = isset($tag['top_flag']) ? $tag['top_flag'] : 0;
        $limit          = isset($tag['limit']) ? $tag['limit'] : 10;
        $order          = isset($tag['order']) ? $tag['order'] : 'id desc';

        $parse = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getDocumentList"';
        $parse .= ',"' . $cid . '"';
        $parse .= ',' . $image_flag;
        $parse .= ',' . $video_flag;
        $parse .= ',' . $attach_flag;
        $parse .= ',' . $hot_flag;
        $parse .= ',' . $recommend_flag;
        $parse .= ',' . $focus_flag;
        $parse .= ',' . $top_flag;
        $parse .= ',' . $limit;
        $parse .= ',"' . $order .'"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * 标签
     *
     */
    public function tagTags($tag, $content)
    {
        $var    = $tag['var'];
        $limit  = isset($tag['limit']) ? $tag['limit'] : 10;
        $order  = isset($tag['order']) ? $tag['order'] : 'tag_id desc';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@getTagsList"';
        $parse .= ',' . $limit;
        $parse .= ',"' . $order . '"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

    /**
     * SQL语句
     *
     */
    public function tagSelect($tag, $content)
    {
        $var    = $tag['var'];
        $sql    = isset($tag['sql']) ? $tag['sql'] : '';
        $parse  = '<?php ';
        $parse .= '$__LIST__ = callback("app\\common\\model\\Kite@select"';
        $parse .= ',"' . $sql . '"';
        $parse .= '); ';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $var . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }
}