<?php
/***********************************************************
	Filename: ajax/admin_vcode.php
	Note	: 生成图片验证码
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 18:06
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$x_size=76;
$y_size=23;
$aimg = imagecreate($x_size,$y_size);
$back = imagecolorallocate($aimg, 255, 255, 255);
$border = imagecolorallocate($aimg, 0, 0, 0);
imagefilledrectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $back);
$txt="0123456789";
$txtlen=strlen($txt);

$thetxt="";
for($i=0;$i<4;$i++)
{
	$randnum=mt_rand(0,$txtlen-1);
	$randang=mt_rand(-10,10);	//文字旋转角度
	$rndtxt=substr($txt,$randnum,1);
	$thetxt.=$rndtxt;
	$rndx=mt_rand(1,5);
	$rndy=mt_rand(1,4);
	$colornum1=($rndx*$rndx*$randnum)%255;
	$colornum2=($rndy*$rndy*$randnum)%255;
	$colornum3=($rndx*$rndy*$randnum)%255;
	$newcolor=imagecolorallocate($aimg, $colornum1, $colornum2, $colornum3);
	imageString($aimg,3,$rndx+$i*21,5+$rndy,$rndtxt,$newcolor);
}
unset($txt);
$thetxt = strtolower($thetxt);
$_SESSION["admin_vcode"] = md5($thetxt);#[写入session中]
imagerectangle($aimg, 0, 0, $x_size - 1, $y_size - 1, $border);
$newcolor="";
$newx="";
$newy="";
$pxsum=30;	//干扰像素个数
for($i=0;$i<$pxsum;$i++)
{
	$newcolor=imagecolorallocate($aimg, mt_rand(0,254), mt_rand(0,254), mt_rand(0,254));
	imagesetpixel($aimg,mt_rand(0,$x_size-1),mt_rand(0,$y_size-1),$newcolor);
}
header("Pragma:no-cache");
header("Cache-control:no-cache");
header("Content-type: image/png");
imagepng($aimg);
imagedestroy($aimg);
exit;
?>