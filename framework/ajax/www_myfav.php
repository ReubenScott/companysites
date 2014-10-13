<?php
/***********************************************************
	Filename: {phpok}ajax/www_fav.php
	Note	: 我的收藏
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月28日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$offset = $this->get("startid","int");

$fav_list = $GLOBALS['app']->cache_data['fav_list'];
if(!$fav_list)
{
	json_exit("暂无收藏信息");
}
//取得主题列表
$total = count($fav_list);
$end = $offset + 10;
if($end>$total) $end = $total;
if($offset>=$end) json_exit("已完");
$i = 1;
$list = "";
foreach($fav_list AS $key=>$value)
{
	if(($key+1) >= $offset && ($key+1)<=$total)
	{
		$list[] = $value;
	}
}

if(!$list) json_exit("已完");
$string = implode(",",$list);
$list = $this->lib("ext")->title_list($string);
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