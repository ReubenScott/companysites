<?php
/***********************************************************
	Filename: plugins/youdao/api.php
	Note	: 翻译插件获取数据接口
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class api_youdao extends phpok_plugin
{
	function __construct()
	{
		parent::plugin();
	}

	function fanyi()
	{
		$rs = $this->plugin_info();
		$q = $this->get("q");
		if(!$q) $this->json("youdao_not_info");
		$url = "http://fanyi.youdao.com/openapi.do?keyfrom=".$rs['param']["keyfrom"];
		$url.= "&key=".$rs['param']["keyid"]."&type=data&doctype=json&version=1.1&q=".rawurlencode($q);
		$content = $this->lib("html")->get_content($url);
		if(!$content) $this->json("youdao_get_error");
		//
		$rs = $this->lib("json")->decode($content);
		if($rs["errorCode"])
		{
			$errlist = array(
				"20"=>$this->lang['youdao'][20],
				"30"=>$this->lang['youdao'][30],
				"40"=>$this->lang['youdao'][40],"50"=>$this->lang['youdao'][50]
			);
			$err = $errlist[$rs["errorCode"]];
			if(!$err) $err = $this->lang['youdao'][60];
			$this->json($err);
		}
		$safe_string = "abcdefghijklmnopqrstuvwxyz0123456789-_";
		$content = strtolower($rs["translation"][0]);
		$str_array = str_split($content);
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
		$this->json($string,true);
	}
}
?>