<?php

use think\migration\Seeder;

class Role extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        \app\model\Permission\Role::insert(['pid'=>0, 'role_name'=>'超级管理员', 'role_permission'=>'{}']);
    }
}