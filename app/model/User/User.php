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

    public function buildWhere($option = [])
    {
        $where = [];
        foreach($option as $key=>$item) {
            switch($key) {
                case 'username':
                    $where[] = [$key,'like',$item.'%'];
                    break;
                case 'account':
                    $where[] = [$key,'=',$item];
                    break;
                default:
                    $where[] = [$key,'=',$item];
            }
        }

        return $where;
    }
}