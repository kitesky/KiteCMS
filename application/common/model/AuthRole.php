<?php
namespace app\common\model;

use think\Model;

class AuthRole extends Model
{
    protected $pk = 'role_id';

    public function getAll($role_ids)
    {
        return AuthRole::all($role_ids);
    }
}