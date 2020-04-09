<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/7
 * Time: 19:00
 */

namespace app\controller\layuimini;



use app\controller\Controller;
use think\App;

class SystemController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
        return app('view')->fetch('layuimini/setting/setting');
    }
}