<?php
/***********************************************************
	Filename: {phpok}/form/upload_admin.php
	Note	: 附件属性
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-14 05:37
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class upload_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
		$this->app->model("res");
	}

	function format($rs)
	{
		if($rs["content"])
		{
			if(is_string($rs["content"]))
			{
				$res = $this->app->res_model->get_list_from_id($rs["content"]);
			}
			else
			{
				$is_list = $rs["content"]["id"] ? false : true;
				$res = array();
				if($is_list)
				{
					foreach($rs["content"]["info"] AS $key=>$value)
					{
						$res[$value["id"]] = $value;
					}
				}
				else
				{
					$res[$rs["content"]["id"]] = $rs["content"];
				}
				$id_list = array_keys($res);
				$rs["content"] = implode(",",$id_list);
			}
			$rs["content_list"] = $res; //附件列表
		}
		else
		{
			$rs["content_list"] = array(); //附件列表
		}
		$type_list = $GLOBALS['app']->model('res')->type_list();
		$type_id = $rs["upload_type"];
		if($rs["upload_type"] && $type_list[$rs["upload_type"]])
		{
			$rs["upload_type"] = $type_list[$rs["upload_type"]];
		}
		else
		{
			$str_array = array();
			foreach($type_list AS $key=>$value)
			{
				$str_array[] = $value["ext"];
			}
			$str = implode(',',$str_array);
			$swfupload = array();
			$str_array = explode(",",$str);
			foreach($str_array AS $key=>$value)
			{
				$swfupload[] = "*.".$value;
			}
			$swfupload = implode(";",$swfupload);
			$rs["upload_type"] = array(
				"id"=>"file",
				"title"=>"附件",
				"ext"=>$str,
				"swfupload"=>$swfupload
			);
		}
		$rs["upload_type"]["id"] = $type_id;
		$spam_id = str_rand(10);
		$spam = md5(str_rand(10));
		$_SESSION['uploadify_spam_code_'.$spam_id] = $spam;
		$rs['spam_id'] = $spam_id;
		$rs['spam'] = $spam;
		$GLOBALS['app']->assign("upload_rs",$rs);
		$file = $this->app->dir_phpok."form/html/upload_form_www.html";
		$content = $this->app->fetch($file,'abs-file',false);
		$this->app->unassign("upload_rs");
		$this->app->unassign("rslist");
		return $content;
	}
}
?>