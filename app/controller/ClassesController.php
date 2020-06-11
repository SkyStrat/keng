<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/10
 * Time: 12:04
 */

namespace app\controller;


use app\model\GradeClass\Classes;
use app\model\GradeClass\Grade;
use think\App;
use think\exception\HttpException;
use think\facade\View;

class ClassesController extends Controller
{
    private $model;
    private $arr_query_search = array('name', 'grade_id');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Classes();
    }

    public function index()
    {
        $grade_model = new Grade();
        $list = $grade_model->getGrade('id,name')->toArray();
        View::assign([
            'grade' => $list
        ]);
        unset($grade_model); //清理资源
        return $this->app->view->fetch('gradeClass/indexClasses');
    }

    /**
     * 班级设置列表
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
     * 添加班级
     * @return \think\response\Json
     */
    public function addClasses()
    {
        $data_classes = array(
            'create_account' => $this->userInfo['account'],
            'create_name' => $this->userInfo['username']
        );
        $data_classes = array_merge($this->request->param(), $data_classes);

        $result = $this->model->create($data_classes);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->model->getLastSql();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 修改班级
     * @return \think\response\Json
     */
    public function updateClasses()
    {
        $data_classes = $this->request->param();

        $result = $this->model->where(['id'=>$data_classes['id']])->update($data_classes);

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 删除班级
     * @return \think\response\Json
     */
    public function deleteClasses()
    {
        if($this->request->isPost()) {
            $data = $this->request->post('id');
            $result = Classes::destroy($data);
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
        return $this->result['token'] = $this->request->buildToken('__classestoken__');
    }
}