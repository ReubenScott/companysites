<?php
/***********************************************************
	Filename:  api/ueditor_control.php
	Note	: Ueditor编辑器中涉及到的上传操作
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月3日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class ueditor_control extends base_control
{
	function __construct()
	{
		parent::control();
		//这里限制上传权限
		if(!$_SESSION['admin_id'])
		{
			exit("{'state':'您没有上传权限'}");
		}
	}

	//上传图片操作
	function image_f()
	{
		//上传图片
		$rs = $this->upload_base('upfile','dir','pictitle');
		//上传失败返回提示
		if($rs["status"] != "ok")
		{
			exit("{'state':'".$rs["error"]."'}");
		}
		$gd_rs = $this->model('gd')->get_editor_default();
		if($gd_rs)
		{
			$ext_rs = $this->model('gd')->get_pic($rs['id'],$gd_rs['id']);
			if($ext_rs) $rs['filename'] = $ext_rs['filename'];
		}
		exit("{'url':'".$rs["filename"]."','title':'".$rs["title"]."','original':'".$rs["title"]."','state':'SUCCESS'}");
	}

	function file_f()
	{
		//上传附件
		$rs = $this->upload_base('upfile');
		//上传失败返回提示
		if($rs["status"] != "ok")
		{
			exit("{'state':'".$rs["error"]."'}");
		}
		exit('{"id":"'.$rs['id'].'","url":"'.$rs["filename"].'","fileType":"'.$rs["ext"].'","original":"'.$rs["title"].'","state":"SUCCESS"}');
	}

	function edit_file($id)
	{
		if(!$id) return false;
		$gd_rs = $this->model('gd')->get_editor_default();
		if(!$gd_rs) return false;
		$rs = $this->model('res')->get_pic($id,$gd_rs['id']);
		if(!$rs) return false;
		return $rs['filename'];
	}

	//附件管理，只读取最新的100个附件信息
	function manage_f()
	{
		//取得编辑器默认读取的图片
		$gd_rs = $this->model('gd')->get_editor_default();
		if($gd_rs)
		{
			$condition = "e.gd_id='".$gd_rs['id']."'";
			$condition.= " AND res.ext IN('jpg','png','gif','jpeg') ";
			$list = $this->model('res')->edit_pic_list($condition,0,100);
		}
		else
		{
			$condition = "ext IN('jpg','png','gif','jpeg')";
			$list = $this->model('res')->get_list($condition,0,100,false);
		}
		if($list)
		{
			foreach($list AS $key=>$value)
			{
				echo  $value['filename'].'ue_separate_ue';
			}
		}
		exit;
	}

	//取得附件要存储的目录
	//cate：附件分类名称，或分类ID（当判断为整数时走分类ID，为字符串时走名称）
	//将返回一个数组，id，为附件分类ID,folder为目录
	function res_folder($cate="")
	{
		if($cate)
		{
			if(intval($cate) && intval($cate) == $cate)
			{
				$cate_rs = $this->model('res')->cate_one($cate);
			}
			if(!$cate_rs) $cate_rs = $this->model('res')->cate_one_from_title($cate);
		}
		//当不存在附件分类时，读取默认附件分类信息
		if(!$cate_rs) $cate_rs = $this->model('res')->cate_default();
		//当默认附件分类也不存在时，人工指定一个附件分类，且不动态生成
		if(!$cate_rs) $cate_rs = array('id'=>0,'root'=>'res/','folder'=>'/');
		//更新cate_rs
		$folder = $cate_rs["root"];
		if($cate_rs["folder"] && $cate_rs["folder"] != "/")
		{
			$folder .= date($cate_rs["folder"],$this->time);
		}
		if(!is_dir(ROOT.$folder)) $this->lib('file')->make(ROOT.$folder);
		//二次判断是否有文件夹
		if(!is_dir(ROOT.$folder)) $folder = $cate_rs['folder'];
		//
		if(substr($folder,-1) != "/") $folder .= "/";
		if(substr($folder,0,1) == "/") $folder = substr($folder,1);
		if($folder)
		{
			$folder = str_replace("//","/",$folder);
		}
		return array('id'=>$cate_rs['id'],'folder'=>$folder);
	}
	

	//远程抓图
	function remote_f()
	{
		$uri = $this->get("upfile");
		$uri = str_replace( "&amp;" , "&" , $uri );
		if(!$uri)
		{
			echo "{'url':'','tip':'没有图片信息！','srcUrl':'" . $uri . "'}";
			exit;
		}
		//防止超时
		set_time_limit( 0 );
		$imgUrls = explode( "ue_separate_ue" , $uri );
		$tmpNames = array();
		//设置存储附件的目录
		$cate_rs = $this->res_folder();
		$arraylist = array("jpg","gif","png","jpeg");
		foreach($imgUrls AS $key=>$imgUrl)
		{
			//如果获取的图片信息是data类型，则自动存储为png格式
			if(strtolower(substr($imgUrl,0,10)) == 'data:image')
			{
				$content = base64_decode(substr($imgUrl,22));
				$tmp_title = $this->time."_".$key;
				$new_filename = $tmp_title;
				$ext = 'png';
			}
			else
			{
				if(strpos($imgUrl,"http")!==0)
				{
					array_push( $tmpNames , "error" );
					continue;
				}
				$content = $this->lib('html')->get_content($imgUrl);
				$tmp_title = basename($imgUrl);
				$new_filename = substr(md5($imgUrl),9,16)."_".rand(0,99)."_".$key;
				$fileType = strtolower( strrchr( $imgUrl , '.' ));
				$ext = substr($fileType,1);
				if(!$ext) $ext = "png";
			}
            if(!$content)
            {
	            array_push( $tmpNames , "error" );
                continue;
            }
            $save_folder = ROOT.$cate_rs['folder'];
			$newfile = $save_folder.$new_filename.".".$ext;
			$this->lib('file')->save_pic($content,$newfile);
			if(!is_file($newfile))
			{
				array_push( $tmpNames , "error" );
				continue;
			}
			//迁移附件到数据库中
			$array = array();
			$array["cate_id"] = $cate_rs["id"];
			$array["folder"] = $cate_rs['folder'];
			$array["name"] = $new_filename.".".$ext;
			$array["ext"] = $ext;
			$array["filename"] = $cate_rs['folder'].$new_filename.".".$ext;
			$array["addtime"] = $this->time;
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
			$this->model('res')->gd_update($id);
			$efile = $this->edit_file($id);
			$file = $efile ? $efile : $cate_rs['folder'].$new_filename.".".$ext;
			array_push( $tmpNames ,  $file );
		}
		echo "{'url':'" . implode( "ue_separate_ue" , $tmpNames ) . "','tip':'远程图片抓取成功！','srcUrl':'" . $uri . "'}";
	}

	//涂鸦上传附件
	function scrawl_f()
	{
		$action = $this->get('action');
		if($action == 'tmpImg')
		{
			$rs = $this->upload_base('upfile');
			if($rs['status'] != 'ok')
			{
				exit("<script>parent.ue_callback('','ERROR')</script>");
			}
			else
			{
				exit("<script>parent.ue_callback('" . $rs[ "filename" ] . "','SUCCESS')</script>");
			}
		}
		$content = $this->get('content');
		$img = base64_decode($content);
		$filename = $this->time."_".rand(1,999).'.png';
		$cate_rs = $this->res_folder();
		//存储成为图片
		$newfile = ROOT.$cate_rs['folder'].$filename;
		$this->lib('file')->save_pic($img,$newfile);
		//当图片不存在时
		if(!is_file($newfile)) exit("{'url':'',state:'涂鸦失败，请检查'}");
		//存储附件信息
		$array = array('cate_id'=>$cate_rs['id'],'folder'=>$cate_rs['folder'],'name'=>$filename,'ext'=>'png');
		$array['filename'] = $cate_rs['folder'].$filename;
		$array['addtime'] = $this->time;
		$array['session_id'] = $this->session->sessid();
		$array['user_id'] = $_SESSION['user_id'];
		$array['title'] = substr($array['name'],0,-4);
		$img_ext = getimagesize(ROOT.$array['filename']);
		$my_ext = array("width"=>$img_ext[0],"height"=>$img_ext[1]);
		$array["attr"] = serialize($my_ext);
		//存储图片信息
		$id = $this->model('res')->save($array);
		if(!$id)
		{
			$this->lib('file')->rm(ROOT.$array['filename']);
			exit("{'url':'',state:'附件存储失败，请检查'}");
		}
		//更新附件操作
		$this->model('res')->gd_update($id);
		//取得编辑器图片
		$efile = $this->edit_file($id);
		$file = $efile ? $efile : $array['filename'];
		exit("{'url':'".$file."',state:'SUCCESS'}");
	}
	
	//基础上传
	function upload_base($input_name = "upfile",$cate="cateid",$title_id='title')
	{
		$rs = $this->lib('upload')->upload($input_name);
		//上传失败后，直接返回失败标识
		if($rs["status"] != "ok") return $rs;
		if($title_id && $this->get($title_id))
		{
			$title = $this->get($title_id);
			$rs['title'] = $title;
		}
		//获取附件分类
		$cate_rs = $this->res_folder($this->get($cate));
		$basename = basename($rs["filename"]);
		$save_folder = ROOT.$cate_rs['folder'];
		if($save_folder.$basename != $rs["filename"])
		{
			$this->lib('file')->mv($rs["filename"],$save_folder.$basename);
		}
		if(!is_file($save_folder.$basename))
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
		$array["folder"] = $cate_rs['folder'];
		$array["name"] = $basename;
		$array["ext"] = $rs["ext"];
		$array["filename"] = $cate_rs['folder'].$basename;
		$array["addtime"] = $this->time;
		if($this->is_utf8($rs["title"])) $rs["title"] = $this->charset($rs["title"],"GBK","UTF-8");
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
		$this->model('res')->gd_update($id);
		$rs = $this->model('res')->get_one($id);
		$rs["status"] = "ok";
		return $rs;
	}

}
?>