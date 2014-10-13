<?php
/***********************************************************
	Filename: {phpok}/api/comment_control.php
	Note	: 发表评论信息
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月6日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class comment_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	//获取评论信息
	function index_f()
	{
		$id = $this->get('id','int');
		if(!$id) $this->json('未指定ID号');
		$condition = "tid='".$id."' AND parent_id='0' ";
		$condition .= " AND (status=1 OR (status=0 AND (uid=".$_SESSION['user_id']." OR session_id='".session_id()."'))) ";
		$vouch = $this->get('vouch','int');
		if($vouch)
		{
			$condition .= " AND vouch=1 ";
		}
		$total = $this->model('reply')->get_total($condition);
		if(!$total) $this->json('暂无评论信息');
		//分割评论信息
		$pageid = $this->get($this->config['pageid'],'int');
		if(!$pageid) $pageid = 1;
		$psize = $this->get('psize','int');
		if(!$psize) $psize = 30;
		$start = ($pageid-1) * $psize;
		$rslist = $this->model('reply')->get_list($condition,$start,$psize,"","id ASC");
		//读子主题
		$idlist = $userlist = array();
		foreach($rslist AS $key=>$value)
		{
			if($value["uid"]) $userlist[] = $value["uid"];
			$idlist[] = $value["id"];
		}
		//读取回复的回复
		$idstring = implode(",",$idlist);
		$condition  = " parent_id IN(".$idstring.") ";
		$condition .= " AND (status=1 OR (status=0 AND (uid=".$_SESSION['user_id']." OR session_id='".session_id()."'))) ";
		$sublist = $this->model('reply')->get_list($condition,0,0);
		if($sublist)
		{
			$mylist = array();
			foreach($sublist AS $key=>$value)
			{
				if($value["uid"]) $userlist[] = $value["uid"];
				$mylist[$value["parent_id"]][] = $value;
			}
		}
		
		//获取会员信息
		if($userlist && count($userlist)>0)
		{
			$userlist = array_unique($userlist);
			$user_idstring = implode(",",$userlist);
			$condition = "u.status='1' AND u.id IN(".$user_idstring.")";
			$tmplist = $this->model('user')->get_list($condition,0,0);
			if($tmplist)
			{
				$userlist = array();
				foreach($tmplist AS $key=>$value)
				{
					$userlist[$value["id"]] = $value;
				}
				$tmplist = "";
			}
		}
		//整理回复列表
		foreach($rslist AS $key=>$value)
		{
			if($mylist && $mylist[$value["id"]])
			{
				foreach($mylist[$value["id"]] AS $k=>$v)
				{
					if($v["uid"] && $userlist)
					{
						$v["uid"] = $userlist[$v["uid"]];
					}
					$mylist[$value["id"]][$k] = $v;
				}
				$value["sonlist"] = $mylist[$value["id"]];
			}
			if($value["uid"] && $userlist)
			{
				$value["uid"] = $userlist[$value["uid"]];
			}
			$rslist[$key] = $value;
		}
		$pageurl = $this->url($id);
		$this->assign("rslist",$rslist);
		$this->assign("pageurl",$pageurl);
		$this->assign("pageid",$start);
		$this->assign("psize",$psize);
		$this->assign("total",$total);
		$html = $this->fetch("api_comment");
		$this->json($html,true,true,false);
	}

	//存储评论信息
	function save_f()
	{
		if(!$_SESSION['user_id']) $this->json('未会员不能执行此操作');
		//过滤防止恶意注册
		if(!$_SESSION['comment_spam_code'] || $_SESSION['comment_spam_code'] != $this->get('_spam'))
		{
			$this->json(1021);
		}
		$group_id = $_SESSION['user_rs']['group_id'];
		if($group_id)
		{
			$group_rs = $this->model('usergroup')->get_one($group_id);
			if(!$group_rs || !$group_rs['status']) $group_id = 0;
		}
		if(!$group_id)
		{
			$group_rs = $this->model('usergroup')->get_default(1);
			if(!$group_rs) $this->error(1010);
			$group_id = $group_rs['id'];
		}
		//获取主题ID
		$tid = $this->get('tid','int');
		if(!$tid) $this->json('未指定评论主题ID');
		//判断是否是子评论
		$parent_id = $this->get("parent_id","int");
		//获取内容
		$content = $this->get("content",'html');
		$ip = phpok_ip();
		if(!$content) $this->json('评论内容不能为空');
		//存储表单
		$array = array();
		$array["tid"] = $tid;
		$array["parent_id"] = $parent_id;
		$array["star"] = 3;
		$array["uid"] = $_SESSION["user_id"] ? $_SESSION["user_id"] : 0;
		$array["ip"] = $ip;
		$array["addtime"] = $this->time;
		$array["status"] = $group_rs['reply_status'];
		$array["session_id"] = $this->session->sessid();
		$array["content"] = $content;
		$this->model("reply")->save($array);
		//更新评论时间
		$update = array("replydate"=>$this->time);
		$this->model("list")->save($update,$tid);
		//清空缓存
		phpok_delete_cache("call,list");
		//更新随机码，防止恶意灌水
		$t = str_rand(10);
		$_SESSION['comment_spam_code'] = $t;
		$array = array("spam_code"=>$t,"reload"=>1);
		$this->json($array,true);
	}
}