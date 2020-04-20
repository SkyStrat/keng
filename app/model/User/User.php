<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/10
 * Time: 14:47
 */

namespace app\model\User;


use think\Model;

class User extends Model
{
    protected $table = 'user';
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
                case $alias.'username':
                    $where[] = [$key,'like',$item.'%'];
                    break;
                case $alias.'account':
                    $where[] = [$key,'=',$item];
                    break;
                default:
                    $where[] = [$key,'=',$item];
            }
        }

        return $where;
    }
}