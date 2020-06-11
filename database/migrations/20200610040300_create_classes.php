<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateClasses extends Migrator
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
        $table = $this->table('classes', array('engine'=>'InnoDB'));
        $table->addColumn('name', 'string', array('length'=>10, 'default'=>'', 'null'=>false, 'comment'=>'班级名称'))
            ->addColumn('grade_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'年级id'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人账号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addTimestamps()
            ->create();
    }
}
