<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-09 17:53:34
 * @version $Id$
 */

/*
file conf.class.php
配置文件读写类
 */
defined('ACC')||exit('ACC Denied');
class conf{
	protected static $ins = null;
	protected $data = array();
	final protected function __construct(){
		include(ROOT.'include/config.inc.php');
		$this -> data = $_CFG;//把配置信息数据存入$data
	}

	final protected function __clone(){//防止clone,导致数据被读取；final加在方法前，此方法不能被覆盖；如果加在类前，则不能被继承
	}

	public static function getIns(){//开启单例模式，保证每次的数据一致性
		if (self::$ins instanceof self) {
			return self::$ins;//静态变量调用self需要代$符号的，$this调用则为不需要,其实一个道理，这个是调的类
		}else{
			self::$ins = new self();
			return self::$ins;
		}
	}


	//通过魔术方法,读取data内的信息
	public function __get($key){//读取不可访问属性或不存在的值时，__get() 会被调用。 
		if (array_key_exists($key,$this -> data)) {//这里注意得判断下，存在或不存在
			return $this -> data[$key];
		}else{
			return null;
		}
	}


	//用魔术方法,在运行期,动态增加或改变配置选项
	public function __set($key,$value){
		$this -> data[$key] = $value;//改变配置不用返回值
	}
}


/*

$conf = conf::getIns();

// 已经能把配置文件的信息,读取到自身的 data属性中存储起来
print_r($conf);

// 读取选项
echo $conf->host,'<br />';//localhost;触动魔术方法__get()来执行的
echo $conf->user,'<br />';//1111;

// 动态的追加选项
$conf->template_dir = 'D:/www/smarty';

echo $conf->template_dir;//这个属性不存在,触动魔术方法__set()执行;

 */



/*

构造函数读取配置信息，存储在一个自定义数组中，以免老是调用；保证构造函数无法更改
再写一个单例
用魔术方法写一个__set(),__get(),来读取data数组中的数据，和动态更改配置选项
 */


?>