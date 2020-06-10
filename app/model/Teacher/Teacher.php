<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:40
 */

namespace app\model\Teacher;


use think\Model;
use think\model\concern\SoftDelete;

class Teacher extends Model
{
    use SoftDelete; //引入软删除

    protected $table = 'teacher';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间
    protected $deleteTime = 'deleted_time'; //软删除字段，规定是时间类型

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
                case $alias.'teacher_no':
                    $where[] = [$alias.$key,'=',$item];
                    break;
                case $alias.'name':
                    $where[] = [$alias.$key,'=',$item];
                    break;
                default:
                    $where[] = [$alias.$key,'=',$item];
            }
        }

        return $where;
    }

    public function getTeacher($field = '*', $where = []) {
        return $this->field($field)->where($where)->select();
    }
}