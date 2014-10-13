<?php
/***********************************************************
	Filename: ajax/www_add_atten.php
	Note	: 添加关注
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 10时28分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$site_id = $this->config_site["id"];
if(!$site_id)
{
	json_exit("站点信息获取异常，请联系管理员");
}
//获取project_id
$project = "atten";
if(!$project)
{
	json_exit("未指定项目，不能执行此操作");
}
$this->model("project");
$this->model("id");
$this->model("list");
$this->model("usergroup");
$id_rs = $this->id_model->get_id($project,$site_id);
if(!$id_rs || $id_rs["type_id"] != "project")
{
	json_exit("项目不存在，请联系管理员");
}
//判断项目权限
if(!$_SESSION["user_id"])
{
	json_exit("您没有权限执行此操作，请先登录");
}

//读取项目信息
$p_rs = $this->project_model->get_one($id_rs["id"]);
if(!$p_rs || !$p_rs["status"])
{
	json_exit("项目不存在或项目未启用，请联系管理员");
}
if(!$p_rs["module"])
{
	json_exit("未指定模型");
}
$this->model("module");
$m_rs = $this->module_model->get_one($p_rs["module"]);
if(!$m_rs || $m_rs["status"] != "1")
{
	json_exit("未启用模型，不能执行此操作");
}

$tid = $this->get("tid","int");
if(!$tid)
{
	json_exit("未指定ID");
}

//判断主题是否已收藏
$condition = "ext.title_id='".$tid."' AND ext.user='".$_SESSION["user_rs"]["user"]."' AND l.site_id='".$site_id."'";
$chk_rs = $this->list_model->only_check($condition,$p_rs["module"]);
if($chk_rs)
{
	json_exit("该主题您已收藏过，要删除，请到个人中心执行");
}

$rs = $this->list_model->get_one($tid);
$title = $rs["title"];
if(!$title)
{
	json_exit("操作异常，请联系管理员");
}

//加入收藏
$array = array();
$array["title"] = $title;
$array["dateline"] = $this->system_time;
$array["status"] = 1;
$array["hidden"] = 0;
$array["module_id"] = $p_rs["module"];
$array["project_id"] = $id_rs["id"];
$array["site_id"] = $site_id;
$insert_id = $this->list_model->save($array);//加入成功后
if(!$insert_id)
{
	json_exit("添加失败");
}
$ext_list = $this->module_model->fields_all($p_rs["module"]);
$tmplist = array();
$tmplist["id"] = $insert_id;
$tmplist["site_id"] = $p_rs["site_id"];
$tmplist["project_id"] = $id_rs["id"];
$tmplist["cate_id"] = 0;
foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
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
$tmplist["user"] = $_SESSION["user_rs"]["user"];
$tmplist["title_id"] = $tid;
$tmplist["link"] = "index.php?id=".$rs["identifier"];
$this->list_model->save_ext($tmplist,$p_rs["module"]);


json_exit("添加成功",true);