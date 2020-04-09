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
use think\facade\Log;
use think\facade\Session;

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
        return app('view')->fetch('login/login');
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
        unset($result['password']);

        app('session')->set('user', $result);
        return $this->jsonResult();
    }

    public function loginOut()
    {
        app('session')->delete('user');
        return $this->jsonResult();
    }
}