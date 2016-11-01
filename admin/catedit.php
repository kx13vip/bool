<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-19 20:43:36
 * @version $Id$
 */

/*
catedit.php
作用:编辑栏目

思路:
接收cat_id

实例化Model,调用Model
取出栏目信息
展示到表单
 */
define('ACC' , true);
require('../include/init.php');

$cat_id = $_GET['cat_id'] + 0;

$cat = new CatModel();

$catinfo = $cat->find($cat_id);
//print_r($catinfo);exit;



$catlist = $cat->select();
$catlist = $cat ->getCatTree($catlist,0);



include(ROOT.'view/admin/templates/catedit.html');

?>