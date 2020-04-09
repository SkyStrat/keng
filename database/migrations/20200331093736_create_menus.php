<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMenus extends Migrator
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
        $table = $this->table('menus', array('engine'=>'InnoDB'));
        $table->addColumn('pid', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'父id'))
            ->addColumn('name', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'权限名称'))
            ->addColumn('controller', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'控制器'))
            ->addColumn('action', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'方法'))
            ->addColumn('sort', 'integer', array('length'=>5, 'default'=>1, 'null'=>false, 'comment'=>'排序'))
            ->addColumn('status', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'状态  0：启用；1：禁用'))
            ->addTimestamps()
            ->create();
    }
}
