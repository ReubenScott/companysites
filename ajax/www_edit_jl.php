<?php
/***********************************************************
	Filename: ajax/www_edit_jl.php
	Note	: 编辑简历操作
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月29日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
//人才简历对应的项目
$pid = 137;
//-----
if(!$_SESSION['user_id'])
{
	error("非会员不能执行此操作",$this->url,'error');
}
if($_SESSION['user_rs']['group_id'] != 2)
{
	error("仅限个人会员有简历功能，其他会员没有这个功能",$this->url('usercp'),'notice',5);
}
//判断是否有个人简历
$rs = phpok('me',array('user_id'=>$_SESSION['user_id']));
if(!$rs)
{
	$p_rs = $this->model('data')->project(array('pid'=>$pid));
	$mid = $p_rs['module'];
	$site_id = $p_rs['site_id'];
	//简历表项目信息
	$title = $_SESSION['user_rs']['fullename'] ? $_SESSION['user_rs']['fullname'] : $_SESSION['user_name'];
	//创建简历
	$array = array("title"=>$title,'project_id'=>$pid,'module_id'=>$mid,'site_id'=>$site_id,'user_id'=>$_SESSION['user_id']);
	$array['status'] = 1;
	$array['cate_id'] = 0;
	$array['dateline'] = $this->time;
	$array['parent_id'] = 0;
	$array['hits'] = 0;
	//存储表单
	$id = $this->model('list')->save($array);
	if(!$id)
	{
		error("创建简历失败，请联系管理员",$this->url('usercp'),'error',10);
	}
	//创建了简历，存储扩展表
	$ext = array("id"=>$id,"project_id"=>$pid,"site_id"=>$site_id,"cate_id"=>0);
	$this->model('list')->save_ext($ext,$mid);
	$this->assign("page_rs",$p_rs);
}
else
{
	$id = $rs['id'];
	$p_rs = $this->model("data")->project(array('pid'=>$rs['project_id']));
	$this->assign("page_rs",$p_rs);
}
$_SESSION['project_spam'] = str_rand(10);
$rs = $this->model('data')->arc(array('id'=>$id));
$flist = $this->model('data')->fields(array('pid'=>$rs['project_id'],'in_title'=>true,'fields_format'=>true,'info'=>$rs));
$this->assign('flist',$flist);
$this->assign('rs',$rs);
$this->view("usercp_resume");