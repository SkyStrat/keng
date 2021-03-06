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

   /******************* 学生基本信息管理start ********************/
   Route::get('student', 'StudentController/index')->name('student'); //学生基本信息管理
   Route::get('studentlist', 'StudentController/queryList')->name('studentlist'); //学生列表
   Route::post('studentadd', 'StudentController/addStudent')->name('studentadd')->token('__studenttoken__'); //学生添加
   Route::post('studentupdate', 'StudentController/updateStudent')->name('studentupdate')->token('__studenttoken__'); //学生修改
   Route::post('studentdelete', 'StudentController/deleteStudent')->name('studentdelete'); //学生删除
   Route::post('studentstatus', 'StudentController/statusStudent')->name('studentstatus'); //学生状态修改
   /******************* 学生基本信息管理end ********************/

   /******************* 老师基本信息管理start ********************/
    Route::get('teacher', 'TeacherController/index')->name('teacher'); //老师基本信息管理
    Route::get('teacherlist', 'TeacherController/queryList')->name('teacherlist'); //老师列表
    Route::post('teacheradd', 'TeacherController/addTeacher')->name('teacheradd')->token('__teachertoken__'); //老师添加
    Route::post('teacherupdate', 'TeacherController/updateTeacher')->name('teacherupdate')->token('__teachertoken__'); //老师修改
    Route::post('teacherdelete', 'TeacherController/deleteTeacher')->name('teacherdelete'); //老师删除
   /******************* 老师基本信息管理end ********************/

   /******************* 课程设置start ********************/
    Route::get('course', 'CourseController/index')->name('course'); //课程设置
    Route::get('courselist', 'CourseController/queryList')->name('courselist'); //课程设置列表
    Route::post('courseadd', 'CourseController/addCourse')->name('courseadd')->token('__coursetoken__'); //添加课程
    Route::post('courseupdate', 'CourseController/updateCourse')->name('courseupdate')->token('__coursetoken__'); //修改课程
    Route::post('coursedelete', 'CourseController/deleteCourse')->name('coursedelete'); //删除课程
   /******************* 课程设置end ********************/

   /******************* 年级设置start ********************/
    Route::get('grade', 'GradeController/index')->name('grade'); //年级设置
    Route::get('gradelist', 'GradeController/queryList')->name('gradelist'); //年级设置列表
    Route::post('gradeadd', 'GradeController/addGrade')->name('gradeadd')->token('__gradetoken__'); //添加年级
    Route::post('gradeupdate', 'GradeController/updateGrade')->name('gradeupdate')->token('__gradetoken__'); //修改年级
    Route::post('gradedelete', 'GradeController/deleteGrade')->name('gradedelete'); //删除年级
   /******************* 年级设置end ********************/

   /******************* 班级设置start ********************/
    Route::get('classes', 'ClassesController/index')->name('classes'); //班级设置
    Route::get('classeslist', 'ClassesController/queryList')->name('classeslist'); //班级设置列表
    Route::post('classesadd', 'ClassesController/addClasses')->name('classesadd')->token('__classestoken__'); //添加班级
    Route::post('classesupdate', 'ClassesController/updateClasses')->name('classesupdate')->token('__classestoken__'); //修改班级
    Route::post('classesdelete', 'ClassesController/deleteClasses')->name('classesdelete'); //删除班级
   /******************* 班级设置end ********************/

   /******************* 学期设置start ********************/
    Route::get('semester', 'SemesterController/index')->name('semester'); //学期设置
    Route::get('semesterlist', 'SemesterController/queryList')->name('semesterlist'); //学期设置
    Route::post('semesteradd', 'SemesterController/addSemester')->name('semesteradd')->token('__semestertoken__'); //添加学期
   /******************* 学期设置end ********************/

   /******************* 快捷排课设置start ********************/
    Route::get('fast', 'CourseController/index_fast')->name('fast'); //排课管理
    Route::post('fastadd', 'CourseController/addFast')->name('fastadd')->token('__fasttoken__'); //批量添加排课
   /******************* 快捷排课设置end ********************/

   /******************* 排课管理start ********************/
    Route::get('schedule', 'CourseController/index_schedule')->name('schedule'); //排课管理
    Route::get('schedulelist', 'CourseController/queryList_schedule')->name('schedulelist'); //排课管理
    Route::post('scheduleupdate', 'CourseController/updateSchedule')->name('scheduleupdate')->token('__scheduletoken__'); //排课修改
   /******************* 排课管理end ********************/

   Route::get('auth', 'AuthController/getMenus')->name('auth'); //获取权限
   Route::post('updatepassword', 'LoginController/updatePassword')->name('updatepassword'); //首页修改密码
})->middleware(AuthPermission::class);

/******************* 公共路由start ********************/
Route::get('clear/cache', 'CommonController/clearCache')->name('clear/cache'); //清理服务端缓存
/******************* 公共路由end ********************/
