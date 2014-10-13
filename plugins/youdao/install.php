<?php
/***********************************************************
	Filename: plugins/youdao/install.php
	Note	: 有道翻译插件安装
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class install_youdao extends phpok_plugin
{
	var $path;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__)).'/';
	}

	function index()
	{
		if(is_file($this->path."template/setting.html"))
		{
			return $this->fetch($this->path."template/setting.html",'abs-file');
		}
		return false;
	}

	function save()
	{
		//取得插件信息
		$id = $this->plugin_id();
		$ext = array();
		$ext['keyfrom'] = $this->get("keyfrom");
		$ext['keyid'] = $this->get("keyid");
		$this->plugin_save($ext,$id);
	}
}
?>