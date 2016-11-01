<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-04 15:39:09
 * @version $Id$
 */
defined('ACC') || exit('Acc Deined');


class UserModel extends Model{
	protected $table = 'bool_user';
	protected $pk = 'user_id';
	protected $fields = array('user_id','username','email','passwd','regtime','lastlogin');

	//自动检验规则
	protected $_valid = array(

		/*
		array('username',1,'用户名必须在4－16字符内','length','4,16'),
		这样写也可以，但在model检验是得必段是三个参数，而下面的有是是‘1’没有第4个
		参数，就会造成Notice: Undefined offset: 4错误
			Model类中检验代码：
				if (!$this->check($data[$v[0]],$v[3],$v[4])) {
				$this->error[] = $v[2];
				return false;
			}
			所以换成如下办法
		 */
		array('username',1,'用户名必须存在','require'),
		array('username',0,'用户名必须在4－16字符内','length','4,16'),
		array('email',1,'email非法','email'),
		array('passwd',1,'passwd不能为空','require')
		);

	//自动填充规则
	protected $_auto = array(
									array('regtime','function' , 'time')
		);

	//用户注册加密密码
	public function reg($data){
		if($data['passwd']){
			$data['passwd'] = $this->encPasswd($data['passwd']);
		}
		return $this->add($data);
	}

	protected function encPasswd($p){
		return md5($p);
	}



	//根据用户名查询用户信息
	public function checkUser($username,$passwd=''){
		if ($passwd=='') {
		$sql = 'select count(*) from '. $this->table . " where username='".$username."'";
		return $this->db->getOne($sql);//密码为空是返回一个“真”回去，不就通过验证了吗？这儿不应该让它验证过
		}else{
			$sql = "select user_id,username,email,passwd from " . $this->table . " where username='".$username."'";

			$row = $this->db->getRow($sql);
			if (empty($row)) {
				return false;
			}
			if ($row['passwd'] != $this->encPasswd($passwd)) {
				return false;
			}
		}
		unset($row['passwd']);
		return $row;

	}
}


























?>