<?php
/***********************************************************
	Filename: {phpok}ajax/www_save.php
	Note	: 存储表单
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月30日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
if(!$_SESSION["user_id"])
{
	error("您还未登录",$this->url('login'));
}
$id = "article";
$p_rs = $this->model("project")->simple_project_from_identifier($id,$this->site['id']);
$error_url = $this->url("post","","id=article");
$content = $this->get("content");
if(!$content)
{
	error("请填写内容",$error_url,"error");
}
$title = phpok_cut($content,80);
$note = phpok_cut($content,255,'…');
$content = nl2br($content);
$array = array();
$array["title"] = $title;
$array["dateline"] = $this->system_time;
$array["status"] = 1;
$array["hidden"] = 0;
$array["module_id"] = $p_rs["module"];
$array["project_id"] = $p_rs["id"];
$array["site_id"] = $p_rs["site_id"];
$array["cate_id"] = $this->get("cate_id","int");
$insert_id = $this->model('list')->save($array);

$ext_list = $this->model('module')->fields_all($p_rs["module"]);
$tmplist = array();
$tmplist["id"] = $insert_id;
$tmplist["site_id"] = $p_rs["site_id"];
$tmplist["project_id"] = $p_rs["id"];
$tmplist["cate_id"] = $array["cate_id"];
if($ext_list)
{
	foreach($ext_list AS $key=>$value)
	{
		$val = ext_value($value);
		if($value["ext"])
		{
			$ext = unserialize($value["ext"]);
			foreach($ext AS $k=>$v)
			{
				$value[$k] = $v;
			}
		}
		if($value["form_type"] == "password")
		{
			$content = $rs[$value["identifier"]] ? $rs[$value["identifier"]] : $value["content"];
			$val = ext_password_format($val,$content,$value["password_type"]);
		}
		$tmplist[$value["identifier"]] = $val;
	}
}
$this->model('list')->save_ext($tmplist,$p_rs["module"]);
//存储扩展字段
$identifier = "content-".$insert_id;
$i_array = array();
$i_array["id"] = $insert_id;
$i_array["site_id"] = $p_rs["site_id"];
$i_array["phpok"] = $identifier;
$i_array["type_id"] = "content";
$this->model('id')->save($i_array);
$_back = "index.html";
phpok_delete_cache("call,list");
error("您的信息已经发布成功",$_back,"ok");