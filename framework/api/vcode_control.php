<?php
/***********************************************************
	Filename:  api/vcode_control.php
	Note	: 验证码
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月1日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class vcode_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$info = $this->lib("vcode")->word();
		$appid = $this->get("appid");
		if(!$appid) $appid = $this->app_id;
		$_SESSION["vcode_".$appid] = md5(strtolower($info));
		$this->lib("vcode")->create();
	}
}
?>