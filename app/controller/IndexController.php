<?php
namespace app\controller;

use think\App;

class IndexController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
        return $this->app->view->fetch('index/index');
    }


}
