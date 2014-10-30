<?php
/***********************************************************
	Filename: {phpok}ajax/www_data2.php
	Note	: 通过Ajax获取数据调用信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月28日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
$uid = $this->get("uid");
if(!$uid)
{
	json_exit("未指定ID");
}
$offset = $this->get("startid","int");
$project = $this->get("project");
if(!$project)
{
	json_exit("未指定项目");
}
$id = array_search($project,$this->cache_data['id_list']);
$p_rs = $this->model("project")->get_one($id);
if(!$p_rs["module"] || !$p_rs["status"])
{
	json_exit("项目异常");
}
$ext_list = $this->model('module')->fields_all($p_rs["module"]);
if(!$ext_list)
{
	error("字段异常");
}
$user_field = "user";
foreach($ext_list AS $key=>$value)
{
	if($value["form_type"] == "user")
	{
		$user_field = $value["identifier"];
		break;
	}
}
$psize = 10;
$condition = " l.project_id='".$p_rs['id']."' AND ext.".$user_field."='".$uid."' ";
$condition.= " AND l.module_id='".$p_rs["module"]."' ";

$list = $this->model('list')->get_list($p_rs['module'],$condition,$offset,$psize,"",$p_rs["orderby"]);
if(!$list)
{
	json_exit("没有内容");
}
$tpl = $this->get("tpl");
if($tpl && $this->tpl->check_exists($tpl))
{
	$rslist = "";
	foreach($list AS $key=>$value)
	{
		$this->assign("value",$value);
		$content = $this->fetch($tpl);
		if($content) $rslist[$key] = $content;
	}
	json_exit($rslist,true);
}
else
{
	json_exit($list,true);
}
?>