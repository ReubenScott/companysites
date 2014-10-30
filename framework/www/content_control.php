<?php
/***********************************************************
	Filename:  www/content_control.php
	Note	: 内容信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-11-27 11:24
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class content_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	//内容首页
	function index_f()
	{
		//创建随机验证串
		$_SESSION['comment_spam_code'] = str_rand(10);
		$id = $this->get("id");
		if(!$id) error("操作异常！","","error");
		$dt = array('site_id'=>$this->site['id']);
		if(intval($id) && $id == intval($id))
		{
			$dt['id'] = $id;
		}
		else
		{
			$dt['phpok'] = $id;
		}
		$page = $this->config['pageid'] ? $this->config['pageid'] : 'pageid';
		$pageid = $this->get($page,'int');
		if(!$pageid) $pageid = 1;
		$dt['pageid'] = $pageid;
		$this->assign('pageid',$pageid);
		$rs = $this->model('data')->arc($dt);
		if(!$rs)
		{
			error("内容不存在",$this->url,'notice',10);
		}
		$pt = array('site_id'=>$this->site['id'],'pid'=>$rs['project_id'],'project_ext'=>1);
		$project_rs = $this->call->phpok("_project",$pt);
		if(!$project_rs)
		{
			error("项目不存在或未启用",$this->url,'notice',10);
		}
		$this->assign("page_rs",$project_rs);
		//如果
		if($project_rs['parent_id'])
		{
			$pt['pid'] = $project_rs['parent_id'];
			$parent_rs = $this->call->phpok("_project",$pt);
			if(!$parent_rs || !$parent_rs['status'])
			{
				error("父级项目未启用",$this->url,'notice',10);
			}
			$this->assign("parent_rs",$parent_rs);
		}
		if($rs["cate_id"])
		{
			$dt = array('site_id'=>$rs['site_id'],'pid'=>$rs['project_id'],'cateid'=>$rs['cate_id'],'cate_ext'=>1);
			$cate_rs = $this->call->phpok("_cate",$dt);
			$this->assign("cate_rs",$cate_rs);
			if($cate_rs['parent_id'] && $cate_rs['parent_id'] != $project_rs['cate'])
			{
				$dt['cateid'] = $rs['parent_id'];
				$cate_parent_rs = $this->call->phpok("_cate",$dt);
				$this->assign("cate_parent_rs",$cate_parent_rs);
			}
		}
		//获取模板配置
		$tpl = $rs['tpl'];
		if(!$tpl && $cate_rs['tpl_content']) $tpl = $cate_rs['tpl_content'];
		if(!$tpl && $cate_parent_rs['tpl_content']) $tpl = $cate_parent_rs['tpl_content'];
		if(!$tpl && $project_rs['tpl_content']) $tpl = $project_rs['tpl_content'];
		if(!$tpl && $parent_rs['tpl_content']) $tpl = $parent_rs['tpl_content'];
		if(!$tpl) $tpl = $project_rs['identifier'].'_content';
		if(!$this->tpl->check_exists($tpl)) $tpl = "index";
		//增加点击
		$this->model('list')->add_hits($rs["id"]);
		//执行SEO优化
		$this->phpok_seo($rs);
		$this->assign("rs",$rs);
		$this->view($tpl);
	}
}
?>