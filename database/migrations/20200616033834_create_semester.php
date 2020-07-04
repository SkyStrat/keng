<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateSemester extends Migrator
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
        $table = $this->table('semester', array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'学期名称'))
            ->addColumn('start_time', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'学期开始时间戳'))
            ->addColumn('end_time', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'学期结束时间戳'))
            ->addColumn('schedule_status', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'是否排课 0未排课 1已排课'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人账号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addTimestamps()
            ->create();
    }
}
