jun项目
===============

> 运行环境要求PHP7.1+ nginx linux mysql5.6+。

## 项目注意点

项目部署/更新后，需要执行以下命令，保证对应的数据库表结构与composer组件包是最新的状态
~~~
php think migrate:run
composer install
~~~

项目菜单分为两个地方进行存储，一个是动态菜单（数据库内），一个是静态菜单（config/menus.php配置文件内）

## 项目扩展包

请把项目扩展包放置根目录下的extend目录内进行调用
