<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/9
 * Time: 20:03
 */

namespace app\controller;


use app\model\GradeClass\Grade;
use think\App;
use think\exception\HttpException;

class GradeController extends Controller
{
    private $model;
    private $arr_query_search = array('name');

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Grade();
    }

    public function index()
    {
        return $this->app->view->fetch('gradeClass/index');
    }

    /**
     * 年级设置列表
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
     * 添加年级
     * @return \think\response\Json
     */
    public function addGrade()
    {
        $data_grade = array(
            'create_account' => $this->userInfo['account'],
            'create_name' => $this->userInfo['username']
        );
        $data_grade = array_merge($this->request->param(), $data_grade);

        $result = $this->model->create($data_grade);
        if(!$result) {
            $this->result['code'] = -1;
            $this->result['message'] = '添加失败:'.$this->model->getLastSql();
        }

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 修改年级
     * @return \think\response\Json
     */
    public function updateGrade()
    {
        $data_grade = $this->request->param();

        $result = $this->model->where(['id'=>$data_grade['id']])->update($data_grade);

        $this->refreshToken(); //刷新令牌
        return $this->jsonResult();
    }

    /**
     * 删除年级
     * @return \think\response\Json
     */
    public function deleteGrade()
    {
        if($this->request->isPost()) {
            $data = $this->request->post('id');
            $result = Grade::destroy($data);
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
        return $this->result['token'] = $this->request->buildToken('__gradetoken__');
    }
}