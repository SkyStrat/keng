<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/13
 * Time: 16:46
 */

namespace app\controller;


use app\model\User\{Account, User};
use think\App;
use think\Exception;
use think\exception\HttpException;

class UserController extends Controller
{
    private $model;
    private $user_info;
    private $arr_curd_where = ['username', 'account'];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new User();
        $this->user_info = $this->app->session->get('user');
    }

    public function index()
    {
        return $this->app->view->fetch('user/index');
    }

    public function queryList()
    {
        $page = $this->request->get('page',1);
        $limit = $this->request->get('limit', 15);

        $data = [];
        foreach($this->request->param() as $key=>$item) {
            if(in_array($key, $this->arr_curd_where)) {
                !$item || $data[$key] = $item;
            }
        }
        $data['r.pid'] =  $this->user_info['role_id'];
        $where = $this->model->buildWhere($data);
        $userList = $this->model->alias('u')->field('u.*')->where($where)->Join('role r', 'u.role_id=r.id')->order('u.create_time', 'desc')->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $userList);
        return $this->jsonResult();
    }

    public function addUser()
    {
        $data_user = $this->request->param();
        $role_arr = explode(',', $data_user['role']);
        $data_account = [
            'account' => $data_user['account'],
            'password' => md5($data_user['password'].$this->loginKey),
            'role_id' => $role_arr[0],
            'username' => $data_user['username']
        ];
        unset($data_user['password'], $data_user['role']);

        $this->model->startTrans(); //开启事务
        try {
            $account = new Account();
            $result1 = $account->create($data_account);

            $data_user['account_id'] = $result1->id;
            $data_user['role_name'] = $role_arr[1];
            $data_user['role_id'] = $role_arr[0];
            $result2 = $this->model->create($data_user);

            if($result1 && $result2) {
                $this->model->commit();
            }
            unset($account); //清除资源
            $this->result['data'] = $this->request->buildToken();
            return $this->jsonResult();
        } catch(Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = 'fail | 数据回滚';
            $this->result['data'] = $this->request->buildToken();
            return $this->jsonResult();
        }

    }

    public function updateUser()
    {
        $data_user = $this->request->param();
        $role_arr = explode(',', $data_user['role']);
        $account_data = $data_user['account'];
        $data_account = [
            'role_id' => $role_arr[0],
            'username' => $data_user['username']
        ];
        unset($data_user['role'], $data_user['account']);
        $this->model->startTrans(); //开启事务
        try {
            $account = new Account();
            $result1 = $account->where(['account'=>$account_data])->update($data_account);

            $data_user['role_name'] = $role_arr[1];
            $data_user['role_id'] = $role_arr[0];
            $result2 = $this->model->where(['id'=>$data_user['id']])->update($data_user);

            if($result2) {
                $this->model->commit();
            }
            unset($account); //清除资源
            $this->result['data'] = $this->request->buildToken();
            return $this->jsonResult();
        } catch(Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = 'fail | 数据回滚';
            $this->result['data'] = $this->request->buildToken();
            return $this->jsonResult();
        }
    }

    public function deleteUser()
    {
        if($this->request->isPost()) {
            $param = $this->request->post('id');
            $result = $this->model->destroy($param); //主键删除适用
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = 'fail | '.$result;
            }
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    public function reloadPassword()
    {
        if($this->request->isPost()) {
            $id = $this->request->post('id');
            $password = $this->request->post('password');

            $account = new Account();
            $result = $account->where(['id',$id])->update(['password',$password]);
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = 'fail | '.$result;
            }
            unset($account);
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }
}