<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-16 17:54:00
 * @version $Id$
 */
defined('ACC')||exit('ACC Denied');

class Model{
	protected $table = NULL;
	protected $db = NULL;

	protected $pk = NULL;
	protected $fields = array();//表的字段名
	protected $_auto = NULL;//自动填充

	protected $_valid = array();
	protected $error = array();//自动验证错误信息

	public function __construct(){
		$this ->db = mysql::getIns();
	}

	public function table($table){
		$this->table = $table;
	}//使table可以用任何表名了,起到一个公用的作用


/*
		负责把传来的数组,清除掉不用的单元,留下与表字段对应的单元 

		思路:循环数组,分别判断其key,是否是表的字段;
		当然,要先有表的字段

		表的字段可以用desc 表名来分析出
		也可以手动写好
		以TP为例,两者都行

		在开发中,尽量用desc 表名来自动获取,因为开发时字段时有变化,比较方便.但自动获取比较耗费资源;所以在稳定运行过程中,
		以手动写为好;
 */

//过滤字段，把不是需要的字段过滤掉
	public function _facade($array=array()){
		$data = array();
		foreach ($array as $k => $v) {
			if (in_array($k,$this->fields)) {//判断$k是否是表的字段名;
				$data[$k] = $v;
			}
		}
		return $data;
	}


/*
自动填充
负责把表中需要的值,而$_POST又没有传的值,赋上值
比如,$_POST没有add_time,即商品时间
则自动把time()的返回值赋过来
 */

	public function _autoFill($data){
		foreach ($this->_auto as $k=>$v){
			if (!array_key_exists($v[0],$data)) {
				switch ($v[1]) {
					case 'value':
						$data[$v[0]] = $v[2];
						break;

					case 'function':
						$data[$v[0]] = call_user_func($v[2]);
						break;
				}
			}
		}
		return $data;
	}

    /*
        格式 $this->_valid = array(
                    array('验证的字段名',0/1/2(验证场景),'报错提示','require/in(某几种情况)/between(范围)/length(某个范围)','参数')
        );

        array('goods_name',1,'必须有商品名','requird'),
        array('cat_id',1,'栏目id必须是整型值','number'),
        array('is_new',0,'in_new只能是0或1','in','0,1')
        array('goods_breif',2,'商品简介就在10到100字符','length','10,100')

 		0 存在字段就验证 （默认）
		1 必须验证
		2 值不为空的时候验证

    */

public function _validate($data){
	if (empty($this->_valid)) {
		return true;
	}

	$this->error = array();

	foreach($this->_valid as $k=>$v){//专门检查出错的,验证数据未通过的作用;
		switch($v[1]){
		case 1:
			if(!isset($data[$v[0]])) {
				$this -> error[] = $v[2];
				return false;
			}

			if (!isset($v[4])) {
				$v[4] = '';
			}
			if (!$this->check($data[$v[0]],$v[3],$v[4])) {
				$this->error[] = $v[2];
				return false;
			}
			break;
		case 0:
			if (isset($data[$v[0]])) {

				if (!$this->check($data[$v[0]],$v[3],$v[4])) {
					$this->error[] = $v[2];
					return false;
				}
			}
			break;
		case 2:
			if (isset($data[$v[0]]) && !empty($data[$v[0]])) {
				if(!$this->check($data[$v[0]],$v[3],$v[4])){
					$this->error[] = $v[2];
					return false;
				}
			}
		}
	}
	return true;
}


protected function check($value,$rule='',$parm=''){
	switch($rule){
		case 'require':
			return !empty($value);
		case 'number':
			return is_numeric($value);

		case 'in':
			$tmp = explode(',',$parm);
			return in_array($value,$tmp);

		case 'between':
			list($min,$max) = explode(',',$parm);
			return $value >= $min && $valude <= $max;

		case 'length':
			list($min,$max) = explode(',',$parm);
			return strlen($value) >= $min && strlen($value) <= $max;

		case 'email':
			//判断$value是否是email，可以用正则表达式，但现在没学
			//因此，用系统函数来判断
			return (filter_var($value,FILTER_VALIDATE_EMAIL) !==false);//加个!==false，更严谨点，不加也可以

		default:
			return false;
	}
}


public function getErr(){
	return $this->error;
}


/*
在Model的父类里,写最基本的增删改查操作
 */

/*
增
pram array $data
return boolea
 */

public function add($data){
	return $this->db->autoExecute($this->table,$data);
}



/*
删
parm int $id 主键
return int 影响的行数
 */


public function delete($id){
	$sql = 'delete from '.$this->table.' where '.$this->pk.'='.$id;
	if($this -> db -> query($sql)){
	return $this->db->affected_rows();
	}else{
		return false;
	}
}



/*
改
parm array() $data
parm int $id
return 影响的行数
 */


public function update($data,$id){
	$rs = $this->db->autoExecute($this->table,$data,'update',' where '.$this->pk.'='.$id);
	if($rs){
		return $this->db->affected_rows();
	}else{
		return false;
	}
}




/*
查
return array,一个二维数组
 */

public function select(){
	$sql = 'select * from '.$this->table;
	return $this->db->getAll($sql);
}



/*
查找一行数据
parm $id
return array()
 */

public function findOne($id){
	$sql= 'select * from '.$this->table.' where '.$this->pk.'='.$id;
	return $this->db->getRow($sql);
}



/*
获取最新的AUTO_INCREMENT 的 ID 号
 */
public function insert_id(){
	return $this->db->insert_id();
}






}






?>