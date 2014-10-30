<?php
/***********************************************************
	Filename: ajax/www_delete_it.php
	Note	: 删除一条记录
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 10时28分
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
//判断项目权限
if(!$_SESSION["user_id"])
{
	json_exit("您没有权限执行此操作，请先登录");
}

$id = $this->get("id","int");
if(!$id)
{
	json_exit("未指定ID");
}
$this->model("list");
$rs = $this->list_model->get_one($id);
if($rs["user"] != $_SESSION["user_rs"]['user'])
{
	json_exit("您没有权限执行此操作");
}
$this->list_model->delete($id,$rs["module_id"]);
json_exit("删除成功",true);