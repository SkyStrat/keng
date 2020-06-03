<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateBackgroundMessage extends Migrator
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
        $table = $this->table('background_message', array('engine'=>'InnoDB'));
        $table->addColumn('type', 'integer', array('length'=>1, 'default'=>1, 'null'=>false, 'comment'=>'消息类别，0：全部发送，1：发给指定用户，2：按条件发送'))
            ->addColumn('title', 'string', array('length'=>100, 'default'=>'', 'null'=>false, 'comment'=>'标题'))
            ->addColumn('content', 'text', array('null'=>false, 'comment'=>'内容'))
            ->addColumn('status', 'integer', array('length'=>1, 'default'=>1, 'null'=>false, 'comment'=>'状态（0未处理，1已发送，2已删除）'))
            ->addColumn('create_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人帐号'))
            ->addColumn('create_name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'创建人'))
            ->addTimestamps()
            ->addIndex('status')
            ->addIndex('type')
            ->create();
    }
}
