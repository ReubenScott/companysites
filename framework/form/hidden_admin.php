<?php
/***********************************************************
	Filename: {phpok}/form/hidden_admin.php
	Note	: 隐藏字段
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class hidden_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$html = $this->app->dir_phpok."form/html/hidden_admin.html";
		$this->app->view($html,"abs-file");
	}

	function format($rs)
	{
		$html = '<input type="hidden" name="'.$rs["identifier"].'" id="'.$rs["identifier"].'" value="'.$rs["content"].'" />';
		return $html;
	}
}