<?php
/***********************************************************
	Filename: ajax/www_update_dateline.php
	Note	: 刷新简历
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月29日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
if(!$_SESSION['user_id'])
{
	$this->json("非会员不能执行此操作");
}
$id = $this->get('id','int');
$rs = phpok("_arc","id=".$id);
if(!$rs)
{
	$this->json('简历信息不存在');
}
if(!$rs['status'])
{
	$this->json('您的简历还未通过审核');
}
if($rs['user_id'] != $_SESSION["user_id"])
{
	$this->json("您没有权限刷新其他人简历信息");
}
//刷新简历
$array = array("dateline"=>$this->time);
$this->model('list')->save($array,$id);
//清空缓存
delete_cache('call');
$this->json('ok',true);
?>