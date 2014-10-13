<?php
/***********************************************************
	Filename: {phpok}ajax/www_register.php
	Note	: 前台会员注册页面
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月12日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}

//判断会员是否已登录
if($_SESSION["user_id"])
{
	error_open("您已登录，请先退出！","error");
}

//判断系统是否关闭注册功能
if(!$this->site["register_status"])
{
	$info = $this->site["register_close"] ? $this->site["register_close"] : "网站暂停会员注册功能";
	error_open($info);
}

//输出注册模板HTML信息
if($this->tpl->check_exists("register_open"))
{
	$this->view("register_open");
}
else
{
	$this->view($this->dir_phpok."tpl_default/register_open.html","abs-file");
}