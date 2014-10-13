<?php
/***********************************************************
	Filename: plugins/youdao/admin.php
	Note	: 有道翻译，后台操作
	Version : 4.0
	Author  : qinggan
	Update  : 2012-08-22 19:59
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class admin_youdao extends phpok_plugin
{
	var $path;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__))."/";
	}

	function all($rs)
	{
		$this->assign('rs',$rs);
		return $this->fetch($this->path."template/btn.html",'abs-file');
	}

	function cate_set($rs)
	{
		return $this->all($rs);
	}

	function project_set($rs)
	{
		return $this->all($rs);
	}

	function list_edit($rs)
	{
		return $this->all($rs);
	}
}
?>