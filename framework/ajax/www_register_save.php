<?php
/***********************************************************
	Filename: {phpok}ajax/www_register_save.php
	Note	: 存储会员注册数据
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月12日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
//判断会员是否已登录
if($_SESSION["user_id"])
{
	json_exit("您已登录，请先退出！");
}

//判断系统是否关闭注册功能
if(!$this->site["register_status"])
{
	$info = $this->site["register_close"] ? $this->site["register_close"] : "网站暂停会员注册功能";
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


$this->model("user");
$this->model("usergroup");
//存储注册信息
$user = $this->get("user");
if(!$user)
{
	json_exit("会员账号不能为空");
}
//检测账号是否存在
$chk_rs = $this->user_model->chk_name($user);
if($chk_rs)
{
	json_exit("会员账号已存在，请返回");
}

//检测密码
$newpass = $this->get("newpass");
$chkpass = $this->get("chkpass");
if(!$newpass)
{
	json_exit("密码不能为空");
}
if($newpass != $chkpass)
{
	json_exit("两次输入的密码不一致");
}

$group_id = $this->get("group_id","int");
if(!$group_id)
{
	$group_rs = $this->usergroup_model->get_default();
	if(!$group_rs)
	{
		json_exit("系统未设置默认会员组，请联系管理员");
	}
	if(!$group_rs["status"])
	{
		json_exit($group_rs["title"]."状态为：未启用，请联系管理员");
	}
	$group_id = $group_rs["id"];
}
if(!$group_id)
{
	json_exit("会员组不能为空");
}
$group_rs = $this->usergroup_model->get_one($group_id);
if(!$group_rs)
{
	json_exit("会员组未找到，请联系管理员");
}
if(!$group_rs["status"])
{
	json_exit($group_rs["title"]."状态为：未启用，请联系管理员");
}
if(!$group_rs["is_default"] && !$group_rs["is_open"])
{
	json_exit("您没有权限指定此会员组！");
}

//存储数据
$array = array();
$array["user"] = $user;
$array["pass"] = password_create($newpass);
//注册后需要管理员审核后才能通过
$array["status"] = 0; 

$array["regtime"] = $this->system_time;
$array["group_id"] = $group_id;
//存档
$user_id = $this->user_model->save($array);
if(!$user_id)
{
	json_exit("会员注册失败，请联系管理员");
}
//
//更新会员表扩展字段内容
$extlist = $this->user_model->fields_all();
$ext = array();
$ext["id"] = $user_id;
if($extlist)
{
	foreach($extlist AS $key=>$value)
	{
		$ext_value = ext_value($value);
		$ext[$value["identifier"]] = $ext_value;
	}
}
$this->user_model->save_ext($ext);

$rs = $this->user_model->get_one($user_id);
if(!$rs)
{
	json_exit("会员注册失败，请联系管理员");
}

if($rs["status"])
{
	user_login($rs["id"]);
}
json_exit("会员注册成功",true);