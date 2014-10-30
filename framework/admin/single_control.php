<?php
/***********************************************************
	Filename: admin/single_control.php
	Note	: 单页面管理
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-31 20:28
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class single_control extends base_control
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