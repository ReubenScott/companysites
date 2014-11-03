<?php
/***********************************************************
	Filename: {phpok}ajax/www_login.php
	Note	: 会员登录弹出窗口
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月14日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
//判断会员是否已登录
if($_SESSION["user_id"])
{
	json_exit("您已登录，请先退出！");
}

//判断系统是否关闭注册功能
if(!$this->site["login_status"])
{
	$info = $this->site["login_close"] ? $this->site["login_close"] : "网站暂停会员登录功能";
	json_exit($info);
}

//输出注册模板HTML信息
if($this->tpl->check_exists("login_open"))
{
	$this->view("login_open");
}
else
{
	$this->view("framework/tpl_default/login_open.html","abs-file");
}