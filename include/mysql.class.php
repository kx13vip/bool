<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-14 09:15:44
 * @version $Id$
 *
 测试用:
include('./conf.class.php');
include('./db.class.php');
require('./log.class.php');
 */

//=========================152-框架准备r4之测试mysql类=====================


defined('ACC')||exit('ACC Denied');


class mysql extends db {
	private static $ins =NULL;
	private $conn =NULL;
	private $conf=array();//数组跟对象？？

	protected function __construct(){
		$this ->conf = conf::getIns();//在实际运用过程中会有引用文件，自动加载
		$this ->connect($this->conf->host,$this->conf->user,$this->conf ->pwd);//连接数据据
		$this ->select_db($this ->conf->db);//初始化选择数据库
		$this ->setChar($this->conf->char);//初始化数据库编码
	}


public static function getIns(){//单例，保证每次调用的是同一个对象
	if (!self::$ins instanceof self) {
		self::$ins = new self();
	}
	return self::$ins;
}

//连接数据据
public function connect($h,$u,$p){
	$this->conn = mysqli_connect($h,$u,$p);
	if (!$this->conn) {//错误报警信息，外面必须有接收的才有效果
		$err = new Exception('连接失败');
		throw $err;
	}
}
//选择数据库
protected function select_db($db){
	$sql = 'use '.$db;
	$this -> query($sql);
}
//设置编码
protected function setChar($char){
	$sql = 'set names '.$char;
	$this -> query($sql);
}


//执行sql语句类
public function query($sql){
	$rs = mysqli_query($this ->conn,$sql);
	log::write($sql);
	return $rs;//返回一个资源型
}

public function getAll($sql){
	$rs = $this -> query($sql);
	$list = array();
	while($row = mysqli_fetch_assoc($rs)){//$row是一个关联数组
		$list[] = $row;
	}
	return $list;//这是一个二维数组了
}

public function getRow($sql){
	$rs = $this -> query($sql);
	return mysqli_fetch_assoc($rs);//返回的是一个 行的 关联数组
}


public function getOne($sql){//取一行的某个列
	$rs = $this -> query($sql);
	$row = mysqli_fetch_row($rs);//返回的是一个枚举数组，可以取一行的某个列或几个列的值；
	return $row[0];
}

	//插入或更新数据库数据
    public function autoExecute($table,$arr,$mode='insert',$where = ' where 1 limit 1') {//用的是数组往数据库写入
        /*    insert into tbname (username,passwd,email) values ('',)
        /// 把所有的键名用','接起来
        // implode(',',array_keys($arr));
        // implode("','",array_values($arr));
        //$arr下标对应数据库字段
        */
        
        if(!is_array($arr)) {
            return false;
        }

        if($mode == 'update') {
            $sql = 'update ' . $table .' set ';
            foreach($arr as $k=>$v) {
                $sql .= $k . "='" . $v ."',";//这个循环注意，.=的优先级是向右的,先循环完再赋值给$sql.
            }
            $sql = rtrim($sql,',');
            $sql .= $where;
            
            return $this->query($sql);//关键在于把数组拼接出想要的字符串
        }

        $sql = 'insert into ' . $table . ' (' . implode(',',array_keys($arr)) . ')';//列字段不用加冒号
        $sql .= ' values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';

        return $this->query($sql);
    
    }

    // 返回影响行数的函数
    public function affected_rows() {//比如操作数据库需要返回影响的行数
        return mysqli_affected_rows($this->conn);
    }

    // 返回最新的auto_increment列的自增长的值
    public function insert_id() {
        return mysqli_insert_id($this->conn);//比如获取最新的产品id号
    }

}


/*
写mysql类，有两样东西绕不开就是query,和各种方式的拼$sql语句，和常用的几个函数
像一些必用的在析构函数就用上
 */







?>