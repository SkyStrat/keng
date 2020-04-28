<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22
 * Time: 18:45
 */

namespace app\model;


use think\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间
}