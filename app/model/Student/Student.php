<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22
 * Time: 18:45
 */

namespace app\model\Student;


use app\model\GradeClass\Grade;
use think\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $pk = 'id';
    protected $createTime = 'create_time'; //创建时间字段名
    protected $updateTime = 'update_time'; //更新时间字段名
    protected $autoWriteTimestamp = 'timestamp'; //自动写入创建时间和更新时间

    public function parents()
    {
        return $this->hasMany(Parents::class, 'student_id', 'id');
    }

    public function grades()
    {
        return $this->hasOne(Grade::class, 'id', 'grade');
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
                case $alias.'student_no':
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

    /**
     * @param $grade string
     * @param $class string
     * @return array|null|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStudentNo($grade, $class)
    {
        return $this->field('student_no')->where(['grade'=>$grade,'class'=>$class])->order('student_no','desc')->find();
    }
}