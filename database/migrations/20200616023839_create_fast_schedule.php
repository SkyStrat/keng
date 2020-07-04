<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateFastSchedule extends Migrator
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
        $table = $this->table('fast_schedule', array('engine'=>'InnoDB'));
        $table->addColumn('course_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'课程id'))
            ->addColumn('course_name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'课程名称'))
            ->addColumn('grade_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'年级id'))
            ->addColumn('grade_name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'年级名称'))
            ->addColumn('classes_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'班级id'))
            ->addColumn('classes_name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'班级名称'))
            ->addColumn('start_time', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'课程开始时间戳'))
            ->addColumn('end_time', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'课程结束时间戳'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人账号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addTimestamps()
            ->create();
    }
}
