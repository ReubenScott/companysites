<?php
/***********************************************************
	Filename: ajax/admin_code_check.php
	Note	: 管理员登录验证码检测
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 18:04
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$val = $this->get("val");
if(!$val)
{
	json_exit("验证码不能为空！");
}
if(!$_SESSION["admin_vcode"])
{
	json_exit("验证码生成异常，请检查！");
}
if($_SESSION["admin_vcode"] != md5($val))
{
	json_exit("验证码填写不正确！");
}
json_exit("验证码填写正确",true);
?>