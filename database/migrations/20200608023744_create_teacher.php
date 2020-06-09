<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTeacher extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('teacher', array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'姓名'))
            ->addColumn('sex', 'integer', array('length'=>1, 'default'=> 0, 'null'=>false, 'comment'=>'性别  0:男  1:女'))
            ->addColumn('birthday', 'timestamp', array('default'=>'0000-00-00 00:00:00', 'null'=>false, 'comment'=>'生日'))
            ->addColumn('teacher_no', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'教师编号'))
            ->addColumn('address', 'string', array('length'=>250, 'default'=>'', 'null'=>false, 'comment'=>'家庭住址'))
            ->addColumn('phone', 'string', array('length'=>11, 'default'=>'', 'null'=>false, 'comment'=>'手机号码'))
            ->addColumn('wechat', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'微信号'))
            ->addColumn('qq', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'QQ号'))
            ->addColumn('email', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'电子邮件'))
            ->addColumn('urgent_name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'紧急联系人'))
            ->addColumn('urgent_phone', 'string', array('length'=>11, 'default'=>'', 'null'=>false, 'comment'=>'紧急联系电话'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人账号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addColumn('deleted_time', 'timestamp', array('null'=>true, 'comment'=>'删除时间'))
            ->addIndex('teacher_no', array('unique' => true))
            ->addTimestamps()
            ->create();
    }
}
