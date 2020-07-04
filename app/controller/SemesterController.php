<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/16
 * Time: 11:45
 */

namespace app\controller;


use app\model\Semester\Semester;
use think\App;

class SemesterController extends Controller
{
    private $model;
    private $arr_query_search = array('name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Semester();
    }

    public function index()
    {
        return $this->app->view->fetch('semester/index');
    }

    /**
     * 学期设置列表
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

        $list = $this->model->field("name, FROM_UNIXTIME(start_time,'%Y-%m') startTime, FROM_UNIXTIME(end_time,'%Y-%m') endTime")->where($where)->order(['create_time'])->paginate($limit)->toArray();

        $this->result = array_merge($this->result, $list);
        return $this->jsonResult();
    }

    /**
     * 添加学期
     * @return \think\response\Json
     */
    public function addSemester()
    {
        $data_semester = array(
            'create_account' => $this->userInfo['account'],
            'create_name' => $this->userInfo['username']
        );
        $data_semester = array_merge($this->request->param(), $data_semester);
        $data_semester['start_time'] = strtotime($data_semester['start_time']);
        $data_semester['end_time'] = strtotime($data_semester['end_time']);

        $result = $this->model->create($data_semester);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->model->getLastSql();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 刷新令牌
     * @return string
     */
    private function refreshToken()
    {
        return $this->result['token'] = $this->request->buildToken('__semestertoken__');
    }
}