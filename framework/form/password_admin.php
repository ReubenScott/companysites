<?php
/***********************************************************
	Filename: {phpok}/form/password_admin.php
	Note	: 文本框
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class password_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$html = $this->app->dir_phpok."form/html/password_admin.html";
		$this->app->view($html,"abs-file");
	}

	function format($rs)
	{
		if($rs["content"] && $rs["password_type"] == "show" && strlen($rs["content"]) > 2)
		{
			$length = strlen($rs["content"]);
			$new_str = "";
			for($i=0;$i<($length-2);$i++)
			{
				$new_str .= "*";
			}
			$old = substr($rs["content"],1,($length-2));
			$rs["content"] = str_replace($old,$new_str,$rs["content"]);
		}
		if($rs["content"] && $rs["password_type"] == "md5" && strlen($rs["content"]) != 32)
		{
			$rs["content"] = "";
		}
		$this->app->assign("rs",$rs);
		$this->app->assign("current_date",date("Y-m-d",$this->app->system_time));
		$file = $this->app->dir_phpok."form/html/password_format_admin.html";
		$content = $this->app->fetch($file,'abs-file');
		$this->app->unassign("rs");
		$this->app->unassign("current_date");
		return $content;
	}
}