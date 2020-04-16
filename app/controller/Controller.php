<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1
 * Time: 16:39
 */

namespace app\controller;


use app\BaseController;
use app\model\Log\AdminLog;
use think\App;

class Controller extends BaseController
{
    private $log;
    //归纳不需要添加日志的控制器
    private $notLogOfController = array('LoginController', 'WelcomeController', 'CommonController');

    public $userInfo;

    public $result = [
        'code' => 0,
        'message' => 'success',
        'data' => ''
    ];
    public $loginKey = '+jun@#email';

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->userInfo = $this->userInfo ? $this->userInfo : app('session')->get('user');
    }

    public function jsonResult()
    {
        $controller = $this->request->controller();
        $action = $this->request->action();
        if(!in_array($controller, $this->notLogOfController)) {
            AdminLog::addLog([
                'controller'=>$controller,
                'action'=>$action,
                'message'=>$this->result['message'],
                'admin_user_name'=>app('session')->get('user')['username'] ?? '',
                'admin_user_id'=>app('session')->get('user')['id'] ?? 0
            ]);
        }
        return json($this->result);
    }
}