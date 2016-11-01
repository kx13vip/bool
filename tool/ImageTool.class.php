<?php
header("content-type:text/html;charset=utf-8");
/**
 * 最后一个月,2016.9.9号前弄完商城项目;PHP入门就算结束!Come On Go!
 * @authors Your Name (you@example.org)
 * @date    2016-09-03 15:18:05
 * @version $Id$
 */

/***
想操作图片
先得把图片的大小,类型信息得到

水印:就是把指定的水印复制到目标上,并加透明效果

缩略图:就是把大图片复制到小尺寸画面上


getimagesize()输出图
Array
(
    [0] => 775//宽
    [1] => 710//高
    [2] => 1//图片类型代码
    [3] => width="775" height="710"
    [bits] => 8
    [channels] => 3
    [mime] => image/gif
)
知识点：
getimagesize()获取图片信息
imagecopymerge()水印函数
imagecopyresampled()缩略图函数


***/
defined('ACC')||exit('Acc Deined');
class ImageTool{
	public static function Info($image){//图片路径
		if(!file_exists($image)){
			return false;
		}

		$info = getimagesize($image);//获取图片信息

		if ($info == false) {
			return false;
		}
		$img['width'] = $info[0];
		$img['height'] = $info[1];
		$img['ext'] =substr($info['mime'], strpos($info['mime'],'/')+1);

		return $img;//把用的图片信息写到一个数组中
	}


	/*
	做水印，把小图压到大图上并做透明度就是水印
	bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x ,
	 int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )
	parm $dst  string 待操作源图片，文件路径
	parm $water string 水印图片，文件路径
	parm $pos int 水印位置，0－左上角，1－右上角，2－右下角，3－左下角
	parm $save string 图片保存位置，如果$save未定义，则覆盖原图片
	parm $alpha int 水印图片的透明度

	 */
	public static function image($dst,$water,$save=NULL,$pos=2,$alpha=50){
		//保证两个文件的存在
		if(!file_exists($dst) || !file_exists($water)){//这里判断了，就不用调用info再来判断
			return false;
		}

		$dinfo = self::Info($dst);
		$winfo = self::Info($water);
		//验证水印图片水于待操作图片
		if($winfo['width'] > $dinfo['width'] || $winfo['height'] > $dinfo['height']){
			return false;
		}


		$dfunc = 'imagecreatefrom'.$dinfo['ext'];//动态函数名
		$wfunc = 'imagecreatefrom'.$winfo['ext'];

		if (!function_exists($dfunc) || !function_exists($wfunc)) {//判断函数名是否已定义,
			return false;
		}
		//造图片画布
		$dim = $dfunc($dst);//待操作画布
		$wim = $wfunc($water);//水印画布

		//通过水印位置找坐标
		switch($pos){
			case 0://左上角
				$posx = 0;
				$posy = 0;
			break;

			case 1://右上角
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = 0;
			break;

			case 3://左下角
				$posx = 0;
				$posy = $dinfo['height'] - $winfo['height'];
			break;

			default:
				$posx = $dinfo['width'] - $winfo['width'];
				$posy = $dinfo['height'] - $winfo['height'];
			break;
		}
		//打水印
		imagecopymerge($dim,$wim,$posx,$posy,0,0,$winfo['width'],$winfo['height'],$alpha);
		//如果$save不存在，则删除原图，再生成一个
		if (!$save) {
			$save = $dst;//这只是一个路径加文件名
			unlink($dst);//删除哪个文件
		}

		$saveimage = 'image'.$dinfo['ext'];//输出动态函数
		//保存
		$saveimage($dim,$save);

		//销毁
		imagedestroy($dim);
		imagedestroy($wim);

		return true;
	}




	/*
	缩略图方法
	bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y ,
	 int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	 */
	public static function thumb($src,$save=NULL,$width=200,$height=200){
		$sinfo = self::Info($src);
		if ($sinfo == false) {
			return false;
		}

		
		$sfunc = 'imagecreatefrom'.$sinfo['ext'];//原画动态函数名
		if (!function_exists($sfunc)) {
			return false;
		}
		//造原画画布
		$sim = $sfunc($src);

		//造缩略图画布
		$tim = imagecreatetruecolor($width,$height);

		//造缩略图颜料
		$gray = imagecolorallocate($tim,255,255,255);

		//填充缩略图画布
		imagefill($tim,0,0,$gray);

		//计算缩略比例
		$calc = min($width/$sinfo['width'],$height/$sinfo['height']);

		//缩略图的实际宽高
		$twidth = (int)$sinfo['width']*$calc;
		$theight = (int)$sinfo['height']*$calc;

		//缩略图在缩略画布上的定位点
		$paddingx = (int)($width - $twidth)/2;
		$paddingy = (int)($height - $theight)/2;



		//缩略操作
		imagecopyresampled($tim,$sim,$paddingx,$paddingy,0,0,$twidth,$theight,$sinfo['width'],$sinfo['height']);


		//输出保存
		if (!$save) {
			$save = $src;
			unlink($src);
		}

		$saveimage = 'image'.$sinfo['ext'];//这个后缀是$src的，还是$tim的？？压缩格式是否保持不变？？
		$saveimage($tim,$save);

		//销毁
		imagedestroy($sim);
		imagedestroy($tim);

		return true;
	}


	//写验证码

    public static function captcha($width=50,$height=25) {
            //造画布
            $image = imagecreatetruecolor($width,$height) ;
           
            //造背影色
            $gray = imagecolorallocate($image, 200, 200, 200);
           
            //填充背景
            imagefill($image, 0, 0, $gray);
           
            //造随机字体颜色
            $color = imagecolorallocate($image, mt_rand(0, 125), mt_rand(0, 125), mt_rand(0, 125)) ;
            //造随机线条颜色
            $color1 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color2 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
            $color3 =imagecolorallocate($image, mt_rand(100, 125), mt_rand(100, 125), mt_rand(100, 125));
           
            //在画布上画线
            imageline($image, mt_rand(0, 50), mt_rand(0, 25), mt_rand(0, 50), mt_rand(0, 25), $color1) ;
            imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color2) ;
            imageline($image, mt_rand(0, 50), mt_rand(0, 20), mt_rand(0, 50), mt_rand(0, 20), $color3) ;
           
            //在画布上写字
            $text = substr(str_shuffle('ABCDEFGHIJKMNPRSTUVWXYZabcdefghijkmnprstuvwxyz23456789'), 0,4) ;
            imagestring($image, 5, 7, 5, $text, $color) ;
           
            //显示、销毁
            header('content-type: image/jpeg');
            imagejpeg($image);
            imagedestroy($image);
    }


}







































?>