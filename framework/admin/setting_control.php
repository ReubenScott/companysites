<?php
/***********************************************************
	Filename: admin/setting_control.php
	Note	: 设置中心页
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-31 22:29
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class setting_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$this->view("index");
	}
}
?>