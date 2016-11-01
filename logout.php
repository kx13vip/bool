<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-07 20:50:32
 * @version $Id$
 */

define('ACC',true);
require('./include/init.php');

//session_start();在init.php中已引入
session_destroy();

$msg = '退出 成功';

include(ROOT.'./view/front/msg.html');




?>