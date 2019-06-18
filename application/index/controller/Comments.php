<?php
namespace app\index\controller;

use think\facade\Request;
use think\facade\Lang;
use think\facade\Hook;
use think\facade\Session;
use app\index\controller\Base;
use app\common\model\DocumentComments;
use app\common\model\DocumentCommentsLike;

class Comments extends Base
{
    protected $rule = [
        'document_id' => 'require',
        'content'     => 'require',
    ];
    protected $message = [
        'document_id.require' => '文档ID不能空',
        'content.require'     => '评论内容不能为空',
    ];

    public function add()
    {
        $request = Request::param();

        // 判断登录状态
        if (empty($this->uid)) {
            return $this->response(201, '请先登录');
        }

        // 验证数据
        $validate = $this->validate($request, $this->rule, $this->message);
        if (true !== $validate) {
            return $this->response(201, $validate);
        }

        // 表单令牌
        $hash = Session::get('__hash__','think');
        if (empty($request['__hash__'])) {
            return $this->response(201, '表单令牌不能空');
        }
        if ($hash != $request['__hash__']) {
            return $this->response(201, '请勿重复提交内容');
        } else {
            Session::delete('__hash__','think');
        }

        $insertData = [
            'site_id' => $this->site_id,
            'uid'     => $this->uid,
        ];
        $insertData = array_merge($insertData, $request);

        // 写入
        $obj = new DocumentComments;
        $obj->allowField(true)->save($insertData);

        if (is_numeric($obj->id)) {
            // 文档评论监听
            $commentsData = [
                'id'          => $obj->id,
                'uid'         => $this->uid,
                'site_id'     => $this->site_id,
                'document_id' => $request['document_id'],
                'content'     => $request['content'],
            ];
            Hook::listen('user_comments', $commentsData);

            return $this->response(200, Lang::get('success'));
        }

        return $this->response(201, Lang::get('fail'));
    }

    public function like()
    {
        $request = Request::param();

        $obj = new DocumentCommentsLike;

        $is_like = $obj->isLike(get_client_ip(), $request['id']);

        if (empty($is_like)) {
            $data = [
                'like'        => $request['type'] == 'like' ? 1 : 0,
                'unlike'      => $request['type'] == 'unlike' ? 1 : 0,
                'comments_id' => $request['id'],
                'ip'          => get_client_ip(),
            ];

            $obj->save($data);

            if (true == $obj->id) {
                $ret  = [
                    'like'   => $obj->likeCount($request['id']),
                    'unlike' => $obj->unlikeCount($request['id']),
                ];

                return $this->response(200, Lang::get('success'), $ret);
            }
        } else {
            return $this->response(201, Lang::get('This record already exists'));
        }
    }
}
