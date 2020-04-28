<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22
 * Time: 15:10
 */

namespace app\controller;


use app\model\Student;
use think\App;

class StudentController extends Controller
{
    private $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Student();
    }

    public function index()
    {
        return $this->app->view->fetch('student/index');
    }

    public function queryList()
    {
        $page = $this->request->get('page',1);
        $limit = $this->request->get('limit', 15);

        $list = $this->model->where(['responsible_account', $this->userInfo['account']])->order(['create_time'])->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $list);
        return $this->jsonResult();
    }
}