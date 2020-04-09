<?php
declare (strict_types = 1);

namespace app\model\User;

use app\model\Permission\Role;
use think\Model;

/**
 * @mixin think\Model
 */
class Account extends Model
{
    protected $table = 'user_account';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
