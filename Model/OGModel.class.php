<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-11 17:49:31
 * @version $Id$
 */


defined('ACC')||exit('Acc Deined');


class OGModel extends Model {
    protected $table = 'bool_ordergoods';
    protected $pk = 'og_id';




    // 把订单的商品写入ordergoods表
    public function addOG($data) {
        if($this->add($data)) {
            $sql = 'update bool_goods set goods_number = goods_number - ' . $data['goods_number'] . ' where goods_id = ' . $data['goods_id'];

            return $this->db->query($sql); // 减少库存
        }

        return false;

    }

}























?>