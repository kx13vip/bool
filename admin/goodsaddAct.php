<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-22 14:29:41
 * @version $Id$
 */

define('ACC' , true);
require('../include/init.php');



//print_r($_POST);

/*
表单数据中:
text类型:如果在表单没有选,也是有字段的[接收数据的数组能看到],只不过是其值为空,而checkbox与radio如果没有选,则不会有字段显示
 */



/*
$data['goods_name'] = trim($_POST['goods_name']);

if ($data['goods_name'] == '') {
	echo '商品名不能为空!';
	exit;
}

$data['goods_sn'] = trim($_POST['goods_sn']);
$data['cat_id'] = $_POST['cat_id'] + 0;
$data['shop_price'] = $_POST['shop_price'] + 0;
$data['market_price'] = $_POST['market_price'];
$data['goods_desc'] = trim($_POST['goods_desc']);
$data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'];
$data['goods_number'] = $_POST['goods_number'] + 0;
$data['is_best'] = isset($_POST['is_best'])?1:0;
$data['is_new'] = isset($_POST['is_new'])?1:0;
$data['is_hot'] = isset($_POST['is_hot'])?1:0;
$data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
$data['goods_brief'] = trim($_POST['goods_brief']);
$data['goods_name'] = trim($_POST['goods_name']);
$data['add_time'] = time();

//print_r($data);
*/
$goods = new GoodsModel();
$_POST['goods_weight']*=$_POST['weight_unit'];


$data = array();
//print_r($_POST);
$data = $goods->_facade($_POST);//自动过滤
$data = $goods->_autoFill($data);//自动填充
//print_r($data);exit;

//自动填充商品货号
if (empty($data['goods_sn'])) {
	$data['goods_sn'] = $goods->createSn();
}


if(!$goods->_validate($data)){
	echo '数据不合法,检测未通过<br />';
	echo '原因:',implode(',',$goods->getErr());
	exit;
}

//上传图片
$uptool = new UpTool();
$ori_img = $uptool->up('ori_img');

if ($ori_img) {
	$data['ori_img'] = $ori_img;
}


/*
如果$ori_img上传成功，再次生成中等大小图片缩略图300*400（也可以css中按比例强行压缩）
根据原始图地址定 中等图的地址
aa.jpeg--->goods_aa.jpeg
 */
if($ori_img){
	$ori_img = ROOT . $ori_img;//加上绝对路径

	$goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);

	if(ImageTool::thumb($ori_img,$goods_img,300,400)){
		$data['goods_img'] = str_replace(ROOT,'',$goods_img);
	}

	    // 再次生成浏览时用缩略图 160*220
	    // 定好缩略图的地址
	    // aa.jpeg --> thumb_aa.jpeg
	$thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);

	if(ImageTool::thumb($ori_img,$thumb_img,160,220)){
		$data['thumb_img'] = str_replace(ROOT,'',$thumb_img);
	}
}

//添加进数据库goods表
if ($goods->add($data)) {
	echo '商品发布成功!';
}else{
	echo '商品发布失败!';
}



























?>