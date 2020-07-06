<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1
 * Time: 18:46
 */

namespace app\controller;

use think\App;

class NotifyController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
         return $this->app->view->fetch('notify/index');
    }
}