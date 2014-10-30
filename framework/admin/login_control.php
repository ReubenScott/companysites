<?php
/***********************************************************
	Filename:  admin/login_control.php
	Note	: 管理员登录模块
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 13:13
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class login_control extends base_control
{
	function __construct()
	{
		parent::control();
		$this->model("admin");
		$this->model("site");
	}

	# 登录页面
	function index_f()
	{
		$vcode = $this->config["is_vcode"] && function_exists("imagecreate") ? true : false;
		$this->assign("vcode",$vcode);
		$login = $this->config['admin_tpl_login'] ? $this->config['admin_tpl_login'] : 'login';
		if(!$this->tpl->check_exists($login)) $login = 'login';
		$this->view($login);
	}

	# 发送登录
	function ok_f()
	{
		$username = $this->get("username");
		$password = $this->get("password");
		if($this->config["is_vcode"] && function_exists("imagecreate"))
		{
			$code_id = $this->get("code_id");
			if(!$code_id)
			{
				error("验证码不允许为空！",$this->url("login"),"error");
			}
			if(md5($code_id) != $_SESSION["admin_vcode"])
			{
				error("验证码填写不正确！",$this->url("login"),"error");
			}
		}
		$rs = $this->model('admin')->get_one_from_name($username);
		if(!$rs)
		{
			error("管理员信息不存在！",$this->url("login"),"error");
		}
		if(!$rs["status"])
		{
			error("管理员账号已被锁定，请联系超管！",$this->url("login"),"error");
		}
		if(!password_check($password,$rs["pass"]))
		{
			error("管理员密码输入不正确！",$this->url("login"),"error");
		}
		//获取管理员的权限
		if(!$rs["if_system"])
		{
			$popedom_list = $this->model('admin')->get_popedom_list($rs["id"]);
			if(!$popedom_list)
			{
				error("你的管理权限未设置好，请联系超级管理员进行设置","","notice");
				exit;
			}
			$_SESSION["admin_popedom"] = $popedom_list;
		}
		$_SESSION["admin_id"] = $rs["id"];
		$_SESSION["admin_account"] = $rs["account"];
		$_SESSION["admin_rs"] = $rs;
		$_SESSION["admin_site_id"] = $this->site['id'];
		# 存储网站配置信息
		error("管理员 <span class='red'>".$rs["account"]."</span> 登录成功！",$this->url("index"));
	}
}
?>