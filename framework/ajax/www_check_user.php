<?php
/***********************************************************
	Filename: ajax/www_check_user.php
	Note	: 检测会员账号是否存在
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年06月20日 04时03分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$this->model("user");
$id = $this->get("id","int");
$user = $this->get("user");
if(!$user)
{
	json_exit("会员账号不能为空");
}
$rs = $this->model('user')->chk_name($user,$id);
if($rs)
{
	json_exit("会员账号已被使用");
}
else
{
	json_exit("验证通过",true);
}
?>