<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/3
 * Time: 11:58
 */

namespace app\controller;

use think\App;
use app\model\Permission\Menus;
use app\model\Permission\RoleMenus;

class AuthController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function getMenus()
    {
        $menusData = array();
        if($this->userInfo['role_id'] === 1) {
            $menus_list = Menus::where(['status'=>0, 'is_menu'=>0])->select()->toArray();
        }else {
            $result = RoleMenus::with(['menus'=>function($query) {
                $query->field('target,href,icon,title,id,pid')->where(['status'=>0, 'is_menu'=>0]);
            }])->where(['role_id'=>$this->userInfo['role_id']])->select()->toArray();

            $menus_list = array_column($result, 'menus');
        }

        foreach($menus_list as $item) {
            //这里通过url方法获取路由地址并回调到前端的话，需要强制转换为string，因为url方法返回的并非string值，而是一个Url实例对象
            //如果是目录没地址，那跳过url方法
            if(!empty($item['href'])) {
                $item['href'] = (string)url($item['href']);
            }
            $menusData[$item['id']] = $item;
        }

        return getMenusJson(childMenus($menusData));
    }
}