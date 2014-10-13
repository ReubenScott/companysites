<?php
/***********************************************************
	Filename: {phpok}/api/admlogin_control.php
	Note	: 管理员登录涉及到的接口验证
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2014年1月6日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class admlogin_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}


	//检测管理员账号是否存在
	//如果账号存在，返回密文
	function name_f()
	{
		//账号不能为空
		$user = $this->get("user");
		if(!$user) $this->json('admlogin.account-empty');
		//检测账号是否存在
		$rs = $this->model('admin')->get_one_from_name($user);
		if(!$rs) $this->json('admlogin.account-not-exit');
		//账号是否被锁定
		if($rs['status'] != '1') $this->json('admlogin.account-is-locked');
		//数据正确，返回数组
		$list = explode(":",$rs['pass']);
		$tip = $this->lang[$this->app_id]['admlogin']['account-ok'] ? $this->lang[$this->app_id]['admlogin']['account-ok'] : $this->lang['global']['admlogin']['account-ok'];
		if($tip || $list[1])
		{
			$rs = array();
			if($tip) $rs['info'] = $tip;
			if($list[1]) $rs['key'] = $list[1];
			$this->json($rs,true);
		}
		$this->json('',true);
	}

	//管理员登录
	function login_f()
	{
		$username = $this->get("username");
		$password = $this->get("password");
		if($this->config["is_vcode"] && function_exists("imagecreate"))
		{
			$code_id = $this->get("code_id");
			//验证码检测为空
			if(!$code_id) $this->json("admlogin.vcode-empty");
			$code_id = strtolower($code_id);
			//验证码检测不通过
			if(md5($code_id) != $_SESSION["vcode_admin"]) $this->json("admlogin.vcode-failed");
		}
		//检测账号是否存在
		$rs = $this->model('admin')->get_one_from_name($username);
		if(!$rs) $this->json('admlogin.account-not-exit');
		//账号是否被锁定
		if($rs['status'] != '1') $this->json('admlogin.account-is-locked');
		//检测密码
		if(!password_check($password,$rs["pass"])) $this->json("admlogin.password-failed");
		//OK，一切检测通过，现在更新权限
		//非系统管理员，更新权限
		if(!$rs["if_system"])
		{
			$popedom_list = $this->model('admin')->get_popedom_list($rs["id"]);
			if(!$popedom_list) $this->json("admlogin.not-power");
			$_SESSION["admin_popedom"] = $popedom_list;
		}
		//登录成功后
		$_SESSION["admin_id"] = $rs["id"];
		$_SESSION["admin_account"] = $rs["account"];
		$_SESSION["admin_rs"] = $rs;
		$_SESSION["admin_site_id"] = $this->site['id'];
		# 存储网站配置信息
		unset($_SESSION['vcode_admin']);
		$this->json($rs['account'],true,true,false);
	}
}
?>