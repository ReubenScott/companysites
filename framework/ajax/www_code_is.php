<?php
/***********************************************************
	Filename: ajax/www_code_is.php
	Note	: 判断是否启用验证码
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年06月30日 10时56分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
if($this->config["is_vcode"] && function_exists("imagecreate"))
{
	exit("ok");
}
else
{
	exit("error");
}
?>