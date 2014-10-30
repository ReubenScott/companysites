<?php
/***********************************************************
	Filename:  form/text_admin.php
	Note	: 文本框
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class text_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	//扩属属性配置
	function config()
	{
		$html = $GLOBALS['app']->dir_phpok."form/html/text_admin.html";
		$GLOBALS['app']->view($html,"abs-file");
	}

	//GET

	function format($rs)
	{
		//针对时间和日期进行格式化
		if($rs["content"] && $rs['format'] == 'time')
		{
			$format = $rs['form_btn'] == "datetime" ? "Y-m-d H:i" : "Y-m-d";
			$rs['content'] = date($format,$rs['content']);
		}
		$this->app->assign("rs",$rs);
		$this->app->assign("current_date",date("Y-m-d",$this->app->system_time));
		$file = $this->app->dir_phpok."form/html/text_format_admin.html";
		$content = $this->app->fetch($file,'abs-file');
		$this->app->unassign("rs");
		return $content;
	}
}