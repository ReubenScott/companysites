<?php
/***********************************************************
	Filename: {phpok}ajax/www_myatten.php
	Note	: 我的收藏
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月28日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
$offset = $this->get("startid","int");
$offset = intval($offset);
if(!$_SESSION['user_id'])
{
	json_exit("非会员不能执行此操作");
}
$mid = 25;
$sql = "SELECT l.title FROM ".$this->db->prefix."list l ";
$sql.= "JOIN ".$this->db->prefix."list_".$mid." ext ON(l.id=ext.id) ";
$sql.= "WHERE ext.post_uid='".$_SESSION['user_id']."' ";
$sql.= "ORDER BY ext.id DESC LIMIT ".$offset.",10";
$rslist = $this->db->get_all($sql);
if(!$rslist)
{
	json_exit("会员数据为空");
}
$list = "";
foreach($rslist AS $key=>$value)
{
	$list[] = $value["title"];
}
$string = implode("','",$list);
$condition = "u.user IN('".$string."')";
$rslist = $this->model("user")->get_list($condition);
if(!$rslist) json_exit("无会员信息");
$idlist = array();
$list = array();
foreach($rslist AS $key=>$value)
{
	$list[$value["id"]] = $value;
}
$idstring = implode(",",array_keys($list));
$sql = "SELECT count(ext.id) total,ext.post_uid FROM ".$this->db->prefix."list_22 ext WHERE ext.post_uid IN(".$idstring.") ";
$sql.= "GROUP BY ext.post_uid";
$extlist = $this->db->get_all($sql);
if($extlist)
{
	foreach($extlist AS $key=>$value)
	{
		$list[$value["post_uid"]]['total'] = $value['total'];
	}
}

$tpl = $this->get("tpl");
if($tpl && $this->tpl->check_exists($tpl))
{
	$rslist = "";
	foreach($list AS $key=>$value)
	{
		$this->assign("rs",$value);
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