<?php
declare (strict_types = 1);

namespace app\model\Permission;

use think\Model;

/**
 * @mixin think\Model
 */
class RoleMenus extends Model
{
    protected $table = 'role_menus';

    public function menus()
    {
        return $this->hasOne(Menus::class, 'id', 'menus_id');
    }
}
