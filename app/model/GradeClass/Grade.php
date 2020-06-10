<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 20:05
 */

namespace app\model\GradeClass;


use think\Model;

class Grade extends Model
{
    protected $table = 'grade';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间

    /**
     * where条件
     * @param array $option 数据
     * @param string $alias 字段前缀
     * @return array
     */
    public function buildWhere($option = [], $alias = '')
    {
        $where = [];
        foreach($option as $key=>$item) {
            switch($key) {
                case $alias.'name':
                    $where[] = [$alias.$key,'=',$item];
                    break;
                default:
                    $where[] = [$alias.$key,'=',$item];
            }
        }

        return $where;
    }

    public function getGrade($field = '*', $where = [])
    {
        return $this->field($field)->where($where)->select();
    }
}