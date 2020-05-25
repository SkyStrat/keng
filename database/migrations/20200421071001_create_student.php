<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateStudent extends Migrator
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
        $table = $this->table('student', array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'学生名字'))
            ->addColumn('sex', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'性别  0:男  1:女'))
            ->addColumn('age', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'年龄'))
            ->addColumn('student_no', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'学号'))
            ->addColumn('grade', 'string', array('length'=>100, 'default'=>'', 'null'=>false, 'comment'=>'年级'))
            ->addColumn('class', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'班级'))
            ->addColumn('address', 'string', array('length'=>250, 'default'=>'', 'null'=>false, 'comment'=>'家庭住址'))
            ->addColumn('home_phone', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'家庭电话'))
            ->addColumn('status', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'状态 0 入学中 1 在读 2 毕业 3 转学'))
            ->addColumn('remark', 'text', array('default'=>'', 'null'=>false, 'comment'=>'备注'))
            ->addColumn('responsible_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'负责人帐号'))
            ->addColumn('responsible_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'负责人'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人帐号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addTimestamps()
            ->addIndex('student_no')
            ->addIndex(['grade','class'])
            ->create();
    }
}
