<?php
declare (strict_types = 1);

namespace app\model\Permission;

use think\Model;

/**
 * @mixin think\Model
 */
class Role extends Model
{
    protected $table = 'role';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间

    public function getRolePermission($id)
    {
        $result = $this->where('id',$id)->find();
        return $result;
    }
}
