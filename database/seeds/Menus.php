<?php

use think\migration\Seeder;

class Menus extends Seeder
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
        $menus = new \app\model\Permission\Menus();

        //一级菜单
        $firstMenus = [
            'pid' => 0,
            'title' => '常规管理',
            'sort' => 1,
            'icon' => 'fa-address-book'
        ];
        $id = $menus->insertGetId($firstMenus);
        //二级菜单
        $secondMenus = [
          [
              'pid' => $id,
              'title' => '菜单管理',
              'sort' => 1,
              'icon' => 'fa-window-maximize'
          ],
            [
                'pid' => $id,
                'title' => '系统设置',
                'sort' => 2,
                'icon' => 'fa-gears'
            ],
            [
                'pid' => $id,
                'title' => '表格示例',
                'sort' => 3,
                'icon' => 'fa-file-text'
            ],
        ];
        $menus->insertAll($secondMenus);
        $secondMenu = [
            'pid' => $id,
            'title' => '表单示例',
            'sort' => 4,
            'icon' => 'fa-calendar'
        ];
        $id = $menus->insertGetId($secondMenu);
        //三级菜单
        $thirdMenus = [
            [
                'pid' => $id,
                'title' => '普通表单',
                'sort' => 1,
                'icon' => 'fa-list-alt'
            ],
            [
                'pid' => $id,
                'title' => '分步表单',
                'sort' => 2,
                'icon' => 'fa-navicon'
            ],
        ];
        $menus->insertAll($thirdMenus);

        $firstMenu = [
            'pid' => 0,
            'title' => '系统管理',
            'sort' => 2,
            'icon' => 'fa-cogs'
        ];
        $id = $menus->insertGetId($firstMenu);
        $secondMenu = [
            'pid' => $id,
            'title' => '菜单管理',
            'sort' => 1,
            'icon' => 'fa-align-justify'
        ];
        $id = $menus->insertGetId($secondMenu);
        $thirdMenus = [
            [
                'pid' => $id,
                'title' => '查询',
                'sort' => 1,
                'icon' => 'fa-adjust',
                'is_menu' => 1
            ],
            [
                'pid' => $id,
                'title' => '添加',
                'sort' => 2,
                'icon' => 'fa-adjust',
                'is_menu' => 1
            ],
            [
                'pid' => $id,
                'title' => '修改',
                'sort' => 3,
                'icon' => 'fa-adjust',
                'is_menu' => 1
            ],
            [
                'pid' => $id,
                'title' => '删除',
                'sort' => 3,
                'icon' => 'fa-adjust',
                'is_menu' => 1
            ],
        ];
        $menus->insertAll($thirdMenus);
    }
}