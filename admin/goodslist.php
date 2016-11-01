<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-22 20:26:20
 * @version $Id$
 */
define('ACC',true);
require('../include/init.php');

$goods = new GoodsModel();
$goodslist = $goods->getGoods();
//print_r($goods);exit;




include('../view/admin/templates/goodslist.html');



?>