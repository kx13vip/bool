<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-09 15:55:47
 * @version $Id$
 */

/*
file db.class.php
数据库类

目前到底采用什么数据库还不清楚,用抽象来统一管理
 */
defined('ACC')||exit('ACC Denied');

abstract class db{
	/*
	连接服务器
	parms $h 服务器地址
	parms $u 用户名
	parms $p 密码
	return bool
	 */
public abstract function connect($h,$u,$p);

/*
发送查询
parms $sql 发送的sql语句
return mixed/bool/resource
 */
public abstract function query($sql);

/*
查询多行数据
parms $sql select 型语句
return array/bool
 */
public abstract function getAll($sql);

/*
查询单行数据
parms $sql select 型语句
return array/bool
 */
public abstract function getRow($sql);

/*
查询单个数据
parms $sql select 型语句
return array/bool
 */
public abstract function getOne($sql);

    /*
    自动执行insert/update语句
    parms $sql select型语句
    return array/bool

    $this->autoExecute('user',array('username'=>'zhansan','email'=>'zhang@163.com'),'insert');
    将发生 自动形成 insert into user (username,email) values ('zhangsan','zhang@163.com');

    */

public abstract function autoExecute($table,$arr,$mode='insert',$where='');

}




































?>