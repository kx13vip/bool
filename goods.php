<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-09 15:19:31
 * @version $Id$
 */
define('ACC',true);
require('./include/init.php');
//检验数据
$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;

$goods = new GoodsModel();
$g = $goods->findOne($goods_id);

if (empty($g)) {
	header('location:index.php');
	exit;
}

$cat = new CatModel();//面包屑导航
$nav = $cat->getParTree($g['cat_id']);

include(ROOT . 'view/front/shangpin.html');
?>