<?php
declare (strict_types = 1);

namespace app\model\Log;

use think\Model;

/**
 * @mixin think\Model
 */
class AdminLog extends Model
{
    protected $table = "admin_log";
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间

    public static function addLog($data)
    {
        $result = self::insert($data);
        return $result;
    }
}
