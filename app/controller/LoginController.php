<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/31
 * Time: 19:05
 */

namespace app\controller;


use app\model\User\Account;
use think\App;
use think\exception\HttpException;

class LoginController extends Controller
{
    private $account;
    private $arr_select_field = array('account');


    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->account = new Account();
    }

    public function loginHtml()
    {
        return $this->app->view->fetch('login/login');
    }

    public function query()
    {
        $data = array();
        $password = $this->request->post('password');
        $captcha = $this->request->post('captcha');
        if(!captcha_check($captcha)) {
            $this->result['code'] = -1;
            $this->result['message'] = '验证码错误';
            return $this->jsonResult();
        }

        foreach($this->arr_select_field as $field) {
            !strlen($this->request->post($field)) || $data[$field] = $this->request->post($field);
        }

        $result = Account::with(['role' => function($query) {
            $query->field(['id', 'role_name', 'role_permission']);
        }])->field(['account', 'role_id', 'username','password'])->where($data)->find();

        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '帐号错误';
            return $this->jsonResult();
        }
        if($result['password'] != md5($password.$this->loginKey)) {
            $this->result['code'] = -1;
            $this->result['message'] = '密码错误';
            return $this->jsonResult();
        }
        if($result['role_id'] == 0) {
            $this->result['code'] = -1;
            $this->result['message'] = '该用户没赋予权限';
            return $this->jsonResult();
        }
        unset($result['password']);

        app('session')->set('user', $result);
        return $this->jsonResult();
    }

    public function loginOut()
    {
        app('session')->delete('user');
        return redirect((string)url('loginhtml'));
    }

    public function updatePassword()
    {
        if($this->request->isPost()) {
            $old = $this->request->post('old_password');
            $new = $this->request->post('new_password');

            $user_info = $this->app->session->get('user');
            $password = $this->account->field(['password'])->where(['account', $user_info['account']])->find();
            if(md5($old.$this->loginKey) === $password) {
                $data['password'] = md5($new.$this->loginKey);
                $result = $this->account->where(['account', $user_info['account']])->update($data);
                if(!$result) {
                    $this->result['code'] = -1;
                    $this->result['message'] = "fail | 修改失败";
                }
            }else {
                $this->result['code'] = -1;
                $this->result['message'] = "fail | 原密码错误";
            }

            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }
}