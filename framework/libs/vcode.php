<?php
/***********************************************************
	Filename:  libs/vcode.php
	Note	: 通用图形验证码类，支持干扰线
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2014年1月6日
***********************************************************/
//if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class vcode_lib
{
	var $width = 76;
	var $height = 24;
	var $count = 4;
	//要生成的验证码
	var $word = '';
	
	function __construct()
	{
		$this->width = 76;
		$this->height = 24;
		$this->count = 4;
	}

	function width($width=76)
	{
		$this->width = $width;
	}

	function height($height=24)
	{
		$this->height = $height;
	}

	function count($count=4)
	{
		$this->count = $count;
	}

	function word()
	{
		$txt = "345689ABCDEFGHJKLMNPRTUVWXY";
		$length = strlen($txt);
		$thetxt = '';
		for($i=0;$i<$this->count;$i++)
		{
			$thetxt .= $txt[rand(0,$length-1)];
		}
		$this->word = $thetxt;
		return $thetxt;
	}
	
	//这里仅限数字
	function create()
	{
		//清空所有缓存
		ob_end_clean();
		//ob_start();
		//制作一张画布
		$aimg = imagecreate($this->width,$this->height);
		imagefilledrectangle($aimg, 0, 0, $this->width, $this->height, imagecolorallocate($aimg, 255, 255, 255));
		$color = $this->color();
		$color_id=imagecolorallocate($aimg, $color[0], $color[1], $color[2]);
		for($i=0;$i<$this->count;$i++)
		{
			$rndtxt = $this->word[$i];
			//每个数字都生成一张小图
			$src_w = 12;
			$src_h = 14;
			$img = imagecreate($src_w,$src_h);
			//填充白色背景
			imagerectangle($img, 0, 0, $src_w, $src_h, imagecolorallocate($img, 255, 255, 255));
			//写入数据
			imagestring($img,3,1,1,$rndtxt,imagecolorallocate($img, $color[0], $color[1], $color[2]));
			//计算每个小图变大图后的宽度
			$dst_x = $i * intval($this->width/$this->count);
			$dst_y = 0;
			$dst_h = $this->height;
			$dst_w = intval($this->width/($this->count - 1));
			//将小图内容移到大图容器里来
			imagecopyresized($aimg,$img,$dst_x,$dst_y,0,0,$dst_w,$dst_h,$src_w,$src_h);
			imagedestroy($img);
		}
		//画曲线
		$this->lines($aimg,$color_id,2);
		//增加同色系干扰素
		$pxsum = 30;
		for($i=0;$i<$pxsum;$i++)
		{
			imagesetpixel($aimg,mt_rand(1,$this->width-1),mt_rand(1,$this->height-1),$color_id);
		}
		header("Pragma:no-cache");
		header("Cache-control:no-cache");
		header("Content-type: image/png");
		imagepng($aimg);
		imagedestroy($aimg);
		exit;
	}

	//随机颜色
	function color()
	{
		$list = array("#0000CC","#000066","#000000","#3300CC","#330066","#660000","#006633","#990033","#990066","#336633");
		$total = count($list);
		$color = rand(0,($total-1));
		$color = $list[$color];
		return array(hexdec($color[1].$color[2]),hexdec($color[3].$color[4]),hexdec($color[5].$color[6]));
	}

	//画干扰线
	function lines($img,$color_id,$st=1)
	{
		$rand = mt_rand(3,10);
		$rand1 = mt_rand(5,15);
		$rand2 = mt_rand(5,10);
		for ($yy=$rand; $yy<=+$rand+1; $yy++)
		{
			for ($px=-38;$px<=38;$px=$px+0.1)
			{
				$x=$px/$rand1;
				if ($x!=0)
				{
					$y=sin($x);
				}
				$py=$y*$rand2;
				imagesetpixel($img, $px+38, $py+$yy, $color_id);
			}
		}
	}

}
//$vcode = new vcode_lib();
//$vcode->word();
//$vcode->create();
?>