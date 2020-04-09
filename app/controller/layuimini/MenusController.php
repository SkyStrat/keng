<?php
namespace app\controller\layuimini;

use think\App;
use app\controller\Controller;

class MenusController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function index()
    {
        return app('view')->fetch('layuimini/menus/menu');
    }
}
