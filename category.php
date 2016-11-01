<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-09 10:29:02
 * @version $Id$
 */

define('ACC',true);
require('./include/init.php');
//session_start();在init.php中已引入

/*
这个页面经过分析，栏目id(cat_id)不可少
在接收栏目id之后，应该检验数据，防止被人恶意篡改；
 */

$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;//检验栏目id是否存在，和防恶转化数据

$page = isset($_GET['page'])?$_GET['page']+0:1;//分页参数
if ($page<1) {
	$page=1;
}


$goods = new GoodsModel();
//分页导航
$total = $goods->catGoodsCount($cat_id);

//每页取2条
$perpage = 2;
if($page > ceil($total/$perpage)) {
    $page = 1;
}
$offset = ($page-1)*$perpage;


$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();


$cat = new CatModel();
$category = $cat->find($cat_id);//检验这个栏目id在数据据中是否存在

if (empty($category)) {//如果在数据库中未找到，说明也不合法，则跳转到首页去
	header('location:index.php');
	exit;
}

//取树状导航
$cats = $cat->select();//取出所有栏目
$sort = $cat->getCatTree($cats,1,0);//排序输出


//面色屑导航
$nav = $cat->getParTree($cat_id);//找家谱树


//取出栏目商品

$goodslist = $goods->catGoods($cat_id,$offset,$perpage);



include(ROOT . 'view/front/lanmu.html');


















?>