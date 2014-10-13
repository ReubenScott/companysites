<?php
/***********************************************************
	Filename: {phpok}/form/title_admin.php
	Note	: 主题选择器
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class title_form
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
		if($this->app->app_id == "admin")
		{
			$site_id = $_SESSION["admin_site_id"];
		}
		else
		{
			$site_id = $this->app->config_site["id"];
		}
		//可选主题列表
		$opt_list = $this->app->project_model->get_all_project($site_id,"p.module>0");
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
		$idlist = $rs["optlist_id"];
		if(!$idlist || !is_array($idlist))
		{
			return "未指定项目，请配置";
		}
		$project_id = implode(",",$idlist);
		//取得项目信息
		$project_list = $this->app->project_model->title_list($project_id);
		if($project_list)
		{
			$open_title = implode(" / ",$project_list) ." - 主题列表";
		}
		else
		{
			$open_title = "主题资源";
		}
		$this->app->assign("open_title",$open_title);
		$condition = " l.project_id IN(".$project_id.") ";
		$total = $this->app->list_model->get_all_total($condition);
		/*if($total<30)
		{
			$rslist = $this->app->list_model->get_all($condition,0,30);
			$this->app->assign("opt_rslist",$rslist);
		}*/
		$this->app->assign("project_id_btn",$project_id);
		$file = $this->app->dir_phpok."form/html/title_form_admin.html";
		if($rs["is_multiple"])
		{
			$content = $rs["content"] ? explode(",",$rs["content"]) : array();
			$rs["content"] = $content;
		}
		$this->app->assign("rs",$rs);
		$content = $this->app->fetch($file,'abs-file');
		$this->app->unassign("rs");
		$this->app->unassign("rslist");
		return $content;
	}
}