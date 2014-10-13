<?php
/***********************************************************
	Filename: ajax/admin_field_identifier.php
	Note	: 检测标识串是否存在
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-11-29 16:18
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$id = $this->get("id","int");
$identifier = $this->get("identifier");
if(!$identifier)
{
	json_exit("字段标识不能为空！");
}
$identifier = strtolower($identifier);
//字符串是否符合条件
if(!preg_match("/[a-z][a-z0-9\_]*/",$identifier))
{
	json_exit("字段标识不符合系统要求，限字母、数字及下划线且必须是字母开头！");
}
$this->model("fields");
$chk = $this->fields_model->is_has_sign($identifier);
if($chk)
{
	json_exit("字段标识已经存在！");
}
json_exit("ok",true);
?>