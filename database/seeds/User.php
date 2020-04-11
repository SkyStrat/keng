<?php

use think\migration\Seeder;

class User extends Seeder
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
        // faker默认语言是英文会生成英文的数据，在创建实例的时候可以指定为中文
        //$faker = Faker\Factory::create('zh_CN');
        \app\model\User\User::insert(['account_id'=>1, 'account'=>'admin', 'username'=>'admin', 'sex'=>0, 'role_name'=>'超级管理员']);
    }
}