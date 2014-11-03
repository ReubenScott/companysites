<?php
/***********************************************************
	Filename:  form/related_admin.php
	Note	: 关联主题
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-15 11:39
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class related_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$file = 'framework/form/html/related_admin.html';
		$this->app->view($file,"abs-file");
	}
}
?>