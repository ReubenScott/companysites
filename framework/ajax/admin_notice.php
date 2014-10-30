<?php
/***********************************************************
	Filename: ajax/admin_notice.php
	Note	: 获取模型未审核信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年7月26日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
$site_id = $_SESSION["site_id"];
$this->model("project");
$this->model("list");
$this->model("user");
$rslist = $this->project_model->get_all_project($site_id);
if(!$rslist)
{
	json_exit("ok");
}
$list = array();
foreach($rslist AS $key=>$value)
{
	if($value["module"])
	{
		$condition = "l.status=0 AND l.project_id='".$value["id"]."'";
		$value["total"] = $this->list_model->get_total($value["module"],$condition);
		if($value["total"]>0)
		{
			$list[] = $value;
			unset($total);
		}
	}
}
$condition = "u.status=0";
$user_total = $this->user_model->get_count($condition);
if($user_total > 0)
{
	$list[] = array("title"=>"待审核会员","total"=>$user_total);
}
json_exit($list,true);
?>