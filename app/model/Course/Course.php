<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 18:06
 */

namespace app\model\Course;


use app\model\GradeClass\Grade;
use think\Model;
use think\model\concern\SoftDelete;

class Course extends Model
{
    use SoftDelete;

    protected $table = 'course';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间
    protected $deleteTime = 'deleted_time'; //软删除字段，规定是时间类型

    public function grades()
    {
        return $this->hasOne(Grade::class, 'id', 'grade_id');
    }

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
                case $alias.'course_no':
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
}