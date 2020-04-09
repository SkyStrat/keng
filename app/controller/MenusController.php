<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/8
 * Time: 12:17
 */

namespace app\controller;

use app\model\Permission\Menus;
use think\App;
use think\exception\HttpException;

class MenusController extends Controller
{
    private $model;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Menus();
    }

    public function index()
    {
        return app('view')->fetch('menu/menu');
    }

    public function queryList()
    {
        $parentList = $this->request->get('parentList', ''); //判断是否只获取类型为菜单的值
        if(empty($parentList)) {
            $id = $this->request->get('id', ''); //获取指定id
            if(empty($id)) {
                $menusList = $this->model->select()->toArray();
            }else {
                $menusList = $this->model->where(['id'=>$id])->find()->toArray();
            }
        }else {
            $menusList = $this->model->where(['is_menu'=>0])->select()->toArray();
        }

        $this->result['data'] = $menusList;
        return $this->jsonResult();
    }

    public function addMenus()
    {
        if($this->request->isPost()) {
            $param = $this->request->param();
            $result = $this->model->save($param);
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = '新增失败 | '.$result;
            }
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    public function updateMenus()
    {
        if($this->request->isPost()) {
            $param = $this->request->param();
            $result = $this->model->update($param);
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = '修改失败 | '.$result;
            }
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }

    public function deleteMenus()
    {
        if($this->request->isPost()) {
            $param = $this->request->param();
            $result = $this->model->where($param)->delete();
            if(!$result) {
                $this->result['code'] = -1;
                $this->result['message'] = '删除失败 | '.$result;
            }
            return $this->jsonResult();
        }else {
            throw new HttpException(400, 'It is not POST request');
        }
    }
}