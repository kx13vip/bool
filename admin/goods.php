<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-22 21:47:19
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');


$goods_id = $_GET['goods_id'];
$cat_id = $_GET['cat_id'];



$goods = new GoodsModel();
$goodsrow = $goods->findOne($goods_id);


//print_r($goodsrow);exit;

//栏目
$cat = new CatModel();
$catinfo = $cat->find($cat_id);

$catlist = $cat->select();
$catlist = $cat ->getCatTree($catlist,0);

//print_r($catlist);exit;

include('../view/admin/templates/goods.html');

































?>