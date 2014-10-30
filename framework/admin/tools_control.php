<?php
/***********************************************************
	Filename:  admin/tools_control.php
	Note	: 后台首页控制台
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 13:03
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class tools_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function tools_control($app)
	{
		$this->__construct($app);
	}

	function index_f()
	{
		# 自动跳转到指定的单页
		$this->view("index");
	}
}
?>