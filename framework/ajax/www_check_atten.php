<?php
/***********************************************************
	Filename: ajax/www_check_atten.php
	Note	: 检查是否已关注此主题
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 11时22分
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
$project = "atten";
if(!$_SESSION["user_id"])
{
	json_exit("非会员不能执行此操作");
}
$site_id = $this->config_site["id"];
$this->model("project");
$this->model("id");
$this->model("usergroup");
$id_rs = $this->id_model->get_id($project,$site_id);
if(!$id_rs || $id_rs["type_id"] != "project")
{
	json_exit("项目不存在，请联系管理员");
}
$p_rs = $this->project_model->get_one($id_rs["id"]);
if(!$p_rs || !$p_rs["status"])
{
	json_exit("项目不存在或项目未启用，请联系管理员");
}
if(!$p_rs["module"])
{
	json_exit("未指定模型");
}
$this->model("module");
$m_rs = $this->module_model->get_one($p_rs["module"]);
if(!$m_rs || $m_rs["status"] != "1")
{
	json_exit("未启用模型，不能执行此操作");
}
$tid = $this->get("tid","int");
if(!$tid)
{
	json_exit("未指定ID");
}
$this->model("list");
$condition = "ext.title_id='".$tid."' AND ext.user='".$_SESSION["user_rs"]["user"]."' AND l.site_id='".$site_id."'";
$chk_rs = $this->list_model->only_check($condition,$p_rs["module"]);
if($chk_rs)
{
	json_exit("已关注",true);
}
else
{
	json_exit("+ 关注",true);
}
?>