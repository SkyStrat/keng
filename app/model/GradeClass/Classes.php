<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/10
 * Time: 12:04
 */

namespace app\model\GradeClass;


use think\Model;

class Classes extends Model
{
    protected $table = 'classes';
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
                case $alias.'grade_id':
                    $where[] = [$alias.$key,'=',$item];
                    break;
                default:
                    $where[] = [$alias.$key,'=',$item];
            }
        }

        return $where;
    }
}