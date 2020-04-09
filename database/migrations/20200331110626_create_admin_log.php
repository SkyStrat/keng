<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateAdminLog extends Migrator
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
        $table = $this->table('admin_log', array('engine'=>'InnoDB'));
        $table->addColumn('controller', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'控制器'))
            ->addColumn('action', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'方法'))
            ->addColumn('message', 'text', array('null'=>false, 'comment'=>'操作信息'))
            ->addColumn('admin_user_name', 'string', array('length'=>15, 'default'=>'' ,'null'=>false, 'comment'=>'操作人'))
            ->addColumn('admin_user_id', 'integer', array('default'=>0, 'null'=>'', 'comment'=>'操作人id'))
            ->addTimestamps()
            ->addIndex('admin_user_id')
            ->create();
    }
}
