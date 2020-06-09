<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/8
 * Time: 11:21
 */

namespace app\controller;


use app\model\Teacher\Teacher;
use think\App;
use think\exception\HttpException;

class TeacherController extends Controller
{
    private $model;
    private $arr_query_search = array('teacher_no','name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Teacher();
    }

    public function index()
    {
        return $this->app->view->fetch('teacher/index');
    }

    /**
     * 教师列表
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function queryList()
    {
        $page = $this->request->get('page',1);
        $limit = $this->request->get('limit', 15);

        $data = [];
        foreach ($this->request->param() as $key=>$value) {
            if(in_array($key, $this->arr_query_search)) {
                !$value || $data[$key] = $value;
            }
        }
        $where = $this->model->buildWhere($data);

        $list = $this->model->field('*,date(birthday) birthday_date')->where($where)->order(['create_time'])->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $list);
        return $this->jsonResult();
    }

    /**
     * 添加教师
     * @return \think\response\Json
     */
    public function addTeacher()
    {
        $data_teacher = array(
            'teacher_no'=>'JS'.date('YmdHis').substr(explode(' ', microtime())[0],-4),
            'create_account'=>$this->userInfo['account'],
            'create_name'=>$this->userInfo['username']
        );
        $data_teacher = array_merge($this->request->param(), $data_teacher);
        $result = $this->model->create($data_teacher);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->model->getLastSql();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 修改教师
     * @return \think\response\Json
     */
    public function updateTeacher()
    {
        $data_teacher = $this->request->param();

        $result = $this->model->where(['id'=>$data_teacher['id']])->update($data_teacher);

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 软删除教师
     * @return \think\response\Json
     */
    public function deleteTeacher()
    {
        if($this->request->isPost()) {
            $data = $this->request->post('id');
            $result = Teacher::destroy($data); //软删除操作
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = 'fail | '.$result;
            }
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    /**
     * 刷新令牌
     * @return string
     */
    private function refreshToken()
    {
        return $this->result['token'] = $this->request->buildToken('__teachertoken__');
    }
}