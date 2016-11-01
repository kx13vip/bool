<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-28 14:47:06
 * @version $Id$
 */
define('ACC' , true);
require('include/init.php');
require(ROOT. 'tool/UpTool.class.php');



$uptool = new UpTool();

if($uptool->up('pic0')){
	echo 'OK';
}else{
	echo 'false';
	$uptool->getErr();
}





?>