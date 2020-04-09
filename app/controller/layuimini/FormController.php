<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/8
 * Time: 10:25
 */

namespace app\controller\layuimini;


use app\controller\Controller;
use think\App;

class FormController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    //普通表单
    public function formIndex()
    {
        return app('view')->fetch('layuimini/form/form');
    }

    //分步表单
    public function formStepIndex()
    {
        return app('view')->fetch('layuimini/form/formstep');
    }
}