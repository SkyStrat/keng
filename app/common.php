<?php
//定义全局变量
define('__STATIC__', '/static');

// 应用公共文件
//组合菜单并转化为json
function getMenusJson($menusData)
{
    $menusList = array();
    $menusList = config('menus');
    //这里通过url方法获取路由地址并回调到前端的话，需要强制转换为string，因为url方法返回的并非string值，而是一个Url实例对象
    $menusList['homeInfo']['href'] = (string)url($menusList['homeInfo']['href']);
    $menusList['logoInfo']['href'] = (string)url($menusList['logoInfo']['href']);


    $menusList['menuInfo'] = $menusData;
    return json($menusList);
}

//循环获取多级菜单
function childMenus($data)
{
    $tree = array(); //格式化好的树
    foreach ($data as $key=>$item) {
        if (isset($data[$item['pid']])){
            $data[$item['pid']]['child'][] = &$data[$key];
        } else {
            $tree[] = &$data[$item['id']];
        }
    }
    return $tree;
}
