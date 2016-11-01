<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-07 19:16:10
 * @version $Id$
 */
define('ACC',true);
require('./include/init.php');

/*
if ($_POST['act'] == 'act_login') {//不够来谨，会报notice错误
	*/
if (isset($_POST['act'])) {//为了不再多写一个页面，而做个标记通过if来判断
	//这说明是占击了登陆按钮过来的
	//收用户名和密码
	$u = $_POST['username'];
	$p = $_POST['passwd'];

	//合法性检测，没有写，调用前面写的方法来验证，可以直接不让空密码不让登陆，免得后面讨论这个情况


	$user = new UserModel();
	//核对用户名和密码
	$row = $user->checkUser($u,$p);
	if (empty($row)) {
		$msg = '用户名密码不匹配';
	}else if($row == 1){//checkUser()写的只有用户名也能通过检验
		$msg = '密码不能为空,请输入密码';
	}else{
		$msg = '登陆成功！';
		//session_start();在init.php中已引入
		$_SESSION = $row;

		//记住用户名2周
		if (isset($_POST['remember'])) {
			setcookie('remuser',$u,time()+14*24*3600);
		}else{
			setcookie('remuser','',0);
		}
		
	}
	include(ROOT . 'view/front/msg.html');
	exit;
}else{//$_COOKIE['remuser']赋给一个变量，以免在不存在时，报notice错误
	$remuser = isset($_COOKIE['remuser'])?$_COOKIE['remuser']:'';
	include(ROOT . 'view/front/denglu.html');
}












?>