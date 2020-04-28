<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/9
 * Time: 18:48
 */

namespace app\controller;


use app\model\Permission\{Menus, Role, RoleMenus};
use app\model\User\{Account, User};
use think\App;
use think\Exception;
use think\exception\HttpException;
use think\facade\View;

class RoleController extends Controller
{
    private $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Role();
    }

    public function index()
    {
        $user_info = $this->userInfo;
        $user_info['role_pid'] = $user_info['role']['pid'];
        $user_info['role_name'] = $user_info['role']['role_name'];
        unset($user_info['role']);
        View::assign([
            'user'=>$user_info
        ]);
        return $this->app->view->fetch('role/index');
    }

    public function queryList()
    {
        $roleList = $this->model->select()->toArray();
        if($this->userInfo['role_id'] != 1) {
            $lower_role_id = $this->getLowerRole($roleList);
            $roleList = $this->model->where([['id', 'in', implode(',', $lower_role_id)]])->select()->toArray();
        }
        $role_menus_model = new RoleMenus();
        $menus_model = new Menus();

        foreach($roleList as $key=>&$item) {
            $title = $role_menus_model->withJoin(['menus'], 'left')->field(['GROUP_CONCAT(menus.title) as title', 'GROUP_CONCAT(menus.id) as id'])->where(['role_id'=>$item['id']])->select()->toArray();
            $item['menus_title'] = $title[0]['title'];
            $item['menus_id'] = explode(',', $title[0]['id']);

            //列出对应角色已选择的权限id
            if($title[0]['id']) {
                $where = [
                    ['id', 'in', $title[0]['id']],
                    ['pid', '<>', 0],
                    ['href', '<>', ''],
                ];
                $id = $menus_model->where($where)->field(['GROUP_CONCAT(id) as id'])->select()->toArray();
                $item['choose_menus_list_id'] = explode(',', $id[0]['id']);
            }else {

                $item['choose_menus_list_id'] = $item['menus_id'];
            }
        }
        unset($role_menus_model, $menus_model);

        $this->result['data'] = $roleList;
        return $this->jsonResult();
    }

    public function addRole()
    {
        $data = $this->request->param();
        $role_menus_data = array();

        $data['role_permission'] = json_encode($data['role_permission']);
        $role_menus = $data['role_menus_id'];
        unset($data['role_menus_id']);

        $this->model->startTrans(); //开启事务
        try {
            $id = $this->model->create($data);

            foreach($role_menus as $item) {
                $role_menus_data[] = [
                    'role_id' => $id->id,
                    'menus_id' => $item
                ];
            }
            $role_menus_model = new RoleMenus();
            $result = $role_menus_model->insertAll($role_menus_data);
            if($id && $result) {
                $this->model->commit();
            }else {
                $this->model->rollback();
                $this->result['code'] = -1;
                $this->result['message'] = 'fail';
            }
            unset($role_menus_model); //清除资源
            return $this->jsonResult();
        }catch(Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = $e->getMessage();
            return $this->jsonResult();
        }
    }

    public function updateRole()
    {
        $data = $this->request->param();
        $role_menus_data = array();

        $data['role_permission'] = json_encode($data['role_permission']);
        $role_menus = $data['role_menus_id'];
        unset($data['role_menus_id']);

        $this->model->startTrans(); //开启事务
        try {
            $result1 = $this->model->update($data);

            foreach($role_menus as $item) {
                $role_menus_data[] = [
                    'role_id' => $data['id'],
                    'menus_id' => $item
                ];
            }
            $role_menus_model = new RoleMenus();
            $result2 = $role_menus_model->where(['role_id'=>$data['id']])->delete(); //删除旧的权限
            $result3 = $role_menus_model->insertAll($role_menus_data); //添加新的权限

            if($result1 && $result3) {
                $this->model->commit();
                //判断当前用户角色是否是现在修改的角色
                $userInfo = $this->app->session->get('user');
                if($userInfo['role_id'] == $data['id']) {
                    $userInfo['role']['role_permission'] = $data['role_permission'];
                    $this->app->session->set('user', $userInfo);
                }
            }else {
                $this->model->rollback();
                $this->result['code'] = -1;
                $this->result['message'] = 'fail';
            }
            unset($role_menus_model); //清除资源
            return $this->jsonResult();
        } catch(Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = $e->getMessage();
            return $this->jsonResult();
        }
    }

    public function deleteRole()
    {
        if($this->request->isPost()) {
            $param = $this->request->param();

            $this->model->startTrans(); //开启事务
            try {
                //如果准备删除的这个角色是父角色的话，那下面的子角色全变为父
                $result4 = 1;
                $child_num = $this->model->where(['pid'=>$param['id']])->count();
                if($child_num) {
                    $result4 = $this->model->where(['pid'=>$param['id']])->update(['pid'=>0]);
                }
                $result1 = $this->model->where($param)->delete();

                $monent = 1;
                //判断当前用户角色是否是现在删除的角色
                $userInfo = $this->app->session->get('user');
                if($userInfo['role_id'] == $param['id']) {
                    $user_model = new User();
                    $account_user_model = new Account();
                    $role_menus_model = new RoleMenus();

                    $result2 = $account_user_model->where(['account'=>$userInfo['account']])->update(['role_id'=>0]);
                    $result3 = $user_model->where(['account'=>$userInfo['account']])->update(['role_name'=>'']);
                    $result5 = $role_menus_model->where(['role_id'=>$param['id']])->delete();
                    unset($user_model, $account_user_model, $role_menus_model); //清除资源
                }else {
                    $monent = 0;
                }

                //根据当前用户角色进行不同的返回信息
                if($monent === 0) {
                    if($result4) {
                        $this->model->commit();
                    }else {
                        $this->model->rollback();
                        $this->result['code'] = -1;
                        $this->result['message'] = 'fail';
                    }
                }else {
                    if($result2 && $result3 && $result4) {
                        $this->model->commit();
                        return redirect((string)url('loginout'));
                    }else {
                        $this->model->rollback();
                        $this->result['code'] = -1;
                        $this->result['message'] = 'fail';
                    }
                }
                return $this->jsonResult();
            } catch(Exception $e) {
                $this->model->rollback();
                $this->result['code'] = -1;
                $this->result['message'] = $e->getMessage();
                return $this->jsonResult();
            }
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    /**
     * 树形结构权限数据
     * @return \think\response\Json
     */
    public function getAllMenus()
    {
        $menusData = array();
        $menusList = Menus::where(['status'=>0])->field(['id','pid','title','href'])->select()->toArray();

        foreach($menusList as $item) {
            $menusData[$item['id']] = $item;
        }

        return json(childMenus($menusData, 'children'));
    }

    /**
     * 获取角色及其下级角色id
     */
    private function getLowerRole(array $roleList): array
    {
        $lower_role = [];

        array_push($lower_role, $this->userInfo['role_id']);

        foreach($roleList as $item) {
            foreach($lower_role as $value) {
                if($item['pid'] == $value) {
                    array_push($lower_role, $item['id']);
                }
            }
        }
        array_shift($lower_role); //如果不需要显示当前用户角色，请注释掉

        return $lower_role;
    }
}