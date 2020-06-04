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
 * 注意：需要添加token的路由，注意token名称
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

   /******************* 菜单管理start ********************/
   Route::get('menus','MenusController/index')->name('menus'); //菜单管理
   Route::get('menuslist','MenusController/queryList')->name('menuslist'); //菜单列表
   Route::post('menusadd', 'MenusController/addMenus')->name('menusadd')->token('__menutoken__'); //菜单添加
   Route::post('menusupdate', 'MenusController/updateMenus')->name('menusupdate')->token('__menutoken__'); //菜单修改
   Route::post('menusdelete', 'MenusController/deleteMenus')->name('menusdelete'); //菜单删除
   /******************* 菜单管理end ********************/

   /******************* 角色管理start ********************/
   Route::get('role', 'RoleController/index')->name('role'); //角色管理
   Route::get('rolelist', 'RoleController/queryList')->name('rolelist'); //角色列表
   Route::post('roleadd', 'RoleController/addRole')->name('roleadd')->token('__roletoken__'); //角色添加
   Route::post('roleupdate', 'RoleController/updateRole')->name('roleupdate')->token('__roletoken__'); //角色修改
   Route::post('roledelete', 'RoleController/deleteRole')->name('roledelete'); //角色删除
   Route::get('rolemenuslist', 'RoleController/getAllMenus')->name('rolemenuslist'); //菜单选择器数据
   /******************* 角色管理end ********************/

   /******************* 用户管理start ********************/
   Route::get('user', 'UserController/index')->name('user'); //用户管理
   Route::get('userlist', 'UserController/queryList')->name('userlist'); //用户列表
   Route::post('useradd', 'UserController/addUser')->name('useradd')->token('__usertoken__'); //用户添加
   Route::post('userupdate', 'UserController/updateUser')->name('userupdate')->token('__usertoken__'); //用户修改
   Route::post('userdelete', 'UserController/deleteUser')->name('userdelete'); //用户删除
   Route::post('userreload', 'UserController/reloadPassword')->name('userreload'); //重置密码
   /******************* 用户管理end ********************/


    /******************* 通知管理start ********************/
    Route::get('notify', 'NotifyController/index')->name('notify'); //通知管理
    /******************* 通知管理end **********************/


   Route::get('auth', 'AuthController/getMenus')->name('auth'); //获取权限
   Route::post('updatepassword', 'LoginController/updatePassword')->name('updatepassword'); //首页修改密码
})->middleware(AuthPermission::class);

/******************* 公共路由start ********************/
Route::get('clear/cache', 'CommonController/clearCache')->name('clear/cache'); //清理服务端缓存
/******************* 公共路由end ********************/
