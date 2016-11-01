<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-23 21:00:03
 * @version $Id$
 */
define('ACC' , true);
require('../include/init.php');


if (isset()) {
	# code...
}


$goods_id = $_GET['goods_id'];

$goods = new GoodsModel();

if($goods->delete($goods_id)){
	echo '已彻底删除';
}else{
	echo '未成功删除';
}

































?>