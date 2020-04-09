<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Login extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'acount' => 'require',
        'password' => 'require',
        'captcha' => 'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'account.require' => '帐号不能为空',
        'password.require' => '密码不能为空',
        'captcha.require' => '验证码不能为空'
    ];

    protected $scene = [
      'login' => ['account', 'password', 'captcha']
    ];
}
