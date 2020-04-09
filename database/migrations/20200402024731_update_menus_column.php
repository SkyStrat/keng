<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdateMenusColumn extends Migrator
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
        $table->removeColumn('controller')
            ->removeColumn('action')
            ->renameColumn('name', 'title')
            ->addColumn('href', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'路由别名'))
            ->addColumn('target', 'string', array('length'=>15, 'default'=>'_self', 'null'=>false, 'comment'=>'路由别名'))
            ->addColumn('is_menu', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'是否菜单  0：菜单  1：功能'))
            ->update();
    }
}
