<?php
/****
燕十八 公益PHP讲堂

论  坛: http://www.zixue.it
微  博: http://weibo.com/Yshiba
YY频道: 88354001
****/
header("content-type:text/html;charset=utf-8");

// 在线支付的返回信息接收页面
//回执验证，跟支付过程的验证一个原理



$md5key = '#(%#WU)(UFGDKJGNDFG';

// 计算出md5info
$md5info = md5($_POST['v_oid'] . $_POST['v_pstatus'] . $_POST['v_amount'] . $_POST['v_moneytype'] . $md5key);
$md5info = strtoupper($md5info);


// 再拿计算出的md5info和表单发来的md5info对比

if($md5info !== $_POST['v_md5str']) {
    echo '你想出老千!';
    exit;
}


echo '执行sql语句,把订单号',$_POST['v_oid'];
echo '对应的订单改为已支付';

/*
最后再连接数据库，改订单状态信息，改为已支付
 */

