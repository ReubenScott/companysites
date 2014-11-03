<?php
/***********************************************************
	Filename:  form/select_admin.php
	Note	: 文本区编辑框
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class select_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
		$this->app->model("opt");
		$this->app->model("project");
		$this->app->model("cate");
	}

	function config()
	{
		$opt_list = $this->app->opt_model->group_all();
		$this->app->assign("opt_list",$opt_list);
		if($this->app->app_id == "admin")
		{
			$site_id = $_SESSION["admin_site_id"];
		}
		else
		{
			$site_id = $this->app->config_site["id"];
		}
		$rslist = $this->app->project_model->get_all_project($site_id);
		if($rslist)
		{
			$p_list = $m_list = array();
			foreach($rslist AS $key=>$value)
			{
				if(!$value["parent_id"])
				{
					$p_list[] = $value;
				}
				if($value["module"])
				{
					$m_list[] = $value;
				}
			}
			if($p_list && count($p_list)>0) $this->app->assign("project_list",$p_list);
			if($m_list && count($m_list)>0) $this->app->assign("title_list",$m_list);
		}
		$catelist = $this->app->cate_model->root_catelist($site_id);
		$this->app->assign("catelist",$catelist);
		$html = "framework/form/html/select_admin.html";
		$this->app->view($html,"abs-file");
	}

	function format($rs)
	{
		if(!$rs["option_list"]) $rs['option_list'] = 'default:0';
		$opt_list = explode(":",$rs["option_list"]);
		$rslist = opt_rslist($opt_list[0],$opt_list[1],$rs['ext_select']);
		$opt_list = explode(":",$rs["option_list"]);
		$group_id = $opt_list[1];
		if($rs["is_multiple"])
		{
			$content = array();
			if($rs["content"]['info'] && is_array($rs['content']['info']))
			{
				foreach($rs['content']['info'] AS $key=>$value)
				{
					$content[] = $value['val'];
				}
				$rs["content"] = $content;
			}
			elseif(is_string($rs['content']))
			{
				$rs['content'] = unserialize($rs['content']);
			}
		}
		else
		{
			if($rs['content'] && $rs['content']['val'] && is_array($rs['content']))
			{
				$rs['content'] = $rs['content']['val'];
			}
		}
		if(!$rslist) return false;
		$GLOBALS['app']->assign('group_id',$group_id);

		//是否多级下拉
		$is_step = false;
		foreach($rslist AS $key=>$value)
		{
			if($value["parent_id"])
			{
				$is_step = true;
				break;
			}
		}
		$GLOBALS['app']->assign("is_step",$is_step);
		$file = "framework/form/html/select_form_admin.html";
		$GLOBALS['app']->assign("rs",$rs);
		$GLOBALS['app']->assign("rslist",$rslist);
		$content = $GLOBALS['app']->fetch($file,'abs-file');
		$GLOBALS['app']->unassign("rs");
		$GLOBALS['app']->unassign("rslist");
		return $content;
	}

	function get_level(&$total_level,&$mylist,$rslist,$pid=0)
	{
		foreach($rslist AS $key=>$value)
		{
			if($value["parent_id"] == $pid)
			{
				$mylist[$value["id"]] = $value;
				$total_level ++;
				$this->get_level($total_level,$mylist[$value["id"]]["sonlist"],$rslist,$value["id"]);
			}
		}
	}
}