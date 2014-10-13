<?php
/***********************************************************
	Filename: {phpok}/api/upload_control.php
	Note	: 上传操作
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月5日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class upload_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	//存储上传的数据
	function save_f()
	{
		$tmpid = $this->get('_spam_id');
		$sessid = 'uploadify_spam_code';
		if($tmpid) $sessid = 'uploadify_spam_code_'.$tmpid;
		if(!$_SESSION[$sessid] || $_SESSION[$sessid] != $this->get('_spam'))
		{
			$this->json(1003);
		}
		$rs = $this->upload_base("Filedata");
		unset($_SESSION[$sessid]);
		exit($this->lib('json')->encode($rs));
	}

	# 基本上传
	function upload_base($input_name = "Filedata")
	{
		$cateid = $this->get("cateid","int");
		$rs = $this->lib('upload')->upload($input_name);
		if($rs["status"] != "ok")
		{
			return $rs;
		}
		# 存储附件
		$cate_rs = $this->model('res')->cate_one($cateid);
		if(!$cate_rs)
		{
			$cate_rs["id"] = 0;
			$cate_rs["root"] = $this->dir_root."res/";
			$cate_rs["folder"] = "Y/md/";
		}
		$folder = $cate_rs["root"];
		if($cate_rs["folder"] && $cate_rs["folder"] != "/")
		{
			$folder .= date($cate_rs["folder"],$this->system_time);
		}
		if(!is_dir($folder))
		{
			$this->lib('file')->make($folder);
		}
		if(substr($folder,-1) != "/") $folder .= "/";
		if(substr($folder,0,1) == "/") $folder = substr($folder,1);
		if($folder)
		{
			$folder = str_replace("//","/",$folder);
		}
		//存储目录
		$basename = basename($rs["filename"]);
		$save_folder = $this->dir_root.$folder;
		if($save_folder.$basename != $rs["filename"])
		{
			$this->lib('file')->mv($rs["filename"],$save_folder.$basename);
		}
		if(!file_exists($save_folder.$basename))
		{
			$this->lib('file')->rm($rs["filename"]);
			$rs = array();
			$rs["status"] = "error";
			$rs["error"] = "图片迁移失败";
			$rs["error_id"] = "1004";
			return $rs;
		}
		# 将图片移到新目录
		$array = array();
		$array["cate_id"] = $cate_rs["id"];
		$array["folder"] = $folder;
		$array["name"] = $basename;
		$array["ext"] = $rs["ext"];
		$array["filename"] = $folder.$basename;
		$array["addtime"] = $this->system_time;
		if($this->is_utf8($rs["title"]))
		{
			$rs["title"] = $this->charset($rs["title"],"GBK","UTF-8");
		}
		$array["title"] = str_replace(".".$rs["ext"],"",$rs["title"]);
		$arraylist = array("jpg","gif","png","jpeg");
		if(in_array($rs["ext"],$arraylist))
		{
			$img_ext = getimagesize($save_folder.$basename);
			$my_ext = array("width"=>$img_ext[0],"height"=>$img_ext[1]);
			if(function_exists("exif_read_data"))
			{
				$ext_note = exif_read_data($save_folder.$basename,0,true);
				if($ext_note)
				{
					foreach($ext_note AS $key=>$value)
					{
						$my_ext[$key] = $value;
					}
				}
			}
			$array["attr"] = serialize($my_ext);
		}
		$array["session_id"] = $this->session->sessid();
		$array['user_id'] = $_SESSION['user_id'] ? $_SESSION['user_id'] : '';
		//存储图片信息
		$id = $this->model('res')->save($array);
		if(!$id)
		{
			$this->lib('file')->rm($save_folder.$basename);
			$rs = array();
			$rs["status"] = "error";
			$rs["error"] = "图片存储失败";
			$rs["error_id"] = "1005";
			return $rs;
		}
		# 更新后台小图
		$this->gd_admin($id);
		# 更新附件方案
		$this->gd($id);
		$rs = $this->model('res')->get_one($id);
		$rs["status"] = "ok";
		return $rs;
	}

	# 更新ICO图片
	function gd_admin($id)
	{
		if(!$id) return false;
		$rs = $this->model('res')->get_one($id);
		if(!$rs) return false;
		$arraylist = array("jpg","gif","png","jpeg");
		if(in_array($rs["ext"],$arraylist))
		{
			$this->lib("gd");
			$ico = $this->lib('gd')->thumb($this->dir_root.$rs["filename"],$id);
			if(!$ico)
			{
				$ico = "images/filetype-large/".$rs["ext"].".jpg";
				if(!file_exists($ico))
				{
					$ico = "images/filetype-large/unknow.jpg";
				}
			}
			else
			{
				$ico = $rs["folder"].$ico;
			}
			$tmp = array();
			$tmp["ico"] = $ico;
		}
		else
		{
			$tmp = array();
			$ico = "images/filetype-large/".$rs["ext"].".jpg";
			if(!file_exists($ico) && !file_exists($this->dir_root.$ico))
			{
				$ico = "images/filetype-large/unknow.jpg";
			}
			$tmp["ico"] = $ico;
		}
		$this->model('res')->save($tmp,$id);
		return true;
	}

	# 更新附件的GD方案
	function gd($id)
	{
		if(!$id) return false;
		$this->model('res')->ext_delete($id);//清除现在扩展图片存储信息
		$rs = $this->model('res')->get_one($id);
		if(!$rs || !is_array($rs)) return false;
		$arraylist = array("jpg","gif","png","jpeg");
		$id = $rs["id"];
		if(!in_array($rs["ext"],$arraylist)) return false;
		$tmp = array();
		$gdlist = $this->model('gd')->get_all();
		if(!$gdlist) return false;
		foreach($gdlist AS $key=>$value)
		{
			$array = array();
			$array["res_id"] = $id;
			$array["gd_id"] = $value["id"];
			$array["filetime"] = $this->system_time;
			$gd_tmp = $this->lib('gd')->gd($this->dir_root.$rs["filename"],$id,$value);
			if($gd_tmp)
			{
				$array["filename"] = $rs["folder"].$gd_tmp;
				$this->model('res')->save_ext($array);
			}
		}
		return true;
	}

}