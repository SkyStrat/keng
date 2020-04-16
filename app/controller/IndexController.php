<?php
namespace app\controller;

use think\App;
use think\facade\View;

class IndexController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
        //搞不懂为什么只能用门面模式来调用assign，使用容器调用assign会报错
        View::assign([
            'username' => $this->app->session->get('user')['username']
        ]);
        return $this->app->view->fetch('index/index');
    }


}
