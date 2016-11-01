<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-18 14:55:58
 * @version $Id$
 */
define('ACC' , true);
require('../include/init.php');
/*
file cateaddAct.php
作用 接收cateadd.php表单页面发送过来的数据
并调用Model,把数据入库
 */


//第一步:接收数据
/*
print_r($_POST);
*/

//第二步:验证数据
//由于还没有建数据库,所以无法验证其合法性,得先建数据库

$data = array();
if(empty($_POST['cat_name'])){
	exit('栏目不能为空');
}
$data['cat_name'] = $_POST['cat_name'];


if (($_POST['parent_id'] + 0)==='') {//这儿不能用'=='或empty来判断,因为有0的存在
	exit('栏目id不能为空');
}
$data['parent_id'] = $_POST['parent_id'];

if (empty($_POST['intro'])) {
	exit('栏目简介不能为空');
}

$data['intro'] = $_POST['intro'];


//第三步:实例化Model,并调用相关方法
$cat = new CatModel();

if($cat -> add($data)){
	echo '栏目添加成功!';
	exit;
}else{
	echo '栏目添加失败!';
	exit;
}



?>