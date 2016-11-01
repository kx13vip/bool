<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-17 21:49:13
 * @version $Id$
 */
define('ACC' , true);
require('../include/init.php');

//调用Model
$cat = new CatModel();
$catlist = $cat->select();
//print_r($catelist);

$catlist = $cat -> getCatTree($catlist,0);


include(ROOT.'view/admin/templates/catelist.html');

?>