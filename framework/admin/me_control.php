<?php
/***********************************************************
	Filename:  admin/me_control.php
	Note	: 管理员面板信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年04月24日 06时14分
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class me_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function panel_f()
	{
		$this->view("me_panel");
	}

	function password_f()
	{
		$this->view("me_password");
	}

	function password_save_f()
	{
		$oldpass = $this->get("oldpass");
		$newpass = $this->get("newpass");
		$chkpass = $this->get("chkpass");
		if(!$oldpass || !$newpass || !$chkpass)
		{
			error("所有加星号的文本框均必须填写",$this->url("me","password"),"error");
		}
		if($newpass != $chkpass)
		{
			error("两次输入的新密码不一致",$this->url("me","password"),"error");
		}
		if($oldpass == $newpass)
		{
			error("新旧密码一致，不需要修改操作",$this->url("me","password"));
		}
		$rs = $this->model('admin')->get_one($_SESSION["admin_id"]);
		if(!password_check($oldpass,$rs["pass"]))
		{
			error("管理员密码输入不正确！",$this->url("me","password"),"error");
		}
		$password = password_create($newpass);
		$this->model('admin')->update_password($_SESSION["admin_id"],$password);
		$_SESSION["admin_rs"]["pass"] = $password;
		$html = '<input type="button" value=" 确定 " class="submit" onclick="$.dialog.close();" />';
		error_open("密码修改成功，请下次登录后使用新密码登录！","ok",$html);
	}
}
?>