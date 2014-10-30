<?php
/***********************************************************
	Filename:  api/logout_control.php
	Note	: 会员退出接口
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class logout_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$nickname = $_SESSION["user_name"];
		unset($_SESSION['user_id'],$_SESSION['user_rs'],$_SESSION['user_name']);
		//session_destroy();
		$tips = $this->lang[$this->app_id][103];
		if(!$tips)
		{
			$tips = '会员 <span style="font-weight:bold;color:red;">'.$nickname.'</span> 成功退出';
		}
		$tips = $this->lang_format($tips,array('nickname'=>$nickname));
		$this->json($tips,true);
	}
}
?>