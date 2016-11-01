<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-08-11 10:54:11
 * @version $Id$
 */

/*
==========================142-框架改进版之增加日志功能======================

file log.class.php 
作用 记录信息到日志
 */
/*
思路:
给定内容,写入文件(fopen,fwrite..)
如果文件大于>1M,重新写一份



传给我一个内容
    判断当前日志的大小
        如果>1M,备份
        否则,写入
*/

defined('ACC')||exit('ACC Denied');

class Log{
	const LOGFILE = 'curr.log';//建 一个常量,代表日志文件的名称
	//写日志
	public static function write($cont){
		$cont .="\r\n";//换行

		//判断是否备份
		$log = self::isBak();// 计算出日志文件的地址
		$fh = fopen($log,'ab');
		fwrite($fh,$cont);
		fclose($fh);
	}

	//备份日志
	public static function bak(){
		//就是把原来的日志文件改个名,存储起来
		//改成年-月-日.bak这种形式
		$log = ROOT. 'data/log/'.self::LOGFILE;
		$bak = ROOT. 'data/log/'.date('ymd').mt_rand(10000,99999).'.bak';
		return rename($log,$bak);
	}



	//读取并判断日志大小
	public static function isBak(){
		$log = ROOT.'data/log/'.self::LOGFILE;
		if (!file_exists($log)) {//如果文件不存在,则创建该文件
			touch($log);//touch()在linux也有此命令,是快速的建立一个文件
			return $log;
		}
		//要是存在,则判断大小
		clearstatcache($log);//请除缓存，filesize()函数是有缓存的
		$size = filesize($log);
		if ($size <= 1024*1024) {//1M = 1024*1024Byte
			return $log;
		}
		//走到这说明>1M
		if (!self::bak()) {
			return $log;//备份失败
		}else{
			touch($log);
			return $log;
		}
	}
}


/*
对于易改动的，尽量用动态调用，方便维护修改；如const LOGFILE = curr.log

在日志写入前得判断日志的大小，如果大于1M那么得备份
 */


































?>