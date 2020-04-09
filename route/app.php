<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use app\middleware\AuthPermission;

/**
 * 建议：路由名称与别名最好一致
 */
Route::group('login', function() {
   //登录页面
   Route::get('/', 'LoginController/loginHtml')->name('loginhtml');
   //登录操作
   Route::post('logining', 'LoginController/query')->name('logining')->validate(\app\validate\Login::class, 'login');
   //退出登录
   Route::get('loginout', 'LoginController/loginOut')->name('loginout');
});

Route::group('/', function() {
   Route::get('/', 'IndexController/index')->name('/');  //首页别名，必须唯一
   Route::get('welcome', 'WelcomeController/index')->name('welcome');   //欢迎页面

   /******************* layuimini测试页面start ********************/
   Route::get('layuimini/menus','layuimini.MenusController/index')->name('layuimini/menus');
   Route::get('layuimini/system','layuimini.SystemController/index')->name('layuimini/system');
   Route::get('layuimini/table','layuimini.TableController/index')->name('layuimini/table');
   Route::get('layuimini/form','layuimini.FormController/formIndex')->name('layuimini/form');
   Route::get('layuimini/formstep','layuimini.FormController/formStepIndex')->name('layuimini/formstep');
   /******************* layuimini测试页面end ********************/

   /******************* 系统设置start ********************/
   Route::get('menus','MenusController/index')->name('menus'); //菜单管理
   Route::get('menuslist','MenusController/queryList')->name('menuslist'); //菜单列表
   Route::post('menusadd', 'MenusController/addMenus')->name('menusadd')->token(); //菜单添加
   Route::post('menusupdate', 'MenusController/updateMenus')->name('menusupdate')->token(); //菜单修改
   Route::post('menusdelete', 'MenusController/deleteMenus')->name('menusdelete'); //菜单删除
   /******************* 系统设置end ********************/

   Route::get('auth', 'AuthController/getMenus')->name('auth'); //获取权限
})->middleware(AuthPermission::class);

//layuimini测试页面
Route::get('test','layuimini.menus/index');

Route::get('hello/:name', 'index/hello');
