<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-09 20:20:14
 * @version $Id$
 */

/***
====笔记部分====
购物车类

分析构造车:
1:你无论在本网站刷新了多少次次页面,或者新增了多少个商品,
都要求你查看购物车时,看到的都是一个一样的结果.

即:你打开A商品刷新,B商品刷新,首页,看到的购物车应该是一样的.

或者说:整站范围内,购物车--是全局有效的!

解决:把购物车的信息放在数据库,也可以放在session/cookie里



2:既然是全局有效,暗示,购物车的实例只能有1个!
不能说在3个页面,买了3个商品,就形成了3个购物车实例,这显然不合理.
解决:单例模式


技术选型: session+单例


功能分析:

判断某个商品是否存在
添加商品
删除商品
修改商品的数量

某商品数量加1
某商品数量减1


查询购物车的商品种类
查询购物车的商品数量
查询购物车里的商品总金额
返回购物里的所有商品

清空购物车

***/
defined('ACC')||exit('Acc Deined');
//session_start();
class CartTool{
	private static $ins = null;
	private $items = array();
	//private $sign = 0;//测单例

	final protected function __construct(){
		//$this->sign = mt_rnad(0,10000);//测单例
	}

	final protected function __clone(){
	}

	//获取实例
	protected static function getIns(){
		if (!self::$ins instanceof self) {
			self::$ins = new self();
		}
	return self::$ins;
	}


	//把购物车的单例对象放到session里
	public static function getCart(){
		if (!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)) {
			$_SESSION['cart'] = self::getIns();
		}
		return $_SESSION['cart'];
	}


	/*
	添加商品
	parm int $id 商品主键
	parm string $name 商品名
	parm float $price 商品价格
	parm int $num 购物数量
	 */

	public function addItem($id,$name,$price,$num=1){
		if ($this->hasItem($id)) {// 如果该商品已经存在,则直接加其数量
			$this->incNum($id,$num);
			return;
		}


		 $item = array();
		 $item['name'] = $name;
		 $item['price'] = $price;
		 $item['num'] = $num;

		 $this->items[$id] = $item;//以商品id为下标
	}


	/*
	修改购物车中的商品数量
	param int $id 商品主键id
	param int  $num 某个商品修改后的数量，即直接把某商品的数量改为$num  
	 */
	public function modNum($id,$num=1){
		if (!$this->hasItem($id)) {
			return false;
		}//是不是有点多此一举?都已经在购物车了，商品还不存在吗？
		$this->items[$id]['num'] = $num;
	}


	/*
	商品数量增加1
	 */
	public function incNum($id,$num=1){
		if ($this->hasItem($id)) {
			$this->items[$id]['num'] += $num;
		}
	}

	/*
	商品数量减少1
	 */
	public function decNum($id,$num=1){
		if ($this->hasItem($id)) {
			$this->items[$id]['num'] -= $num;
		}

		//如果减少后，数量变为0了，则把这个商品从购物车中删掉
		if ($this->items[$id]['num'] < 1) {
			$this->deleItem($id);
		}
	}


	/*
	判断商品是否存在
	 */
	public function hasItem($id){
		return array_key_exists($id,$this->items);
	}

	/*
	删除商品
	 */
	public function deleItem($id){
		unset($this->items[$id]);
	}

	/*
	查询购物车中商品种类
	 */
	public function getCnt(){
		return count($this->items);
	}

	/*
	查询购物车中商品个数
	 */
	public function getNum(){
		if ($this->getCnt() == 0) {
			return 0;
		}
		$sum = 0;
		foreach ($this->items as $item) {
			$sum += $item['num'];
		}
		return $sum;
	}


	/*
	查询购物车中商品总金额
	 */
	public function getPrice(){
		if ($this->getCnt() == 0) {
			return 0;
		}

		$price = 0.0;
		foreach ($this->items as $item) {
			$price += $item['num'] * $item['price'];
		}
		return $price;
	}

	/*
	返回购物车中所有商品
	 */
	public function all(){
		return $this->items;
	}


	/*
	清空购物车
	 */
	public function clear(){
		$this->items = array();//清空存数据的这个数组
	}

}

/*

//print_r(CartTool::getCart());//测单例
$cart = CartTool::getCart();

if(!isset($_GET['test'])){//不让其报没有test时的notice错误
	$_GET['test'] = '';
}


if($_GET['test'] == 'addwangba') {
    $cart->addItem(1,'王八',23.4,1);
    echo 'add wangba ok';
} else if($_GET['test'] == 'addfz') {
    $cart->addItem(2,'方舟',2347.56,1);
    echo 'add fangzhou ok';
} else if($_GET['test'] == 'clear') {
    $cart->clear();
} else if($_GET['test'] == 'show') {
    print_r($cart->all());
    echo '<br />';
    echo '共',$cart->getCnt(),'种',$cart->getNum(),'个商品<br />';
    echo '共',$cart->getPrice(),'元';
} else {
    print_r($cart);
}


*/























?>