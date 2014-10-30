<?php
/***********************************************************
	Filename:  www/tj_control.php
	Note	: 宣传
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年5月13日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class tj_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$id = $this->get("id");
		if(!$id)
		{
			$this->js_error("未指定广告ID");
		}
		$list_id = explode(",",$id);
		$list_id = array_unique($list_id);
		$array = array();
		foreach($list_id AS $key=>$value)
		{
			$value = intval($value);
			if($value)
			{
				$array[] = $value;
			}
		}
		$id = implode(",",$array);
		$rslist = $this->model('ad')->get_all_in($id,"status=1");
		if(!$rslist)
		{
			$this->js_error("");
		}
		$html = '';
		$pv_id = array();
		foreach($rslist AS $key=>$value)
		{
			$url = "index.php?".$this->config["ctrl_id"]."=tj&".$this->config["func_id"]."=click&id=".$value["id"];
			$pic = ($value["pic_id"] && is_array($value["pic_id"])) ? $value["pic_id"]["filename"] : "";
			if(!$value["content"])
			{
				$content = "";
				$content .= '<a href="{url}" target="_blank">';
				if($pic)
				{
					$content .= '<img src="{pic}" border="0" />';
				}
				$content .= '</a>';
			}
			else
			{
				$content = $value["content"];
			}
			if(!$value["url"] || $value["url"] == "#")
			{
				$content = preg_replace("/href=['|\"](.+)['|\"]/isU","",$content);
			}
			$content = str_replace("{url}",$url,$content);
			$content = str_replace("{pic}",$pic,$content);
			$content = addcslashes($content,'"');
			$html .= 'document.write("'.$content.'");'."\n";
			$array = array();
			$array["aid"] = $value["id"];
			$array["addtime"] = $this->system_time;
			$array["page"] = "";
			$p_id = $this->model('ad')->save_pv($array);
			$this->model('ad')->update_pv_stat($value["id"]);
		}
		$this->js_header();
		echo $html;
		exit;
	}

	function click_f()
	{
		$id = $this->get("id","int");
		if(!$id)
		{
			exit("error");
		}
		$rs = $this->model('ad')->get_one($id);
		if(!$rs)
		{
			exit("error2");
		}
		$session_id = $this->session->sessid();
		$url = $_SERVER["HTTP_REFERER"];
		$ip = $this->sys_ip();
		$array = array("aid"=>$id,"url"=>$url,"addtime"=>$this->system_time,"ip"=>$ip,"session_id"=>$session_id);
		$this->model('ad')->save_info($array);
		//更新PV，IP，UV统计
		$this->model('ad')->update_stat($id);
		header("Location:".$rs["url"]);
		exit;
	}

	//包含文件，JS和str
	function include_f()
	{
		$js = $this->get("js");
		if(!$js) $js = "jquery.js,jquery.str.js";
		$list = explode(",",$js);
		$content = '';
		foreach($list AS $key=>$value)
		{
			if($value == "jquery.js")
			{
				$content .= file_get_contents(FRAMEWORK."jquery.js");
				$content .= "\n\n";
			}
			else
			{
				$content .= file_get_contents(FRAMEWORK."js/".$value);
				$content .= "\n\n";
			}
		}
		$this->js_header();
		echo $content;
		exit;
	}

	function js_error($msg="")
	{
		$this->js_header();
		$html = 'document.write("'.$msg.'");';
		echo $html;
		exit;
	}

	function js_header()
	{
		header("Content-type: application/x-javascript; charset=UTF-8");
		// 时间总是过去的
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		// 文件总是被修改的
		header("Last-Modified:". gmdate("D, d M Y H:i:s") ." GMT");
		// HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		// HTTP/1.0
		header("Pragma: no-cache");
	}

	function sys_ip()
	{
		$cip = (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != "") ? $_SERVER['HTTP_CLIENT_IP'] : FALSE;
		$rip = (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != "") ? $_SERVER['REMOTE_ADDR'] : FALSE;
		$fip = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != "") ? $_SERVER['HTTP_X_FORWARDED_FOR'] : FALSE;
		$ip = "0.0.0.0";
		if($cip && $rip)
		{
			$ip = $cip;
		}
		elseif($rip)
		{
			$ip = $rip;
		}
		elseif($cip)
		{
			$ip = $cip;
		}
		elseif($fip)
		{
			$ip = $fip;
		}

		if (strstr($ip, ','))
		{
			$x = explode(',', $ip);
			$ip = end($x);
		}

		if ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip))
		{
			$ip = '0.0.0.0';
		}
		return $ip;
	}

}
?>