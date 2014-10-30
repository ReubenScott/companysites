<?php
/***********************************************************
	Filename: {phpok}ajax/www_logout.php
	Note	: 会员退出操作，退出后将清空相关的SESSION信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月14日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
if(!$_SESSION["user_id"])
{
	json_exit("您还没有登录，不能使用此功能");
}
unset($_SESSION["user_id"],$_SESSION["user_rs"],$_SESSION["nickname"]);
json_exit("会员退出成功",true);