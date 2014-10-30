<?php
/***********************************************************
	Filename:  api/index_control.php
	Note	: API接口默认接入
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年10月30日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class index_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		echo '111';
	}

	function vcode_f()
	{
		//
	}
}