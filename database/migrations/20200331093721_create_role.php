<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateRole extends Migrator
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
        $table = $this->table('role', array('engine'=>'InnoDB'));
        $table->addColumn('pid', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'父id'))
            ->addColumn('role_name', 'string', array('length'=>15, 'default'=>'', 'null'=>false, 'comment'=>'角色名'))
            ->addColumn('role_permission', 'text', array('null'=>false, 'comment'=>'角色对应的权限id集'))
            ->addTimestamps()
            ->create();
    }
}
