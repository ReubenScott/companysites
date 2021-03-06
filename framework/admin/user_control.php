<?php
/***********************************************************
	Filename: app/admin/user.php
	Note	: 会员中心
	Version : 3.0
	Author  : qinggan
	Update  : 2009-12-23
***********************************************************/
class user_control extends phpok_control
{
	var $popedom;
	function __construct()
	{
		parent::control();
		$this->model("user");
		$this->model("usergroup");
		$this->popedom = appfile_popedom("user");
		$this->assign("popedom",$this->popedom);
	}


	//会员列表
	function index_f()
	{
		if(!$this->popedom["list"]) error("你没有查看权限");
		$pageid = $this->get($this->config["pageid"],"int");
		if(!$pageid) $pageid = 1;
		$psize = $this->config["psize"];
		if(!$psize) $psize = 30;
		$keywords = $this->get("keywords");
		$page_url = $this->url("user");
		$condition = "1=1";
		if($keywords)
		{
			$this->assign("keywords",$keywords);
			$condition .= " AND u.user LIKE '%".$keywords."%'";
			$page_url.="&keywords=".rawurlencode($keywords);
		}
		$offset = ($pageid-1) * $psize;
		$rslist = $this->model('user')->get_list($condition,$offet,$psize);
		$count = $this->model('user')->get_count($keywords);
		$pagelist = phpok_page($page_url,$count,$pageid,$psize,"home=首页&prev=上一页&next=下一页&last=尾页&half=5&add=数量：[total]/[psize]，页码：[num]/[total_page]&always=1");
		$this->assign("total",$count);
		$this->assign("rslist",$rslist);
		$this->assign("pagelist",$pagelist);
		
		$xmlfile = $this->dir_root.'data/xml/admin_user.xml';
		$list = xml_to_array(file_get_contents($xmlfile));
		$this->assign("arealist",$list);

		$grouplist = $this->model('usergroup')->get_all("","id");
		$this->assign("grouplist",$grouplist);
		
		$this->view("user_list");
	}

	function add_f()
	{
		$this->set_f();
	}

	function set_f()
	{
		$id = $this->get("id","int");
		if($id)
		{
			if(!$this->popedom["modify"]) error("你没有修改权限");
			$rs = $this->model('user')->get_one($id);
		}
		else
		{
			if(!$this->popedom["add"]) error("你没有添加权限");
		}
		//创建扩展字段的表单
		//读取扩展属性
		$this->lib("form");
		$ext_list = $this->model('user')->fields_all();
		$extlist = array();
		foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
		{
			if($value["ext"])
			{
				$ext = unserialize($value["ext"]);
				foreach($ext AS $k=>$v)
				{
					$value[$k] = $v;
				}
			}
			$idlist[] = strtolower($value["identifier"]);
			if($rs[$value["identifier"]])
			{
				$value["content"] = $rs[$value["identifier"]];
			}
			$extlist[] = $this->lib('form')->format($value);
		}
		$this->assign("extlist",$extlist);
		//会员组
		$grouplist = $this->model('usergroup')->get_all();
		$this->assign("grouplist",$grouplist);
		if($rs)
		{
			$this->assign("rs",$rs);
			$this->assign("id",$id);
		}

		$this->view("user_add");
	}

	function chk_f()
	{
		$id = $this->get("id","int");
		$user = $this->get("user");
		if(!$user)
		{
			json_exit("会员账号不允许为空");
		}
		$rs_name = $this->model('user')->chk_name($user,$id);
		if($rs_name)
		{
			json_exit("会员账号已经存在");
		}
		json_exit("验证通过",true);
	}

	//存储信息
	function setok_f()
	{
		$id = $this->get("id","int");
		$array = array();
		$array["user"] = $this->get("user");
		$array['avatar'] = $this->get('avatar');
		$array['email'] = $this->get('email');
		$array['mobile'] = $this->get('mobile');
		$pass = $this->get("pass");
		if($pass)
		{
			$array["pass"] = password_create($pass);
		}
		else
		{
			if(!$id)
			{
				$array["pass"] = password_create("123456");
			}
		}
		if($id)
		{
			if(!$this->popedom["modify"]) error("你没有修改权限");
		}
		else
		{
			if(!$this->popedom["add"]) error("你没有添加权限");
		}
		$array["group_id"] = $this->get("group_id","int");
		if($this->popedom["status"])
		{
			$array["status"] = $this->get("status","int");
		}
		$regtime = $this->get("regtime");
		if(!$regtime) $regtime = date("Y-m-d H:i:s",$this->system_time);
		$array["regtime"] = strtotime($regtime);
		//存储扩展表信息
		$insert_id = $this->model('user')->save($array,$id);
		//读取扩展字段
 		$ext_list = $this->model('user')->fields_all();
 		$tmplist = array();
 		$tmplist["id"] = $insert_id;
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
		$this->model('user')->save_ext($tmplist);
		$note = $id ? "会员编辑成功" : "新会员添加成功";
		error($note,$this->url("user"),"ok");
	}

	function ajax_status_f()
	{
		if(!$this->popedom["status"]) exit("你没有启用/禁用权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("error:没有指定ID");
		}
		$rs = $this->model('user')->get_one($id);
		$status = $rs["status"] ? 0 : 1;
		$this->model('user')->set_status($id,$status);
		exit("ok");
	}

	function ajax_del_f()
	{
		if(!$this->popedom["delete"]) exit("error:你没有删除权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("error:没有指定ID");
		}
		$this->model('user')->del($id);
		exit("ok");
	}

