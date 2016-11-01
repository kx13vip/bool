<?php
header("content-type:text/html;charset=utf-8");
/**
 * @authors Your Name (you@example.org)
 * @date    2016-08-09 14:55:37
 * @version $Id$
 */


/*
file inti.php 
作用:框架初始化;
 */



//初始化当前的绝对路径

//echo __FILE__;
//echo __DIR__;//要求版本较高;


//defined('ACC')||exit('ACC Denied');//防止恶意查看源文件;controler全部要define,非controler全部要检验

define('ROOT',str_replace('\\' , '/' , dirname(dirname(__FILE__))) . '/');
define('DEBUG', true);
/*
require(ROOT.'include/conf.class.php');
require(ROOT.'include/db.class.php');
require(ROOT.'Model/Model.class.php');
require(ROOT.'Model/TestModel.class.php');
require(ROOT.'include/mysql.class.php');
require(ROOT.'include/log.class.php');
*/
require(ROOT.'include/lib_base.php');

//自动加载文件
function __autoload($class){
	if(strtolower(substr($class,-5)) == 'model') {
		require(ROOT.'Model/' . $class . '.class.php');
	}else if (strtolower(substr($class,-4)) == 'tool') {
		require(ROOT.'tool/' . $class . '.class.php');
	}else{
		require(ROOT . 'include/' . $class . '.class.php');
	}
}



//过滤参数,用递归的方式;$_GET,$_POST,$_COOKE
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);

//开启session
session_start();


//设置报错级别
if (defined('DEBUG')) {
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}
























?>