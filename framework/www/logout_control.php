<?php
/***********************************************************
	Filename:  www/logout_control.php
	Note	: 会员退出操作
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 05时33分
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class logout_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$nickname = $_SESSION["nickname"];
		session_destroy();
		error("会员<span class='red'> ".$nickname." </span>成功退出",$this->url("index"),"ok");
	}
}
?>