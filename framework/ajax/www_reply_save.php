<?php
/***********************************************************
	Filename: ajax/www_reply_save.php
	Note	: 存储评论信息
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年06月21日 14时55分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
if(!$_SESSION["user_id"])
{
	json_exit("游客不能评论");
}
$tid = $this->get("tid","int");
$parent_id = $this->get("parent_id","int");
$content = $this->get("content");
$ip = phpok_ip();
if(!$tid || !$content)
{
	json_exit("数据存储失败，没有指定主题或评论内容为空");
}
$array = array();
$array["tid"] = $tid;
$array["parent_id"] = $parent_id;
$array["star"] = 3;
$array["uid"] = $_SESSION["user_id"] ? $_SESSION["user_id"] : 0;
$array["ip"] = $ip;
$array["addtime"] = $this->system_time;
$array["status"] = 1;
$array["session_id"] = $this->session->sessid();
$array["content"] = $content;
$this->model("reply")->save($array);

$update = array("replydate"=>$this->system_time);
//更新评论时间
$this->model("list")->save($update,$tid);

$rs = $this->model("content")->get_one($tid);
$update = array("reply_count"=>$rs["reply_count"]+1);
//更新回复数量
$this->model("list")->update_ext($update,$rs["module_id"],$tid);
phpok_delete_cache("call,list");

//$array["uid"] = $_SESSION["user_rs"];

json_exit($array,true);
?>