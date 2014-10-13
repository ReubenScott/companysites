<?php
/***********************************************************
	Filename: alipay.php
	Note	: 异步通知订单付款信息
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月15日
***********************************************************/
error_reporting(E_ALL ^ E_NOTICE);
include_once('framework/libs/html.php');
$url = 'api.php?c=plugin&f=exec&id=alipay&exec=notify';
if(isset($_POST))
{
	foreach($_POST AS $key=>$value)
	{
		$url .= "&".$key."=".rawurlencode($value);
	}
}
$cls = new html_lib();
$cls->get_content($url);
?>