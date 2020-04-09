<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateAccount extends Migrator
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
        $table = $this->table('user_account', array('engine'=>'InnoDB'));
        $table->addColumn('account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'账户'))
            ->addColumn('password', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'密码'))
            ->addColumn('role_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'角色id'))
            ->addColumn('username', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'用户名'))
            ->addTimestamps()
            ->addIndex('account')
            ->create();
    }
}
