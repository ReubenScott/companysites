<?php
/***********************************************************
	Filename:  form/ptitle_admin.php
	Note	: 主题选择器
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class ptitle_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
		$this->app->model("opt");
		$this->app->model("list");
		$this->app->model("project");
	}

	function config()
	{
		$opt_list = $this->app->project_model->get_all_project($_SESSION["admin_site_id"],"p.module>0");
		$this->app->assign("opt_list",$opt_list);
		$html = $this->app->dir_phpok."form/html/title_admin.html";
		$this->app->view($html,"abs-file");
	}

	function format($rs)
	{
		if(!$rs["optlist_id"])
		{
			return "未指定选项组";
		}
		$project_id = $rs["optlist_id"];
		$project_rs = $this->app->project_model->get_one($project_id);
		if(!$project_rs)
		{
			return "项目不存在，请检查";
		}
		$rslist = $this->app->list_model->get_list($project_rs["module"],"l.status='1' AND l.project_id='".$project_id."'",0,0,$project_rs["orderby"]);
		if(!$rslist)
		{
			return "选项内容不存在，请检查";
		}
		if($rs["is_multiple"])
		{
			$content = array();
			if($rs["content"])
			{
				$content = explode(",",$rs["content"]);
			}
			$rs["content"] = $content;		
		}
		$file = $this->app->dir_phpok."form/html/title_form_admin.html";
		$this->app->assign("rs",$rs);
		$this->app->assign("rslist",$rslist);
		$content = $this->app->fetch($file,'abs-file');
		$this->app->unassign("rs");
		$this->app->unassign("rslist");
		return $content;
	}
}