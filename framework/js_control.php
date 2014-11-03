<?php
/***********************************************************
	Filename:  js_control.php
	Note	: JS控制器，这里用来控制后台的JS信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-29 20:22
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class js_control extends base_control
{
	function __construct()
	{
		parent::control();
		$this->lib("file");
		$this->lib("html");
		$this->model("res");
		$this->model("gd");
	}

	//WEB前台通用JS
	function index_f()
	{
		$this->js_base();
		echo $this->file_lib->cat("framework/form.js");
		echo "\n";
		$ext = $this->get("ext");
		$autoload_js = $this->config["autoload_js"];
		if($autoload_js)
		{
			$ext = $ext ? ",".$autoload_js : $autoload_js;
		}
		if(!$ext) exit;
		$list = explode(",",$ext);
		$list = array_unique($list);
		foreach($list AS $key=>$value)
		{
			$value = trim($value);
			$js1_file = ROOT."js/".$value;
			$js2_file = "framework/js/".$value;
			$jsfile = is_file($js1_file) ? $js1_file : $js2_file;
			if(!is_file($jsfile)) $jsfile = ROOT.$value;
			if($value && is_file($jsfile) && $value != "jquery.js")
			{
				echo $this->file_lib->cat($jsfile);
				echo "\n";
			}
		}
		exit;
	}

	# 人工指定js
	function ext_f()
	{
		$this->js_header();
		$js = $this->get("js");
		if(!$js) exit("\n");
		$list = explode(",",$js);
		echo "\n";
		foreach($list AS $key=>$value)
		{
			$value = trim($value);
			if(!$value) continue;
			//判断后缀是否是.js
			if(strtolower(substr($value,-3)) != '.js') $value .= '.js';
			//判断文件是否存在
			$file = is_file('framework/js/'.$value) ? 'framework/js/'.$value : ROOT."js/".$value;
			if(is_file($file))
			{
				echo "\n";
				echo $this->file_lib->cat($file);
				echo "\n";
			}
		}
	}

	//取得拼音
	function pingyin_f()
	{
 		$this->lib("pingyin")->path = "framework/libs/pingyin.qdb";
 		$title = $this->get("title");
 		if(!$title)
 		{
	 		json_exit("缺少要转化的类型");
 		}
 		$split = $this->get("split");
 		if($split)
 		{
	 		$tmplist = $this->lib("string")->StringToArray($title);
	 		$title = implode($split,$tmplist);
 		}
 		$py = iconv("UTF-8","GBK",$title);
 		$getFirst = $this->get("first","int");
 		if($getFirst)
 		{
	 		$this->lib("pingyin")->isFrist = 1;
 		}
	 	$py = $this->lib("pingyin")->ChineseToPinyin($py);
	 	if(!$py)
	 	{
		 	json_exit("没有相关拼音");
	 	}
	 	$py = strtolower($py);
	 	$safe_string = "abcdefghijklmnopqrstuvwxyz0123456789-_";
		$str_array = str_split($py);
		$safe_array = str_split($safe_string);
		$string = "";
		foreach($str_array AS $key=>$value)
		{
			if(in_array($value,$safe_array))
			{
				$string .= $value;
			}
			else
			{
				$string .= "-";
			}
		}
		json_exit($string,true);
	}

	function mini_f()
	{
		$this->js_base();
		$ext = $this->get('ext');
		if($ext)
		{
			$list = explode(",",$ext);
			foreach($list AS $key=>$value)
			{
				$value = trim($value);
				if(!$value) continue;
				if(strtolower(substr($value,-3)) != '.js') $value .= '.js';
				$file = is_file('framework/js/'.$value) ? 'framework/js/'.$value : ROOT."js/".$value;
				if(is_file($file))
				{
					echo "\n";
					echo $this->file_lib->cat($file);
					echo "\n";
				}
			}
		}
	}

	//定义JS缓存头部信息
	function js_header()
	{
		header("Content-type: application/x-javascript; charset=UTF-8");
		//计算缓存时间
		$jstime = $_SESSION['js_cache_time'];
		if(!$jstime)
		{
			$_SESSION['js_cache_time'] = $this->time;
			$jstime = $this->time;
		}
		$expires_time = $jstime + $this->config['js_cache_time'];
		if($expires_time < $this->time)
		{
			$_SESSION['js_cache_time'] = $this->time;
			$expires_time = $this->time + $this->config['js_cache_time'];
			$jstime = $this->time;
		}
		header("Expires:".date("D, d M Y H:i:s",$expires_time)." GMT");
		header("Last-Modified:".date("D, d M Y H:i:s",$jstime)." GMT");
		header("Cache-Control:max-age=".$this->config['js_cache_time']);
	}

	//最小化加载js
	function js_base()
	{
		$this->js_header();
		$file = $_SERVER["SCRIPT_NAME"] ? basename($_SERVER["SCRIPT_NAME"]) : basename($_SERVER["SCRIPT_FILENAME"]);
		//加载配置常用的JS
		$weburl = $this->get_url();
		echo 'var basefile = "'.$file.'";';
		echo 'var ctrl_id = "'.$this->config['ctrl_id'].'";';
		echo 'var func_id = "'.$this->config['func_id'].'";';
		echo 'var webroot = "'.$weburl.'";';
		echo 'var apifile = "'.$this->config['api_file'].'";';
		if($this->lang['js'])
		{
			echo 'var lang= new Array();';
			foreach($this->lang['js'] AS $key=>$value)
			{
				echo 'lang["'.$key.'"] = "'.$value.'";';
			}
		}
		echo "\n";
		echo 'function get_url(ctrl,func,ext){var url = "'.$weburl.$file.'?'.$this->config['ctrl_id'].'="+ctrl;if(func){url+="&'.$this->config['func_id'].'="+func;};if(ext){url+="&"+ext};return url;}';
		echo "\n";
		echo 'function api_url(ctrl,func,ext){var url = "'.$weburl.$this->config['api_file'].'?'.$this->config['ctrl_id'].'="+ctrl;if(func){url+="&'.$this->config['func_id'].'="+func;};if(ext){url+="&"+ext};return url;};';
		echo "\n";
		echo 'function api_plugin_url(id,func,ext){var url = "'.$weburl.$this->config['api_file'].'?'.$this->config['ctrl_id'].'=plugin&'.$this->config['func_id'].'=index&id="+id+"&exec="+func;if(ext){url+="&"+ext};return url;};';
		echo "\n";
		echo $this->file_lib->cat("js/jquery.js");
		echo "\n";
	}
}
?>