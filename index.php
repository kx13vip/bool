<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-07 20:36:09
 * @version $Id$
 */

define('ACC',true);
require('./include/init.php');

//session_start();在init.php中已引入

//取现5条新品
$goods = new GoodsModel();
$newlist = $goods->getNew(5);


/*
取出指定栏目商品
$cat_id = $_GET['cat_id']
$sql = select  * from goods where cat_id = $cat_id;
这是错的

因为cat_id对应的栏目可能是个大栏目，而大栏目下面没有商品
商品放在大栏目下面的小栏目下面。
因此，正确的方法是找出所有$cat_id的子孙栏目，
然后再查所有$cat_id及其子孙栏目下的商品
 */

//女士栏目下的商品
$female = 4;
$felist = $goods->catGoods($female);




//男士栏目下的商品
$man = 1;
$manlist = $goods->catGoods($man);

include(ROOT . 'view/front/index.html');














?>