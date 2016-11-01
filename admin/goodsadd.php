<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-22 14:16:03
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');


//栏目
$cat = new CatModel();

$catlist = $cat->select();
$catlist = $cat ->getCatTree($catlist,0);

//print_r($catlist);exit;

include('../view/admin/templates/goodsadd.html');













































?>