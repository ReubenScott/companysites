<?php
/***********************************************************
	Filename: ajax/admin_adminer_check.php
	Note	: 管理员账号验证
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 18:00
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$username = $this->get("username");
if(!$username)
{
	json_exit("管理员账号不能为空！");
}
$this->model("admin");
$rs = $this->admin_model->get_one_from_name($username);
if(!$rs)
{
	json_exit("管理员信息不存在！");
}

# 判断管理员身份是否被锁定
if(!$rs["status"])
{
	json_exit("管理员账号被锁定，请联系超管！");
}

json_exit("管理员信息验证成功！",true);
?>