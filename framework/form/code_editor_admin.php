<?php
/***********************************************************
	Filename:  form/code_editor_admin.php
	Note	: 代码编辑器
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class code_editor_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$html = "framework/form/html/code_editor_admin.html";
		$this->app->view($html,"abs-file");
	}

	function format($rs)
	{
		$this->app->assign("rs",$rs);
		$file = "framework/form/html/code_editor_form_admin.html";
		$content = $this->app->fetch($file,'abs-file');
		$this->app->unassign("rs");
		return $content;
	}
}