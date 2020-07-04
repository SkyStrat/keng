<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 18:05
 */

namespace app\controller;


use app\model\Course\Course;
use app\model\Course\Schedule;
use app\model\GradeClass\Classes;
use app\model\GradeClass\Grade;
use app\model\Semester\Semester;
use think\App;
use think\exception\HttpException;
use think\facade\View;

class CourseController extends Controller
{
    private $model;
    private $schedule_model;
    private $arr_query_search = array('course_no','name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Course();
        $this->schedule_model = new Schedule();
    }

    /**************************** 课程设置 *********************************/
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

        $list = $this->model->with(['grades'])->where($where)->order(['create_time'])->paginate($limit)->toArray();

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

    /**************************** 快捷排课 *********************************/
    public function index_fast()
    {
        $grade_model = new Grade();
        $grade_list = $grade_model->getGrade()->toArray();
        $classes_model = new Classes();
        $classes_list = $classes_model->getClasses();
        $course_list = $this->model->getCourse();
        $semester_model = new Semester();
        $semester_list = $semester_model->getSemester('*', ['schedule_status'=>0])->toArray();

        View::assign([
            'grade' => $grade_list,
            'classes' => $classes_list,
            'course' => $course_list,
            'semester' => $semester_list
        ]);
        unset($grade_model, $classes_model, $semester_model); //清理资源
        return $this->app->view->fetch('course/indexFast');
    }

    /**
     * 批量添加排课
     * @return \think\response\Json
     * @throws \Exception
     */
    public function addFast()
    {
        $data_course = $this->request->param();
        $grade_arr = explode("-", $data_course['grade']);
        $classes_arr = explode("-", $data_course['classes']);
        $course_arr = explode("-", $data_course['course']);
        $time_arr = explode("-", $data_course['semester']);
        $weekday = $data_course['weekday'];
        $begin_time = $data_course['begin_time'];
        $after_time = $data_course['after_time'];
        unset($data_course['grade'], $data_course['classes'], $data_course['course'], $data_course['semester'], $data_course['weekday'], $data_course['begin_time'], $data_course['after_time']);

        /*  在学期区间内，计算指定某星期保存的数据  */
        $start_time = $time_arr[0];
        $end_time = $time_arr[1];
        $weekday_calc = date('N', $start_time);
        $weeknum = $weekday-$weekday_calc; //计算目标星期与当前星期相差几天
        $data_course = []; //清空一下属性内的数据，以免出现数据异常
        if($weeknum <= 0) {
            if($weeknum < 0) {
                $start_time = strtotime($weeknum." day", strtotime("+1 week", $start_time)); //获取某星期的日期
            }
            //学期区间内，获取到每周的某星期
            for($i = $start_time; $i<=$end_time; $i = strtotime("+1 week", $i)) {
                $data_course[] = [
                    'create_account' => $this->userInfo['account'],
                    'create_name' => $this->userInfo['username'],
                    'grade_id' => $grade_arr[0],
                    'grade_name' => $grade_arr[1],
                    'classes_id' => $classes_arr[0],
                    'classes_name' => $classes_arr[1],
                    'course_id' => $course_arr[0],
                    'course_name' => $course_arr[1],
                    'start_time' => strtotime(date('Y-m-d',$i).' '.$begin_time),
                    'end_time' => strtotime(date('Y-m-d',$i).' '.$after_time)
                ];
            }
        }else {
            $weeknum = '+'.$weeknum;
            //学期区间内，获取到每周的某星期
            for($i = strtotime($weeknum." day", $start_time); $i<=$end_time; $i = strtotime("+1 week", $i)) {
                $data_course[] = [
                    'create_account' => $this->userInfo['account'],
                    'create_name' => $this->userInfo['username'],
                    'grade_id' => $grade_arr[0],
                    'grade_name' => $grade_arr[1],
                    'classes_id' => $classes_arr[0],
                    'classes_name' => $classes_arr[1],
                    'course_id' => $course_arr[0],
                    'course_name' => $course_arr[1],
                    'start_time' => strtotime(date('Y-m-d',$i).' '.$begin_time),
                    'end_time' => strtotime(date('Y-m-d',$i).' '.$after_time)
                ];
            }
        }

        $result = $this->schedule_model->saveAll($data_course);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->schedule_model->getLastSql();
        }

        $this->refreshTokenFast(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 刷新令牌
     * @return string
     */
    private function refreshTokenFast()
    {
        return $this->result['token'] = $this->request->buildToken('__fasttoken__');
    }

    /**************************** 排课表（可视化） *********************************/
    public function index_schedule()
    {
        $course_list = $this->model->getCourse();
        View::assign([
            'course' => $course_list
        ]);
        return $this->app->view->fetch('course/indexSchedule');
    }

    /**
     * 排课列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function queryList_schedule()
    {
        $data_start = strtotime($this->request->get('start'));
        $data_end = strtotime($this->request->get('end'));

        $list = $this->schedule_model->where([['start_time','>',$data_start],['end_time','<',$data_end]])->select()->toArray();
        $result = [];
        foreach ($list as $value) {
            $result[] = [
                'title'=> $value['grade_name'].$value['classes_name']." ".$value['course_name'],
                'start'=> date('Y-m-d H:i:s', $value['start_time']),
                'end'=> date('Y-m-d H:i:s', $value['end_time']),
                'id'=> $value['id'],
                'grade_id'=> $value['grade_id'],
                'course_id'=> $value['course_id'],
                'course_name'=> $value['course_name']
            ];
        }

        return json($result);
    }

    /**
     * 修改排课信息
     * @return \think\response\Json
     */
    public function updateSchedule()
    {
        $data = $this->request->post('course');
        $id = $this->request->post('id');
        $type = $this->request->post("type");

        switch ($type) {
            case "transfer":
                $data_arr = implode('-', $data);
                $data_course['course_id'] = $data_arr[0];
                $data_course['course_name'] = $data_arr[1];
                break;
            case "selfStudy":
                $data_course['course_id'] = -1;
                $data_course['course_name'] = "自修";
                break;
            default :
                $data_course['course_id'] = -1;
                $data_course['course_name'] = "自修";
                break;
        }

        $result = $this->schedule_model->where(['id'=>$id])->update($data_course);

        $this->refreshTokenSchedule(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 刷新令牌
     * @return string
     */
    private function refreshTokenSchedule()
    {
        return $this->result['token'] = $this->request->buildToken('__scheduletoken__');
    }
}