<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUser extends Migrator
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
        $table = $this->table('user',array('engine'=>'InnoDB'));
        $table->addColumn('account_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'账户id'))
            ->addColumn('account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'账户'))
            ->addColumn('username', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'用户名'))
            ->addColumn('sex', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'性别。0：男；1：女'))
            ->addColumn('phone', 'string', array('length'=>11, 'default'=>'', 'null'=>false, 'comment'=>'手机号码'))
            ->addColumn('email', 'string', array('length'=>100, 'default'=>'', 'null'=>false, 'comment'=>'电子邮箱'))
            ->addColumn('qq', 'string', array('length'=>20, 'default'=>'', 'null'=>false, 'comment'=>'QQ号码'))
            ->addColumn('role_name', 'string', array('length'=>15, 'default'=>'', 'null'=>false, 'comment'=>'角色名'))
            ->addIndex('account_id')
            ->addTimestamps()
            ->create();
    }
}
