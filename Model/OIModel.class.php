<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-11 15:23:43
 * @version $Id$
 */
defined('ACC')||exit('Acc Deined');


class OIModel extends Model {
    protected $table = 'bool_orderinfo';
    protected $pk = 'order_id';
    protected $fields = array('order_id','order_sn','user_id','username','zone','address','zipcode','reciver','email','tel','mobile','building','best_time','add_time','order_amount','pay');

    protected $_valid = array(
                            array('reciver',1,'收货人不能为空','require'),
                            array('email',1,'email非法','email'),
                            array('pay',1,'必须先支付方式','in','4,5') //代表在线支付与到付.
    );

    protected $_auto = array(
                            array('add_time','function','time')
                            );


    public function orderSn(){
    	$sn = 'OI' . date('Ymd') . mt_rand(10000,99999);
	$sql = 'select count(*) from ' . $this->table . " where order_sn='" . $sn . "'";
	return $this->db->getOne($sql)?$this->orderSn():$sn;//用了递归
    }

    //撤消订单
    public function invoke($order_id){
    	$this->delete($order_id);//先删掉订单

    	$sql = 'delete from bool_ordergoods where order_id = ' . $order_id;//再删订单对应的商品
    	$this->db->query($sql);
    }


}
?>