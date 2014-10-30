<?php
/***********************************************************
	Filename: ajax/admin_res.php
	Note	: 自定义表单中获取附件内容
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-04-10 02:56
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
$id = $this->get("id");
if(!$id)
{
	json_exit("没有指定附件ID");
}
$list = explode(",",$id);
$newlist = array();
foreach($list AS $key=>$value)
{
	$value = intval($value);
	if($value)
	{
		$newlist[] = $value;
	}
}
$id = implode(",",$newlist);
if(!$id)
{
	json_exit("黑客行为要不得，请传递正确的附件ID");
}
$rslist = $this->model("res")->get_list_from_id($id,true);
json_exit($rslist,true);
?>