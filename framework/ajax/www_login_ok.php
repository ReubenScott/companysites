<?php
/***********************************************************
	Filename: {phpok}ajax/www_login_ok.php
	Note	: 会员登录验证
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

//检测验证码填写是否正确
if($this->config["is_vcode"] && function_exists("imagecreate"))
{
	$_code = $this->get("_code");
	if(!$_SESSION["www_vcode"])
	{
		json_exit("验证码异常，请检查！");
	}
	if($_SESSION["www_vcode"] != md5($_code))
	{
		json_exit("验证码填写不正确！");
	}
}


$user = $this->get("user");
$pass = $this->get("pass");
if(!$user || !$pass)
{
	json_exit("会员登录失败，账号或密码为空");
}
$rs = $this->model("user")->chk_name($user);
if(!$rs)
{
	json_exit("会员信息不存在");
}
$action = user_login($rs["id"],$pass);
if($action != "ok")
{
	json_exit($action);
}
json_exit("会员登录成功",true);
?>