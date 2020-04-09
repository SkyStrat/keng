<?php
declare (strict_types = 1);

namespace app\model\Permission;

use think\Model;

/**
 * @mixin think\Model
 */
class Menus extends Model
{
    protected $table = 'menus';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间(请根据数据库时间字段类型自行填写)

}
