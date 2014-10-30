<?php
/***********************************************************
	Filename:  admin/ajax_control.php
	Note	: Ajax调用，无限制调用，请在应用中根据需要添加
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 18:08
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class ajax_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$filename = $this->get("filename");
		if(!$filename)
		{
			json_exit("Ajax目标文件不能为空！");
		}
		$ajax_file = $this->root_dir."ajax/admin_".$filename.".php";
		if(!file_exists($ajax_file))
		{
			json_exit("Ajax文件：".$ajax_file." 不存在！");
		}
		include($ajax_file);
	}

}
?>