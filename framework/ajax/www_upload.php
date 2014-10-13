<?php
/***********************************************************
	Filename: {phpok}ajax/www_upload.php
	Note	: 上传附件
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月30日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
$cateid = $this->get("cateid","int");
$rs = $this->lib('upload')->upload("fileupload");
if($rs["status"] != "ok")
{
	json_exit($rs);
}
# 存储附件
$cate_rs = $this->model('res')->cate_one($cateid);
if(!$cate_rs)
{
	$cate_rs["id"] = 0;
	$cate_rs["root"] = $this->dir_root."res/";
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
	json_exit($rs);
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
	json_exit($rs);
}
# 更新后台小图
$rs = $this->model('res')->get_one($id);
if(!$rs) json_exit("附件不存在");
$arraylist = array("jpg","gif","png","jpeg");
if(in_array($rs["ext"],$arraylist))
{
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
$rs["ico"] = $tmp["ico"];

$this->model('res')->ext_delete($id);//清除现在扩展图片存储信息
$id = $rs["id"];
if(in_array($rs["ext"],$arraylist))
{
	$gdlist = $this->model('gd')->get_all();
	if($gdlist)
	{
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
	}
}
json_exit($rs,true);