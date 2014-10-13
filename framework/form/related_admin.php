<?php
/***********************************************************
	Filename: {phpok}/form/related_admin.php
	Note	: 关联主题
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-15 11:39
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class related_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$file = $this->app->dir_phpok.'form/html/related_admin.html';
		$this->app->view($file,"abs-file");
	}
}
?>