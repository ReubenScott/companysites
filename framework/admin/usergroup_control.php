<?php
/***********************************************************
	Filename:  admin/usergroup_control.php
	Note	: 会员组管理
	Version : 3.1
	Author  : qinggan
	Update  : 2011-03-13
***********************************************************/
class usergroup_control extends base_control
{
	var $popedom;
	function __construct()
	{
		parent::control();
		$this->popedom = appfile_popedom("usergroup");
		$this->assign("popedom",$this->popedom);
	}

	function index_f()
	{
		if(!$this->popedom["list"]) error("您没有查看权限");
		$rslist = $this->model('usergroup')->get_all();
		$this->assign("rslist",$rslist);
		$this->view("usergroup_list");
	}

	function set_f()
	{
		$id = $this->get("id","int");
		if($id)
		{
			if(!$this->popedom["modify"]) error("你没有编辑权限");
			$rs = $this->model('usergroup')->get_one($id);
			$read_popedom = $rs["read_popedom"] ? explode(",",$rs["read_popedom"]) : array("all");
			$post_popedom = $rs["post_popedom"] ? explode(",",$rs["post_popedom"]) : array("none");
			$this->assign("id",$id);
			$ext_module = "usergroup-".$id;
		}
		else
		{
			if(!$this->popedom["add"]) error("你没有添加权限");
			$ext_module = "add-usergroup";
			//阅读权限
			$read_popedom = array("all");
			//发布权限
			$post_popedom = array("none");
		}
		$this->assign("read_popedom",$read_popedom);
		$this->assign("post_popedom",$post_popedom);
		$this->assign("rs",$rs);
		//项目列表
		$site_id = $_SESSION["admin_site_id"];
		$rslist = $this->model('project')->get_all_project($site_id);
		$this->assign("project_list",$rslist);
		
		//自定义扩展字段
		$this->assign("ext_module",$ext_module);
		$forbid = array("id","identifier");
		$forbid_list = $this->model('ext')->fields("group,id");
		$forbid = array_merge($forbid,$forbid_list);
		$forbid = array_unique($forbid);
		$this->assign("ext_idstring",implode(",",$forbid));

		//会员字段列表
		$all_fields_list = $this->model('user')->fields_all();
		if($all_fields_list)
		{
			$this->assign("all_fields_list",$all_fields_list);
			$fields_list = "";
			if($rs["fields"])
			{
				$fields_list = explode(",",$rs["fields"]);
			}
			$this->assign("fields_list",$fields_list);
		}
		
		$this->view("usergroup_set");
	}

	//存储信息
	function setok_f()
	{
		$id = $this->get("id","int");
		$title = $this->get("title");
		$error_url = $this->url("usergroup","set");
		if($id)
		{
			if(!$this->popedom["modify"]) error("你没有编辑权限");
			$error_url .= "&id=".$id;
		}
		else
		{
			if(!$this->popedom["add"]) error("你没有添加权限");
		}
		if(!$title)
		{
			error("组名称不允许为空！",$error_url,"error");
		}
		$array = array();
		$array["title"] = $title;
		$read_popedom = $this->get("read_popedom");
		if($read_popedom && is_array($read_popedom)) $read_popedom = implode(",",$read_popedom);
		$post_popedom = $this->get("post_popedom");
		if($post_popedom && is_array($post_popedom))
		{
			foreach($post_popedom AS $key=>$value)
			{
				if($value == 'none') unset($post_popedom[$key]);
			}
			$post_popedom = implode(",",$post_popedom);
		}
		$array["read_popedom"] = $read_popedom;
		$array["post_popedom"] = $post_popedom;
		$array["post_status"] = $this->get("post_popedom","int");
		$array["reply_status"] = $this->get("reply_status","int");
		$array["is_open"] = $this->get("is_open","int");
		$array["taxis"] = $this->get("taxis","int");
		$array["register_status"] = $this->get("register_status");
		$array["tbl_id"] = $this->get("tbl_id","int");
		$fields_list = $this->get("fields_list");
		if($fields_list && is_array($fields_list))
		{
			if(in_array("all",$fields_list))
			{
				$array["fields"] = "";
			}
			else
			{
				$array["fields"] = implode(",",$fields_list);
			}
		}
		if($id)
		{
			$this->model('usergroup')->save($array,$id);
			//存储扩展字段
			ext_save("usergroup-".$id);
			$this->model('temp')->clean("usergroup-".$id,$_SESSION["admin_id"]);
		}
		else
		{
			$id = $this->model('usergroup')->save($array);
			if($id)
			{
				ext_save("add-usergroup-ext-id",true,"usergroup-".$id);
				$this->model('temp')->clean("add-usergroup-ext-id",$_SESSION["admin_id"]);
			}
		}
		error("会员组信息添加/存储成功",$this->url("usergroup"),"ok");
	}

	function ajax_del_f()
	{
		if(!$this->popedom["delete"]) exit("error:你没有删除权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("error:没有指定ID");
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs)
		{
			exit("会员组信息不存在");
		}
		if($rs["is_default"])
		{
			exit("默认会员组不能删除");
		}
		if($rs["is_guest"])
		{
			exit("默认游客组不能删除");
		}
		$this->model('usergroup')->del($id);
		exit("ok");
	}

	function default_f()
	{
		if(!$_SESSION["admin_rs"]["if_system"])
		{
			exit("您没有此操作权限，设置默认会员组只有系统管理员能设置，请联系系统管理员");
		}
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("未指定会员组ID");
		}
		$this->model('usergroup')->set_default($id);
		exit("ok");
	}

	function guest_f()
	{
		if(!$_SESSION["admin_rs"]["if_system"])
		{
			exit("您没有此操作权限，设置游客组只有系统管理员能设置，请联系系统管理员");
		}
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("未指定会员组ID");
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs)
		{
			exit("会员组信息不存在");
		}
		if($rs["is_default"])
		{
			exit("默认会员组不能设为游客组");
		}
		$this->model('usergroup')->set_guest($id);
		exit("ok");
	}

	function status_f()
	{
		if(!$this->popedom["status"]) error("你没有启用/禁用权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("未指定会员组ID");
		}
		$rs = $this->model('usergroup')->get_one($id);
		if(!$rs)
		{
			exit("会员组信息不存在");
		}
		$status = $this->get("status","int");
		$this->model('usergroup')->set_status($id,$status);
		exit("ok");
	}
}
?>