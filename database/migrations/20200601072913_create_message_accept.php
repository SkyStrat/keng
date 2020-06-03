<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMessageAccept extends Migrator
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
        $table = $this->table('message_accept', array('engine'=>'InnoDB'));
        $table->addColumn('msg_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'消息ID'))
            ->addColumn('accept_account', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'接收人账号'))
            ->addColumn('isread', 'integer', array('length'=>1, 'default'=> 0, 'null'=>false, 'comment'=>'是否已读 0未读 1已读'))
            ->addTimestamps()
            ->create();
    }
}
