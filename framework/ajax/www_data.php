<?php
/***********************************************************
	Filename: {phpok}ajax/www_data.php
	Note	: 通过Ajax获取数据调用信息
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月28日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$offset = $this->get("startid","int");
$id = $this->get("phpok");
if(!$id)
{
	json_exit("未指数据调用中心ID");
}
$string = "offset=".$offset;
$cid = $this->get("cid","int");
if($cid) $string .= "&cid=".$cid;
$list = $this->call->phpok($id,$string);
if(!$list)
{
	json_exit('没有数据');
}
$tpl = $this->get("tpl");
if($tpl && $this->tpl->check_exists($tpl))
{
	//$this->assign("rslist",$list);
	//$content = $this->fetch($tpl);
	//json_exit($content,true);
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