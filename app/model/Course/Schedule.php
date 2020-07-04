<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/16
 * Time: 10:51
 */

namespace app\model\Course;


use think\Model;

class Schedule extends Model
{
    protected $table = 'fast_schedule';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间
}