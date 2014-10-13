<?php
/***********************************************************
	Filename: {phpok}/admin/ueditor_control.php
	Note	: Ueditor 编辑器中涉及到上传的操作
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年06月26日 19时04分
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class ueditor_control extends phpok_control
{
	function __construct()
	{
		parent::control();
		$this->model("res");
		$this->model("gd");
		$this->lib("json");
		$this->lib("file");
		$this->lib("html");
	}

	//上传图片操作
	function image_up_f()
	{
		$rs = $this->upload_base();
		if($rs["status"] != "ok")
		{
			//file_put_contents("tmp.php",print_r($rs,true)."------");
			exit("{'state':'".$rs["error"]."'}");
			//exit('{"state":"'.$rs['error'].'"}');
		}
		$title = $this->get("pictitle");
		if($title)
		{
			$tmp = array("title"=>$title);
			$this->model('res')->save($tmp,$rs["id"]);
			$rs["title"] = $title;
		}
		$tmp = array("title"=>$rs['title']);
		$tmp["url"] = $rs["filename"];
		$tmp["original"] = $rs["title"];
		$tmp["status"] = "SUCCESS";
		//echo "{'url':'" . $rs["filename"] . "','title':'" . $rs['title'] . "','original':'" . $rs["name"] . "','state':'SUCCESS'}";
		exit("{'url':'".$tmp["url"]."','title':'".$tmp["title"]."','original':'".$tmp["original"]."','state':'SUCCESS'}");
		//exit($this->lib('json')->encode($tmp));
	}

	//上传文件
	function file_up_f()
	{
		$rs = $this->upload_base();
		if($rs["status"] != "ok")
		{
			exit('{"state":"'.$rs['error'].'"}');
		}
		exit("{'url':'".$rs["filename"]."','fileType':'".$rs["ext"]."','original':'".$rs["title"]."','state':'SUCCESS'}");
	}

	//远程抓图
	function remote_image_f()
	{
		$uri = $this->get("upfile");
		$uri = str_replace( "&amp;" , "&" , $uri );
		if(!$uri)
		{
			echo "{'url':'','tip':'没有图片信息！','srcUrl':'" . $uri . "'}";
			exit;
		}
		$this->lib("html");
		set_time_limit( 0 );
		$imgUrls = explode( "ue_separate_ue" , $uri );
		$tmpNames = array();
		//设置存储附件的目录
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
		$arraylist = array("jpg","gif","png","jpeg");
		foreach($imgUrls AS $key=>$imgUrl)
		{
			if(strpos($imgUrl,"http")!==0)
			{
                array_push( $tmpNames , "error" );
                continue;
            }
            $content = $this->lib('html')->get_content($imgUrl);
            if(!$content)
            {
	            array_push( $tmpNames , "error" );
                continue;
            }
            $tmp_title = basename($imgUrl);
            $new_filename = substr(md5($imgUrl),9,16)."_".rand(0,99)."_".$key;
            $fileType = strtolower( strrchr( $imgUrl , '.' ));
            $ext = substr($fileType,1);
            if(!$ext) $ext = "png";
			$save_folder = $this->dir_root.$folder;
			$newfile = $save_folder.$new_filename.".".$ext;
			$this->lib('file')->save_pic($content,$newfile);
			if(!file_exists($newfile))
			{
				array_push( $tmpNames , "error" );
                continue;
			}
			//记录到数据库中
			# 将图片移到新目录
			$array = array();
			$array["cate_id"] = $cate_rs["id"];
			$array["folder"] = $folder;
			$array["name"] = $new_filename.".".$ext;
			$array["ext"] = $ext;
			$array["filename"] = $folder.$new_filename.".".$ext;
			$array["addtime"] = $this->system_time;
			if($tmp_title && !$this->is_utf8($tmp_title))
			{
				$tmp_title = $this->charset($tmp_title,"GBK","UTF-8");
			}
			$array["title"] = $tmp_title ? str_replace(".".$ext,"",$tmp_title) : $new_filename;
			if(in_array($ext,$arraylist))
			{
				$img_ext = getimagesize($newfile);
				$my_ext = array("width"=>$img_ext[0],"height"=>$img_ext[1]);
				if(function_exists("exif_read_data"))
				{
					$ext_note = exif_read_data($newfile,0,true);
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
				array_push( $tmpNames , "error" );
                continue;
			}
			//更新后台小图
			$this->gd_admin($id);
			//更新附件方案
			$this->gd($id);
			array_push( $tmpNames ,  $folder.$new_filename.".".$ext );
		}
		echo "{'url':'" . implode( "ue_separate_ue" , $tmpNames ) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
	}
    
	//管理本地图片
	function manage_f()
	{
		//
	}

	//基础上传
	function upload_base($input_name = "upfile")
	{
		$cateid = $this->get("cateid","int");
		$this->lib("upload");
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
		if(!$this->is_utf8($rs["title"]))
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
		//存储图片信息
		$id = $this->model('res')->save($array);
		if(!$id)
		{
			$this->lib('file')->rm($save_folder.$basename);
			$rs = array();
			$rs["status"] = "error";
			$rs["error"] = "图片存储失败";
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
			$ico = $this->lib('gd')->thumb($rs["filename"],$id);
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
			if(!file_exists($ico))
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
			$gd_tmp = $this->lib('gd')->gd($rs["filename"],$id,$value);
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