<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-12 09:16:08
 * @version $Id$
 */


/*
分页类：
共5条商品，每页显示2条

问：共有几页？
答：3页，页数是整的

问：第一页显示第几条到第几条
答：1－2条

问：第二页显示第几条到第几条
答：3－4条

总结：
分页原理的3个变量！

总条数	$total
每页条数		$perpage
当前页	$page

分页原理的2个公式
总页数$cnt = ceil($total/$perpage);//相除，向上取整

第$page页，显示第几条到第几条？
答：第$page页，说明前面已经跳过$page-1页，每页又是$perpage条
跳过了（$page-1）*$perpage条
即从第($page-1)*$perpage+1条开始取，取$perpage条出来


分页导航：200-分页类及在商城中的应用 27分钟
分页导航的生成
例：
c.php
c.php?cat_id=3
c.php?cat_id=3&page=1
c.php?page=1

分页导航里
[1][2]3[4][5]
page导航里，应根据页码来生成，但同时不能把其他参数搞丢，如cat_id


所以，先把地址栏的获取并保存起来
 */
defined('ACC')||exit('Acc Deined');
class PageTool{
	protected $tatal = 0;
	protected $perpage = 10;
	protected $page =5;

	public function __construct($total,$page=false,$perpage=false){//这个构造函数从外面引入数据，不能是protected,只有是public 
		$this->total = $total;
		if ($perpage) {//这儿如果不传则使用上面的默认的，所以得判断是否存在
			$this->perpage = $perpage;
		}
		if ($page) {
			$this->page = $page;
		}
	}

	//创建分布导航
	public function show(){
		$cnt = ceil($this->total/$this->perpage);//得到总页数

		$uri = $_SERVER['REQUEST_URI'];
		$parse = parse_url($uri);//把url分解了；Array ( [path] => /bool/tool/test01.php [query] => uid=5&pid=3 ) 

		$param = array();
		if (isset($parse['query'])) {//注意判断一下；
			parse_str($parse['query'],$param);//把后缀分拆建成一个数据Array ( [uid] => 5 [pid] => 3 ) 
		}
		
		//不管$param数组里，有没有page单元，都unset一下，确保没有$page单元
		//即保存除page之外的所有单元
		unset($param['page']);//上面所做的就是为了删除$page项


		$url = $parse['path'] . '?';
		if (!empty($param)) {
			$param = http_build_query($param);//是parse_str逆反过程，把数据组变成网址后缀形式
			$url = $url . $param . '&';//加&是为后面的链接拼接服务
		}

		//关键问题，计算页码导航,上面所做就是为了得到有去掉page的url
		$nav = array();
		$nav[0] = '<span class="page_now">' . $this->page . '</span>';//当前位置

		for ($left = $this->page-1,$right = $this->page+1;($left>=1||$right<=$cnt)&&count($nav)<5;) { //($left>=1||$right<=$cnt)&&这个条件不要也可以
			if ($left>=1) {//如果不加这个条件，当$left＝0时，就还能继续走下去，因为上面的条件是“｜｜”，只要一个满足就能过
				array_unshift($nav,'<a href="' . $url . 'page=' . $left . '">[' . $left . ']</a>');//插入下标会从新排序，数组开头插入
				$left -= 1;
			}
			if ($right<=$cnt) {
				array_push($nav,'<a href="' . $url . 'page=' . $right . '">[' . $right . ']</a>');//插入下标会从新排序
				$right += 1;
			}
		}
		return implode('',$nav);//返回一个字符串，写入view层
	}
}

/*
//new pagetool(总条数,当前页,每页条数);
//show() 返回分页代码.
$page = isset($_GET['page'])?$_GET['page']+0:1;
$p = new PageTool(20,$page,6);
echo $p->show();
*/


























?>