	//会员字段管理器中涉及到的字段
	function fields_auto()
	{
		$this->form_list = $this->model('form')->form_all();
		$this->field_list = $this->model('form')->field_all();
		$this->format_list = $this->model('form')->format_all();
		$this->assign('form_list',$this->form_list);
		$this->assign("field_list",$this->field_list);
		$this->assign("format_list",$this->format_list);
		$this->popedom = appfile_popedom("user:fields");
		$this->assign("popedom",$this->popedom);
	}

	function fields_f()
	{
		$this->fields_auto();
		if(!$this->popedom["list"]) error("你没有查看权限");
		// 取得现有全部字段
		$condition = "area LIKE '%user%'";
		$used_list = $this->model('user')->fields_all("","identifier");
		if($used_list)
		{
			foreach($used_list AS $key=>$value)
			{
				$value["field_type_name"] = $this->field_list[$value["field_type"]];
				$value["form_type_name"] = $this->form_list[$value["form_type"]];
				$used_list[$key] = $value;
			}
		}
		$this->assign("used_list",$used_list);
		if($this->popedom["set"])
		{
			$fields_list = $this->model('fields')->get_all($condition,"identifier");
			if($fields_list)
			{
				foreach($fields_list AS $key=>$value)
				{
					$value["field_type_name"] = $this->field_list[$value["field_type"]];
					$value["form_type_name"] = $this->form_list[$value["form_type"]];
					$fields_list[$key] = $value;
				}
			}
			if($fields_list && $used_list)
			{
				$main_key = $this->model('user')->fields();
				$newlist = array();
				foreach($fields_list AS $key=>$value)
				{
					if(!$used_list[$key] && !in_array($key,$main_key))
					{
						$newlist[$key] = $value;
					}
				}
				$this->assign("fields_list",$newlist);
			}
			else
			{
				$this->assign("fields_list",$fields_list);
			}
		}
		$this->view("user_fields");
	}

	//自定义字段
	function fields_save_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error("你没有权限");
		$id_list = isset($_POST["add_field"]) ? $_POST["add_field"] : "";
		if($id_list && is_array($id_list))
		{
			$condition = "area LIKE '%user%'";
			$flist = $this->model('fields')->get_all($condition,"id");
			foreach($id_list AS $key=>$value)
			{
				if(!$flist[$value]) continue;
				$f_rs = $flist[$value];
				$title = $this->get("field_title_".$value);
				if(!$title) $title = $f_rs["title"];
				$note = $this->get("field_note_".$value);
				if(!$note) $note = $f_rs["note"];
				$tmp_array = array("title"=>$title,"note"=>$note);
				$tmp_array["identifier"] = $f_rs["identifier"];
				$tmp_array["field_type"] = $f_rs["field_type"];
				$tmp_array["form_type"] = $f_rs["form_type"];
				$tmp_array["form_style"] = $f_rs["form_style"];
				$tmp_array["format"] = $f_rs["format"];
				$tmp_array["content"] = $f_rs["content"];
				$tmp_array["taxis"] = $f_rs["taxis"];
				$tmp_array["ext"] = $f_rs["ext"] ? serialize(unserialize($f_rs["ext"])) : "";
				$this->model('user')->fields_save($tmp_array);
			}
		}
		$list = $this->model('user')->fields_all();
		if($list)
		{
			foreach($list AS $key=>$value)
			{
				$this->model('user')->create_fields($value);
			}
		}
		error("会员自定义字段配置成功",$this->url("user","fields"));
	}

	
	function field_edit_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error_open("你没有权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			error_open("未指定ID");
		}
		$rs = $this->model('user')->field_one($id);
		$this->assign("rs",$rs);
		$this->assign("id",$id);
		$this->view("user_field_set");
	}

	function field_edit_save_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) error_open("你没有权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			error_open("未指定ID");
		}
		$title = $this->get("title");
		$note = $this->get("note");
		$form_type = $this->get("form_type");
		$form_style = $this->get("form_style","html");
		$content = $this->get("content","html");
		$format = $this->get("format");
		$taxis = $this->get("taxis","int");
		$ext_form_id = $this->get("ext_form_id");
		$ext = array();
		if($ext_form_id)
		{
			$list = explode(",",$ext_form_id);
			foreach($list AS $key=>$value)
			{
				$val = explode(':',$value);
				if($val[1] && $val[1] == "checkbox")
				{
					$value = $val[0];
					$ext[$value] = $this->get($value,"checkbox");
				}
				else
				{
					$value = $val[0];
					$ext[$value] = $this->get($value);
				}
			}
		}
		$array = array();
		$array["title"] = $title;
		$array["note"] = $note;
		$array["form_type"] = $form_type;
		$array["form_style"] = $form_style;
		$array["format"] = $format;
		$array["content"] = $content;
		$array["taxis"] = $taxis;
		$array["ext"] = ($ext && count($ext)>0) ? serialize($ext) : "";
		$array["is_edit"] = $this->get("is_edit","int");
		$this->model('user')->fields_save($array,$id);
		$html = '<input type="button" value=" 确定 " class="submit" onclick="$.dialog.close();" />';
		error_open("自定义字段信息配置成功！","ok",$html);
	}
	//删除字段
	function field_delete_f()
	{
		$this->fields_auto();
		if(!$this->popedom["set"]) json_exit("你没有权限");
		$id = $this->get("id","int");
		if(!$id)
		{
			json_exit("未指定要删除的字段！");
		}
		$this->model('user')->field_delete($id);
		json_exit("删除成功！",true);
	}
}
?>