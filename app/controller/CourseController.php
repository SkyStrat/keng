<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 18:05
 */

namespace app\controller;


use app\model\Course\Course;
use app\model\GradeClass\Grade;
use think\App;
use think\exception\HttpException;
use think\facade\View;

class CourseController extends Controller
{
    private $model;
    private $arr_query_search = array('course_no','name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Course();
    }


    public function index()
    {
        $grade_model = new Grade();
        $list = $grade_model->getGrade('id,name')->toArray();
        View::assign([
            'grade' => $list
        ]);
        unset($grade_model); //清理资源
        return $this->app->view->fetch('course/index');
    }

    /**
     * 课程设置列表
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

        $list = $this->model->where($where)->order(['create_time'])->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $list);
        return $this->jsonResult();
    }

    /**
     * 添加课程
     * @return \think\response\Json
     */
    public function addCourse()
    {
        $data_course = array(
            'course_no' => 'KC'.date('Ymd').substr(explode(' ', microtime())[0],-4),
            'create_account' => $this->userInfo['account'],
            'create_name' => $this->userInfo['username']
        );
        $data_course = array_merge($this->request->param(), $data_course);

        $result = $this->model->create($data_course);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->model->getLastSql();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 修改课程
     * @return \think\response\Json
     */
    public function updateCourse()
    {
        $data_course = $this->request->param();

        $result = $this->model->where(['id'=>$data_course['id']])->update($data_course);

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 删除课程
     * @return \think\response\Json
     */
    public function deleteCourse()
    {
        if($this->request->isPost()) {
            $data = $this->request->post('id');
            $result = Course::destroy($data); //软删除操作
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = 'fail | '.$this->model->getLastSql();
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
        return $this->result['token'] = $this->request->buildToken('__coursetoken__');
    }
}