<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/8
 * Time: 10:16
 */

namespace app\controller\layuimini;


use app\controller\Controller;
use think\App;

class TableController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
        return app('view')->fetch('layuimini/table/table');
    }
}