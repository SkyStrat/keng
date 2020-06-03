<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/9
 * Time: 18:00
 */

namespace app\controller;

use think\App;

class CommonController extends Controller
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function clearCache()
    {
        $this->app->cache->clear();
        $this->result['msg'] = '服务端清理缓存成功';
        $this->result['code'] = 1;
        return $this->jsonResult();
    }
}