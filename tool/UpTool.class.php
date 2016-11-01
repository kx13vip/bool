<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-27 14:19:48
 * @version $Id$
 */
/*
单文件上传类

多文件上传需扩展
 */

defined('ACC')||exit('ACC Denied');


/*
上传文件

配置允许的文件后缀
配置允许的大小

随机生成目录
随机生成文件名

获取文件后缀名
判断文件的后缀

知识点：良好的报错的支持，

思路：
检验上传有没有成功
获取后缀
检查后缀
检查大小
创建目录
生成随机文件名
移动
Array
(
    [pic] => Array
        (
            [name] => Winter.jpg  // 文件原名
            [type] => image/jpeg  // 文件类型
            [tmp_name] => D:\tmp\php6A2.tmp  // 临时文件路径
            [error] => 0                     // 错误代码 ,0 代表无错
            [size] => 105542                 // 文件的大小,以Byte为单位
        )

)

重要知识点：

创建想要放的文件路径：mkdir(路径，0777，true);
移动文件：move_uploaded_file(老地址,新地址)；
新文件名：随机文件名，截取，拼接
 */


class UpTool{
protected $allowExt = 'jpg,jpeg,gif,bmp,png';//允许上传的后缀的文件6
protected $maxSize = 1;//M为单位，最大上传大小

protected $errno = 0;//错误代码
protected $error = array(
        0=>'无错',
        1=>'上传文件超出系统限制',
        2=>'上传文件大小超出网页表单页面',
        3=>'文件只有部分被上传',
        4=>'没有文件被上传',
        6=>'找不到临时文件夹',
        7=>'文件写入失败',
        8=>'不允许的文件后缀',
        9=>'文件大小超出的类的允许范围',
        10=>'创建目录失败',
        11=>'移动失败'
	);//把健名做成一个变量，然后再业通过键名获取健值信息，达到一个错误信息的传达

/*
string $_key  上传时的字段名
 */


public function up($key){
	if (!isset($_FILES[$key])) {//检验上传是否有错，导致$_FILES[$key]不存在
		return false;
	}
	$f = $_FILES[$key];//$key的详情信息，$f 成为一维数组


	/*
	检验上传有没有成功
	 */
	if ($f['error']) {//等于0直接走了，不等于0往下执行，改变errno的值，返回false
		$this->errno = $f['error'];
		return false;
	}

	//检验文件的大小和后缀，看是不是想要的文件
	//获取后缀
	$ext = $this->getExt($f['name']);

	//检查后缀
	if(!$this->isAllowExt($ext)){
		$this->errno =8;
		return false;
	}

	//检查大小
	if(!$this->isAllowSize($f['size'])){
		$this->errno = 9;
		return false;
	}//上面都是作的检验数据

	//创建目录,放到自己想要放的位置
	$dir = $this->mk_dir();

	if ($dir == false) {
		$this->errno = 10;
		return false;
	}


	//生成随机文件名
	$newName = $this->randName() . '.' . $ext;//生成新的文件名
	$dir = $dir . '/' . $newName;//新的文件路径


	//移动
	if(!move_uploaded_file($f['tmp_name'],$dir)){//要想移动文件，得有新老文件路径
		$this->errno = 11;
		return false;
	}

return str_replace(ROOT,'',$dir);//一个文件的相对地址写入数据库



}

/*
错误获取
 */
public function getErr(){
	return $this->error[$this->errno];
}

/*
设置后缀
parm string $exts 允许的后缀
 */
public function setExt($exts){
	$this->allowExt = $exts;
}

/*
设置上传文件的大小
parm string $num 允许的文件大小
 */
public function setSize($num){
	$this->maxSize = $num;
}


/*
	parm String $file  为$f['name']，为文件的类型单元
	return String $ext 后缀
 */
protected function getExt($file){//取文件的后缀
	$tmp = explode('.',$file);//把字符串变成数组，取后缀；也可以用字符串形式来做
	return end($tmp);//返回$tmp数组中的最后一个值，即文件的后缀名
}


/*
parm string $ext 文件后缀
return bool
防止大小写的问题,用strtolower函数
 */
protected function isAllowExt($ext){
	return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));//这里注意统一大小写来比较
}


/*
检查文件大小
 */
protected function isAllowSize($size){
	return $size <= $this->maxSize * 1024 * 1024;//在$_FILES['pic']['size']的大小是以Byte为单位
}




/*
按日期创建目录的方法
 */


protected function mk_dir(){//mkdir()函数的参数主要是路径,再看是否存在
	$dir = ROOT . 'data/images/' . date('Ym/d');
	if(is_dir($dir) || mkdir($dir,0777,true)){//运用逻辑运算符短路特性
		return $dir;
	}else{
		return false;
	}
}


/*
生成随机文件名
 */

protected function randName($length = 6){
$str = 'abcdefghjkmnpqrstuvwxyz23456789';//这里注意不要把1，l,0,o等不易辨识的写进去
return substr(str_shuffle($str),0,$length);
}



}

































?>