<?php
/***********************************************************
	Filename:  admin/upload_control.php
	Note	: 附件上传操作
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-01-09 10:51
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class upload_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	//一般上传，返回Ajax组信息
	function index_f()
	{
		if($_SESSIO["user_id"])
		{
			$gid = $_SESSION["user_rs"]["group_id"];
			$group_rs = $gid ? $this->model("usergroup")->get_one($id) : $this->model("usergroup")->get_default();
			
		}
		else
		{
			$group_rs = $this->model("usergroup")->get_guest();
		}
		if(!$group_rs)
		{
			$array = array("staus"=>"error","error_id"=>1005,"error"=>"无法获取会员组信息");
			exit($this->lib("json")->encode($array));
		}
		if(!$group_rs["upload"])
		{
			$array = array("staus"=>"error","error_id"=>1006,"error"=>"没有上传权限");
			exit($this->lib("json")->encode($array));
		}
		$rs = $this->upload_base("Filedata",$group_rs['upload']);
		exit($this->lib('json')->encode($rs));
	}

	function base_f()
	{
		$rs = $this->upload_base("Filedata");
		if($rs["status"] == "ok")
		{
			error("图片上传成功",$this->url("res","add"),"ok");
		}
		else
		{
			error($rs["error"],$this->url("res","add"),"error");
		}
	}

	# 编辑器上传返回
	function kindedit_f()
	{
		$rs = $this->upload_base("imgFile");
		if($rs["status"] == "ok")
		{
			$gd_rs = $this->model('gd')->get_editor_default();
			if(!$gd_rs)
			{
				$array = array("error"=>0,"url"=>$rs["filename"]);
			}
			else
			{
				$ext_pic = $this->model('res')->get_pic($rs["id"],$gd_rs["id"]);
				$array = array("error"=>0,"url"=>$ext_pic["filename"]);
			}
			exit($this->lib('json')->encode($array));
		}
		else
		{
			$array = array("error"=>1,"message"=>$rs["content"]);
			exit($this->lib('json')->encode($array));
		}
	}

	# 附件替换
	function replace_f()
	{
		$rs = $this->upload("Filedata");
		if($rs["status"] != "ok")
		{
			exit($this->lib('json')->encode($rs));
		}
		$id = $this->get("id","int");
		if(!$id)
		{
			$this->lib('file')->rm($rs["filename"]);
			json_exit("没有指定资源");
		}
		$old_rs = $this->model('res')->get_one($id);
		if(!$old_rs)
		{
			$this->lib('file')->rm($rs["filename"]);
			json_exit("资源不存在");
		}
		$arraylist = array("jpg","gif","png","jpeg");
		$my_ext = array();
		if(in_array($rs["ext"],$arraylist))
		{
			$img_ext = getimagesize($rs["filename"]);
			$my_ext["width"] = $img_ext[0];
			$my_ext["height"] = $img_ext[1];
			if(function_exists("exif_read_data"))
			{
				$ext_note = exif_read_data($rs["filename"],0,true);
				if($ext_note)
				{
					foreach($ext_note AS $key=>$value)
					{
						$my_ext[$key] = $value;
					}
				}
			}
		}
		# 替换资源
		$this->lib('file')->mv($rs["filename"],$old_rs["filename"]);
		$tmp = array("addtime"=>$this->system_time,"ico"=>"");
		$tmp["attr"] = serialize($my_ext);
		$this->model('res')->save($tmp,$id);
		//更新GD
		$this->gd_admin($id);
		$this->gd($id);
		$rs = $this->model('res')->get_one($id);
		$rs["status"] = "ok";
		exit($this->lib('json')->encode($rs));
	}

	# 基本上传
	function upload_base($input_name = "Filedata",$upload_type)
	{
		$cateid = $this->get("cateid","int");
		$rs = $this->upload($input_name);
		if($rs["status"] != "ok")
		{
			return $rs;
		}
		# 存储附件
		$cate_rs = $this->model('res')->cate_one($cateid);
		if(!$cate_rs)
		{
			$cate_rs["id"] = 0;
			$cate_rs["root"] = ROOT."res/";
			$cate_rs["folder"] = "/";
		}
		$folder = $cate_rs["root"];
		if($cate_rs["folder"] && $cate_rs["folder"] != "/")
		{
			$folder .= date($cate_rs["folder"],$this->system_time);
		}
		if(!file_exists($folder))
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
		$save_folder = ROOT.$folder;
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
		if($this->lib('trans')->is_utf8($rs["title"]))
		{
			$rs["title"] = $this->lib('trans')->charset($rs["title"],"GBK","UTF-8");
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

	# 上传附件
	function upload($id="Filedata")
	{
		return $this->lib('upload')->upload($id);
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
			$ico = $this->lib('gd')->thumb(ROOT.$rs["filename"],$id);
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
			if(!file_exists($ico) && !file_exists(ROOT.$ico))
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
		$this->lib("gd");
		$tmp = array();
		$gdlist = $this->model('gd')->get_all();
		if(!$gdlist) return false;
		foreach($gdlist AS $key=>$value)
		{
			$array = array();
			$array["res_id"] = $id;
			$array["gd_id"] = $value["id"];
			$array["filetime"] = $this->system_time;
			$gd_tmp = $this->lib('gd')->gd(ROOT.$rs["filename"],$id,$value);
			if($gd_tmp)
			{
				$array["filename"] = $rs["folder"].$gd_tmp;
				$this->model('res')->save_ext($array);
			}
		}
		return true;
	}
}
?>