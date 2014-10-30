<?php
/***********************************************************
	Filename: ajax/www_cart_add.php
	Note	: 添加到购物车中，这里仅限session
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年7月9日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
unset($_SESSION["cart"],$_SESSION["cart_total"]);
exit("ok");
?>