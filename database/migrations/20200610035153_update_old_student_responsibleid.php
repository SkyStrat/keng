<?php

use think\migration\Migrator;
use think\migration\db\Column;

class UpdateOldStudentResponsibleid extends Migrator
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
        $table = $this->table('student_old',array('engine'=>'InnoDB'));
        $table->removeColumn('responsible_account')
            ->addColumn('responsible_id', 'integer', array('default'=>0, 'null'=>false, 'comment'=>'负责人ID'))
            ->update();
    }
}
