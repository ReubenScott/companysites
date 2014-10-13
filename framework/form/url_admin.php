<?php
/***********************************************************
	Filename: {phpok}/form/url_admin.php
	Note	: 网址关联配置
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-15 11:46
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class url_form
{
	var $app;
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$file = $this->app->dir_phpok."form/html/url_admin.html";
		$this->app->view($file,'abs-file');
	}
	
	function format($rs)
	{
		if($rs["content"] && is_string($rs["content"]))
		{
			$tmp = unserialize($rs["content"]);
			if($tmp && is_array($tmp) && count($tmp)>0)
			{
				$rs["content"] = $tmp;
			}
			else
			{
				$tmp["default"] = $rs["content"];
				$tmp["rewrite"] = "";
				$rs["content"] = $tmp;
			}
		}
		$this->app->assign("edit_rs",$rs);
		$file = $this->app->dir_phpok."form/html/url_form_admin.html";
		$content = $this->app->fetch($file,'abs-file');
		return $content;
	}
}
?>