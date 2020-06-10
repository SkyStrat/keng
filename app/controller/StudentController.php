<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/22
 * Time: 15:10
 */

namespace app\controller;


use app\model\Student\{Parents,Student,StudentOld};
use app\model\User\User;
use think\App;
use think\exception\HttpException;
use think\facade\View;

class StudentController extends Controller
{
    private $model;
    private $arr_filed_pop = array('parent_name', 'parent_qq', 'parent_phone', 'parent_relation', 'parent_remark', 'parent_wx', 'parent_id');
    private $arr_query_search = array('student_no','name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Student();
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $user_model = new User();
        $info = $user_model->getTeacherRoleUser('account,username');
        View::assign([
            'teacher' => $info
        ]);
        unset($user_model); //清理资源
        return $this->app->view->fetch('student/index');
    }

    /**
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

        $list = $this->model->with(['parents'])->where(function($query) {
            $query->whereOr('responsible_account',$this->userInfo['account'])->whereOr('responsible_account','');
        })->where($where)->where([['status','in','0,1']])->order(['create_time'])->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $list);
        return $this->jsonResult();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addStudent()
    {
        $data_student = $this->request->param();
        $student_no = $this->model->getStudentNo($data_student['gradeClass'],$data_student['class']);
        $data_student['student_no'] = isset($student_no['student_no']) ? $student_no['student_no']++ : 1;
        $data_student['create_account'] = $this->userInfo['account'];
        $data_student['create_name'] = $this->userInfo['username'];

        //分割学生数据与家长数据
        foreach ($data_student as $key=>$value) {
            if(in_array($key, $this->arr_filed_pop)) {
                $data_parent[$key] = $value;
                unset($data_student[$key]);
            }
        }

        $this->model->startTrans(); //开启事务
        try {
            $result = $this->model->create($data_student);

            $data_parent_key = array_keys($data_parent['parent_name']); //获取数组的键名
            //不保证数组键名一定是按顺序的，所以要根据数据键名来循环获取数据
            foreach ($data_parent_key as $value) {
                $parent[] = [
                    'student_id' => $result->id,
                    'name' => $data_parent['parent_name'][$value] ?: '',
                    'relation_child' => $data_parent['parent_relation'][$value] ?: 0,
                    'phone' => $data_parent['parent_phone'][$value] ?: '',
                    'wechat' => $data_parent['parent_wx'][$value] ?: '',
                    'QQ' => $data_parent['parent_qq'][$value] ?: '',
                    'remark' => $data_parent['parent_remark'][$value] ?: ''
                ];
            }
            $parent_model = new Parents();
            $parent_model->exists(false);
            $result2 = $parent_model->saveAll($parent);

            if($result && $result2) {
                $this->model->commit();
            }else {
                $this->model->rollback();
                $this->result['code'] = -1;
                $this->result['message'] = '添加失败:'.$this->model->getLastSql().'----'.$parent_model->getLastSql();
            }
            unset($parent_model); //清除资源
        }catch (\Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = 'code:'.$e->getCode().'----message:'.$e->getMessage();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateStudent()
    {
        $data_student = $this->request->param();

        //分割学生数据与家长数据
        foreach ($data_student as $key=>$value) {
            if(in_array($key, $this->arr_filed_pop)) {
                $data_parent[$key] = $value;
                unset($data_student[$key]);
            }
        }

        $parent_model = new Parents();
        $delete_parent = [];
        $add_parent = [];
        $update_parent = [];
        $parent_info = $parent_model->where(['student_id'=>$data_student['id']])->select()->toArray();

        //获取需要删除的家庭成员id
        foreach ($parent_info as $value) {
            if(!in_array($value['id'],$data_parent['parent_id'])) {
                $delete_parent[] = $value['id'];
            }
        }
        count($delete_parent) == 0 ?: Parents::destroy($delete_parent); //删除其中家庭成员信息

        $arr_parent_id = array_column($parent_info, 'id');
        $add_parent = array_diff($data_parent['parent_id'], $arr_parent_id); //获取需要添加的新家庭成员信息
        $update_parent = array_intersect($data_parent['parent_id'], $arr_parent_id); //获取需要修改的新家庭成员信息

        $this->model->startTrans(); //开启事务
        try {
            $result1 = $this->model->where(['id'=>$data_student['id']])->update($data_student); //修改学生信息

            $result2 = 1;
            $result3 = 1;
            $parent_data_add = [];
            $parent_data_update = [];
            foreach ($data_parent['parent_id'] as $key=>$value) {
                if(in_array($value, $add_parent)) {
                    //添加家庭成员数组
                    $parent_data_add[] = [
                        'student_id' => $data_student['id'],
                        'name' => $data_parent['parent_name'][$key] ?: '',
                        'relation_child' => $data_parent['parent_relation'][$key] ?: 0,
                        'phone' => $data_parent['parent_phone'][$key] ?: '',
                        'wechat' => $data_parent['parent_wx'][$key] ?: '',
                        'QQ' => $data_parent['parent_qq'][$key] ?: '',
                        'remark' => $data_parent['parent_remark'][$key] ?: ''
                    ];
                }else if(in_array($value, $update_parent)) {
                    //修改家庭成员数组
                    $parent_data_update[] = [
                        'id' => $value,
                        'name' => $data_parent['parent_name'][$key] ?: '',
                        'relation_child' => $data_parent['parent_relation'][$key] ?: 0,
                        'phone' => $data_parent['parent_phone'][$key] ?: '',
                        'wechat' => $data_parent['parent_wx'][$key] ?: '',
                        'QQ' => $data_parent['parent_qq'][$key] ?: '',
                        'remark' => $data_parent['parent_remark'][$key] ?: ''
                    ];
                }
            }

            if(count($parent_data_add) > 0) {
                $parent_model->exists(false);
                $result2 = $parent_model->saveAll($parent_data_add);
            }
            if(count($parent_data_update) > 0) {
                $parent_model->exists(true);
                $result3 = $parent_model->saveAll($parent_data_update);
            }

            if($result1>=0 && $result2 && $result3) {
                $this->model->commit();
            }else {
                $this->model->rollback();
                $this->result['code'] = -1;
                $this->result['message'] = '修改失败:'.$this->model->getLastSql().'-----'.$parent_model->getLastSql();
            }
            unset($parent_model); //清除资源
        }catch (\Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = 'code:'.$e->getCode().'----message:'.$e->getMessage();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteStudent()
    {
        if($this->request->isPost()) {
            $param = $this->request->post('id');
            $this->transfer($param, 'deleted'); //转移操作

            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function statusStudent()
    {
        if($this->request->isPost()) {
            $param_id = $this->request->post('id');
            $param_type = $this->request->post('type');
            $is_str = !is_array($param_id) ?: implode(',', $param_id);

            if($param_type == 'studying') {
                $param_teacher = $this->request->post('teacher_info');

                $teacher_arr = explode('-',$param_teacher);
                $result = $this->model->where([['id','in',$is_str]])->update(['responsible_account'=>$teacher_arr[0], 'responsible_name'=>$teacher_arr[1], 'status'=>1]);
            }else {
                $this->transfer($param_id, $param_type); //转移操作
            }

            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    /**
     * 把被删除，毕业，转学的学生信息转移到历史记录表里
     * @param $param array|string 学生id
     * @param $type string 类型
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function transfer($param,$type)
    {
        $id_str = !is_array($param) ?: implode(',',$param);
        $student_info = $this->model->field('id,name,sex,age,gradeClass,class,address,home_phone,remark,responsible_account,responsible_name')->where([['id','in',$id_str]])->select()->toArray();

        $this->model->startTrans(); //开启事务
        try {
            $result = $this->model->destroy($param); //主键删除适用

            foreach ($student_info as $value) {
                $id = $value['id'];unset($value['id']); //去除id
                $value['status'] = $type == 'deleted' ? 3 : $type == 'graduation' ? 1 : 2;
                $value['create_account'] = $this->userInfo['account'];
                $value['create_name'] =$this->userInfo['username'];
                $result2 = StudentOld::create($value); //数据移到历史学生表

                $parent_data['student_id_old'] = $result2->id;
                $result3 = Parents::where(['student_id'=>$id])->update($parent_data); //更新家长表id
            }

            if($result && $result2 && $result3>=0) {
                $this->model->commit();
            }
        }catch (\Exception $e) {
            $this->model->rollback();
            $this->result['code'] = -1;
            $this->result['message'] = 'code:'.$e->getCode().'-----message:'.$e->getMessage();
        }
    }

    /**
     * @return string
     */
    private function refreshToken()
    {
        return $this->result['token'] = $this->request->buildToken('__studenttoken__');
    }
}