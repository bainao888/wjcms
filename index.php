<?php
// [ 应用入口文件 ]
header("Content-type: text/html; charset=utf-8");
//提交表单返回保持数据
header('Cache-control: private, must-revalidate');
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.4.0','<='))  die('require PHP >= 5.4.0 !');
//define('APP_DEBUG',true);
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
