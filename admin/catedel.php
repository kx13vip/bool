<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-19 13:48:54
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');


/*
思路:
接收cat_id

调用Model

删除cat_id

 */



$cat_id = $_GET['cat_id'] + 0;
/*
判断该栏目是是否有子目录,
如果有子栏目,则该栏目不允许删除

我们可以在model写一个方法,专门查子栏目.
调用一下,并判断
 */



$cat = new CatModel();

/*
防止在删栏目时,有子栏目被删掉
 */
$sons = $cat->getson($cat_id);
if (!empty($sons)) {
	exit('有子栏目,不允许删除!');
}

/*
没有子栏目时,执行删除
 */
if($cat -> delete($cat_id)){
	echo '删除成功!';
}else{
	echo '删除失败!';
}











































?>