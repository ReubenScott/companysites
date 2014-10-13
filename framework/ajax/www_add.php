<?php
/***********************************************************
	Filename: ajax/www_add.php
	Note	: 添加一条记录
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 10时28分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$site_id = $this->config_site["id"];
if(!$site_id)
{
	json_exit("站点信息获取异常，请联系管理员");
}
//获取project_id
$project = $this->get("project");
if(!$project)
{
	json_exit("未指定项目，不能执行此操作");
}
$this->model("project");
$this->model("id");
$this->model("usergroup");
$id_rs = $this->id_model->get_id($project,$site_id);
if(!$id_rs || $id_rs["type_id"] != "project")
{
	json_exit("项目不存在，请联系管理员");
}
//判断项目权限
if($_SESSION["user_id"])
{
	$group_id = $_SESSION["user_rs"]["group_id"];
	$group_rs = $this->usergroup_model->get_one($group_id);
}
else
{
	$group_rs = $this->usergroup_model->get_guest();
}
if(!$group_rs || $group_rs["status"])
{
	json_exit("您没有权限执行此操作");
}

//判断是否有相应的权限
$post_popedom = $group_rs["post_popedom"] ? explode(",",$rs["post_popedom"]) : array("none");
if(in_array("none",$post_popedom))
{
	json_exit("您没有权限执行此操作");
}

if(!in_array($id_rs["id"],$post_popedom))
{
	json_exit("您没有权限执行此操作");
}

//读取项目信息
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


$title = $this->get("title");
if(!$title)
{
	json_exit("操作异常，请联系管理员");
}
//判断相关主题是否已存在
$condition = "l.title='".$title."' AND ext.user='".$_SESSION["user"]."' AND l.site_id='".$site_id."'";
$chk_rs = $this->list_model->only_check($condition);
if($chk_rs)
{
	//
}
$array = array();
$array["title"] = $title;
$array["dateline"] = $this->system_time;
$array["status"] = $group_rs["post_status"];
$array["hidden"] = 0;
$array["module_id"] = $p_rs["module"];
$array["project_id"] = $id_rs["id"];
$array["site_id"] = $site_id;
$insert_id = $this->list_model->save($array);//加入成功后
if(!$insert_id)
{
	json_exit("添加失败");
}

if(!$_SESSION["user_id"])
{
	json_exit("非会员不能执行此操作，请先登录");
}
//
?>