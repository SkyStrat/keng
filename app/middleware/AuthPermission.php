<?php
declare (strict_types = 1);

namespace app\middleware;


use think\exception\HttpException;

class AuthPermission
{
    protected $permissionRoutes = [];

    //不需要权限控制的路由别名
    protected $notPermission = ['/','auth','welcome','clear/cache','updatepassword'];

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        //判断是否超级管理员
        if($this->isAdmin()) {
            return $response;
        }

        //获取当前路由别名进行判断当前用户是否有访问权限
        $routelist = $this->getPermissionRoutes();
        if($routelist === false) {
            return redirect((string)url('loginhtml'));
        }

        $ruleName = $request->rule()->getName();
        //无需控制权限的路由别名
        if(in_array($ruleName, $this->notPermission)) {
            return $response;
        }
        if(in_array($ruleName, $routelist)) {
            return $response;
        }

        throw new HttpException(401, 'No Access');
    }

    protected function isAdmin()
    {
        $userInfo = app('session')->get('user');
        if($userInfo['role_id'] === 1) {
            return true;
        }
        return false;
    }

    protected function getPermissionRoutes()
    {
        if($this->permissionRoutes) return $this->permissionRoutes;

        $userInfo = app('session')->get('user');
        if(!$userInfo || $userInfo['role_id'] === 0) {
            return false;
        }

        $this->permissionRoutes = $permission = json_decode($userInfo['role']['role_permission'], true);
        return $permission;
    }
}
