<?php
/***********************************************************
	Filename: {phpok}ajax/www_atten.php
	Note	: 加关注
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年10月1日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
if(!$_SESSION['user_id'])
{
	json_exit("您还未登录");
}

$site_id = $this->site["id"];
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
$project_id = array_search($project,$this->cache_data['id_list']);

//读取项目信息
$p_rs = $this->model("project")->get_one($project_id);
if(!$p_rs || !$p_rs["status"])
{
	json_exit("项目不存在或项目未启用，请联系管理员");
}
if(!$p_rs["module"])
{
	json_exit("未指定模型");
}
$this->model("module");
$m_rs = $this->model("module")->get_one($p_rs["module"]);
if(!$m_rs || $m_rs["status"] != "1")
{
	json_exit("未启用模型，不能执行此操作");
}

$tid = $this->get("id","int");
if(!$tid)
{
	json_exit("未指定ID");
}
$user_rs = $this->model("user")->get_one($tid);

//判断主题是否已收藏
$condition = "l.title='".$user_rs['user']."' AND ext.post_uid='".$_SESSION["user_id"]."' AND l.site_id='".$site_id."'";
$chk_rs = $this->model("list")->get_one_condition($condition,$p_rs["module"]);
if($chk_rs)
{
	$this->model("list")->delete($chk_rs['id']);
	json_exit(array('tips'=>"取消关注操作成功",'action'=>'-'),true);
}

$title = $user_rs["user"];
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
$array["project_id"] = $p_rs["id"];
$array["site_id"] = $site_id;
$insert_id = $this->model('list')->save($array);//加入成功后
if(!$insert_id)
{
	json_exit("添加失败");
}
$ext_list = $this->model('module')->fields_all($p_rs["module"]);
$tmplist = array();
$tmplist["id"] = $insert_id;
$tmplist["site_id"] = $p_rs["site_id"];
$tmplist["project_id"] = $p_rs["id"];
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
$tmplist["post_uid"] = $_SESSION["user_id"];
$this->model('list')->save_ext($tmplist,$p_rs["module"]);

//存储扩展字段
$identifier = "content-".$insert_id;
$i_array = array();
$i_array["id"] = $insert_id;
$i_array["site_id"] = $p_rs["site_id"];
$i_array["phpok"] = $identifier;
$i_array["type_id"] = "content";
$this->model('id')->save($i_array);


json_exit(array('tips'=>"添加关注成功",'action'=>'+'),true);

?>