<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-22 16:35:19
 * @version $Id$
 */

defined('ACC') || exit('ACC Denied');


/*

 */

class GoodsModel extends Model{
	protected $table = 'bool_goods';
	protected $pk = 'goods_id';

	protected $fields = array('goods_id','goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time','last_update');

	protected $_auto = array(
									array('is_hot' , 'value' , 0),
									array('is_best' , 'value' ,0),
									array('is_new' , 'value' ,0),
									array('add_time','function' , 'time')
		);
/*
0 存在字段就验证 （默认）
1 必须验证
2 值不为空的时候验证
 */
    protected $_valid = array(
                            array('goods_name',1,'必须有商品名','require'),
                            array('cat_id',1,'栏目id必须是整型值','number'),
                            array('is_new',0,'is_new只能是0或1','in','0,1'),
                            array('goods_brief',2,'商品简介就在10到100字符','length','10,100')
    );






/*
作用:把商品放到回收站,即is_delete字段为1
parm int id
return bool
 */
	public function trash($id){
		return $this->update(array('is_delete' =>1) , $id);
	}
/*
作用:把商品撤回到商品表,即is_delete字段为0
parm int id
return bool
 */

	public function trashrevoke($id){
		return $this->update(array('is_delete' =>0) , $id);
	}



public function getGoods(){
	$sql = 'select * from bool_goods where is_delete=0';
	return $this->db->getAll($sql);
}


public function getTrash(){
	$sql = 'select * from bool_goods where is_delete=1';
	return $this->db->getAll($sql);
}



/*
创建商品的货号
 */
public function createSn(){
	$sn = 'BL' . date('Ymd') . mt_rand(10000,99999);
	$sql = 'select count(*) from ' . $this->table . " where goods_sn='" . $sn . "'";
	return $this->db->getOne($sql)?$this->createSn():$sn;//用了递归
}


/*
取出指定条数的新品
 */

public function getNew(){
	$sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from ' . $this->table . ' order by add_time limit 5';

	return $this->db->getAll($sql);
}

/*
取出指定栏目的商品

$cat_id = $_GET['cat_id']
$sql = select  * from goods where cat_id = $cat_id;
这是错的

因为cat_id对应的栏目可能是个大栏目，而大栏目下面没有商品
商品放在大栏目下面的小栏目下面。
因此，正确的方法是找出所有$cat_id的子孙栏目，
然后再查所有$cat_id及其子孙栏目下的商品
 */
public function catGoods($cat_id,$offset=0,$limit=5){
	$category = new CatModel();
	$cats = $category->select();//取出所有的栏目来
	$sons = $category->getCatTree($cats,'',$cat_id);//取出指定栏目的子栏目
	
	$sub = array($cat_id);//直接加进去是为了防止没有子栏目情况
	if (!empty($sons)) {//不为空有子栏目,取出
		foreach ($sons as $v) {
			$sub[] = $v['cat_id'];
		}
	}

	$in = implode(',',$sub);
	$sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from ' . $this->table . ' where cat_id in (' . $in . ')order by add_time limit ' . $offset . ',' . $limit;
	return $this->db->getAll($sql);

}
/*
分页所取该栏目下的商品数
 */


    public function catGoodsCount($cat_id) {
	$category = new CatModel();
	$cats = $category->select();//取出所有的栏目来
	$sons = $category->getCatTree($cats,'',$cat_id);//取出指定栏目的子栏目
	
	$sub = array($cat_id);//直接加进去是为了防止没有子栏目情况
	if (!empty($sons)) {//不为空有子栏目,取出
		foreach ($sons as $v) {
			$sub[] = $v['cat_id'];
		}
	}

        $in = implode(',',$sub);

        $sql = 'select count(*) from bool_goods where cat_id in (' . $in . ')';
        return $this->db->getOne($sql);
    }


/*
获取购物车中商品详细信息
params array $item 购物车中的商品数组
return 商品数组中的详细信息
 */
public function getCartGoods($items){
	/*
	//把购物车中的商品id都集中到$ids数组中：方法一
	$ids = array();
	foreach($items as $k=>$v){
		$ids[] = $k;//把购物车中的商品id都集中到$ids数组中,注意$k就是id
	}
	//-----------------------------------------------------------------
	//把购物车中的商品id都集中到$ids数组中：方法二
	$ids = array_keys($items);//把购物车中的商品id都集中到$ids数组中

	$sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' . $this->table . ' where goods_id in (' . implode(',',$ids) . ')';
	return $this->db->getAll($sql);
	//此方法运用中把购物车中的每件商品数量丢了，所以不合适
	*/

	foreach($items as $k=>$v){
		$sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' . $this->table . ' where goods_id=' . $k;
		$row = $this->db->getRow($sql);//用购物车的商品id一条一条的取出数据，然后插入购物车数据数组中$items

		$items[$k]['thumb_img'] = $row['thumb_img'];//插入购物车数据数组中$items
		$items[$k]['market_price'] = $row['market_price'];
	}
	return $items;
}








}



































?>