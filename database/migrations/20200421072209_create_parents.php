<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateParents extends Migrator
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
        $table = $this->table('parents', array('engine'=>'InnoDB'));
        $table->addColumn('parents_id', 'string', array('length'=>40, 'default'=>'', 'null'=>false, 'comment'=>'家庭id'))
            ->addColumn('name', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'家庭成员名字'))
            ->addColumn('relation_child', 'integer', array('length'=>1, 'default'=>0, 'null'=>false, 'comment'=>'与学生之间的关系  0爸爸 1妈妈 2爷爷 3奶奶 4外婆 5外公 6其他(请在备注中说明)'))
            ->addColumn('phone', 'string', array('length'=>11, 'default'=>0, 'null'=>false, 'comment'=>'手机号码'))
            ->addColumn('wechat', 'string', array('length'=>50, 'default'=>'', 'null'=>false, 'comment'=>'微信号'))
            ->addColumn('QQ', 'string', array('length'=>30, 'default'=>'', 'null'=>false, 'comment'=>'QQ号'))
            ->addColumn('remark', 'text', array('default'=>'', 'null'=>false, 'comment'=>'备注'))
            ->addTimestamps()
            ->create();
    }
}
