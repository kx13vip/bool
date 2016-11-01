<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-23 19:04:53
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');

	//$goods=new GoodsModel();//因在列表中,没有得到goods_id,而报 Notice: Undefined index: goods_id错误
	$goods=new GoodsModel();

if (isset($_GET['act']) && $_GET['act'] == 'show') {
/*
调用getTrash方法;
读取is_delete为1的出来
 */
	//$goods=new GoodsModel();//
	$goodslist = $goods->getTrash();//这是里注意与goodslist.html变量保持一致

	include('../view/admin/templates/goodstrashlist.html');
}else if (isset($_GET['act']) && $_GET['act'] == 'del') {
	/*
调用delete方法;
彻底从回收站删除
 */
	$goods_id = $_GET['goods_id'];
	//$goods = new GoodsModel();

	if($goods->delete($goods_id)){
		echo '已彻底删除';
	}else{
		echo '未成功删除';
	}
}else if (isset($_GET['act']) && $_GET['act'] == 'revoke') {
/*
调用trashrovoke方法
 */
	$goods_id = $_GET['goods_id'];
	//$goods = new GoodsModel();

	if($goods->trashrevoke($goods_id)){
		echo '放回商品表成功';
	}else{
		echo '放回商品表失败';
	}
}else{
/*
接收goods_id,
调用trash方法
 */
		$goods_id = $_GET['goods_id'];
		//$goods = new GoodsModel();
		if($goods->trash($goods_id)){
			echo '已放入回收站成功!';
		}else{
			echo '放入回收站失败!';
		}
}




?>