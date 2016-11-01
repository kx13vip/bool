<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-20 11:46:00
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');

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


$cat_id = $_POST['cat_id'] + 0;




/*
一个栏目A,不能修改成为A的子孙栏目的子栏目;

思考:如果B是A的后代,则A不能成为B的子栏目,
反之,B是A的后代,则A是B的祖先;

因此,我们为A设定一个新的父栏目时,设为N,我们可以先查N的家谱树,如果有A,则子孙差辈了.
 */

//第三步:实例化Model,并调用相关方法
$cat = new CatModel();
/*
echo '你想修改',$cat_id,'栏目<br />';
echo '想修改它的新栏目为',$data['parent_id'],'<br />';
echo $data['parent_id'],'家谱树是<br />';
*/

//查找新父栏目的家谱树
$trees = $cat->getParTree($data['parent_id']);

//判断自身是否在新父栏目的家谱树里面
$flag = true;
foreach($trees as $v){
	if ($v['cat_id'] == $cat_id) {
		$flag = false;
		break;
	}
}

if (!$flag) {
		echo $cat_id,'是',$data['parent_id'],'的祖先,不能更改!';
		exit;
}


if($cat -> update($data,$cat_id)){
	echo '栏目修改成功!';
	exit;
}else{
	echo '栏目修改失败!';
	exit;
}



?>