<?php
/***********************************************************
	Filename:  api/post_control.php
	Note	: 存储发布的项目信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月11日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class post_control extends base_control
{
	function __construct()
	{
		parent::control();
	}


	function save_f()
	{
		//过滤防止恶意发布信息
		if(!$_SESSION['project_spam'] || $_SESSION['project_spam'] != $this->get('_spam'))
		{
			$this->json('请不要恶意发布信息！');
		}
		//判断ID参数是否传过来
		$id = $this->get('id');
		if(!$id) $this->json('未指定项目ID');
		//判断项目是否存在
		$project_rs = $this->call->phpok('_project','phpok='.$id);
		if(!$project_rs || !$project_rs['status']) $this->json('项目信息不存在或未启用');
		//判断是否有表单功能
		if(!$project_rs['module']) $this->json("此项目没有表单功能，请检查");
		//判断是否有权限发布
		if($_SESSION['user_id'])
		{
			$group_id = $_SESSION['user_rs']['group_id'];
			if(!$group_id)
			{
				$group_rs = $this->model('usergroup')->get_default();
				if(!$group_rs || !$group_rs["status"]) $this->json(1010);
				$group_id = $group_rs["id"];
			}
			$group_rs = $this->model('usergroup')->get_one($group_id);
			if(!$group_rs || !$group_rs["status"]) $this->json(1011);
		}
		else
		{
			$group_rs = $this->model('usergroup')->get_guest(1);
			if(!$group_rs || !$group_rs["status"]) $this->json(1011);
		}
		if(!$group_rs['post_popedom'] || $group_rs['post_popedom'] == 'none')
		{
			$this->json('您没有发布权限');
		}
		$glist = explode(',',$group_rs['post_popedom']);
		if(!in_array($project_rs['id'],$glist))
		{
			$this->json('你没有权限发布');
		}
		$array = array();
		$array["title"] = $this->get("title");
		if(!$array['title'])
		{
			$tip = $project_rs['alias_title'] ? $project_rs['alias_title'] : '主题';
			$this->json($tip."不能为空");
		}
		$tid = $this->get('tid','int');
		if($tid)
		{
			$oldrs = $this->model('data')->arc(array('id'=>$tid));
			$array['dateline'] = $oldrs['dateline'];
			$array['status'] = $oldrs['status'];
		}
		else
		{
			$array["dateline"] = $this->time;
			$array["status"] = $group_rs['post_status'];
		}
		$array["hidden"] = 0;
		$array["module_id"] = $project_rs["module"];
		$array["project_id"] = $project_rs["id"];
		$array["site_id"] = $project_rs["site_id"];
		$array["cate_id"] = $this->get("cate_id","int");
		$array['user_id'] = $_SESSION['user_id'];
		if($tid)
		{
			$insert_id = $tid;
			$this->model('list')->save($array,$tid);
		}
		else
		{
			$insert_id = $this->model('list')->save($array);
		}
		if(!$insert_id)
		{
			$this->json("数据存储失败，请联系管理");
		}
		$ext_list = $this->model('module')->fields_all($project_rs["module"]);
		$tmplist = array();
		$tmplist["id"] = $insert_id;
		$tmplist["site_id"] = $project_rs["site_id"];
		$tmplist["project_id"] = $project_rs["id"];
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
		$this->model('list')->save_ext($tmplist,$project_rs["module"]);
		$spam = str_rand(10);
		$_SESSION['project_spam'] = $spam;
		$status = $group_rs['post_status'];
		delete_cache('call,list,ext');
		$this->json(array("status"=>$status,'_spam'=>$spam),true);
	}

}
?>