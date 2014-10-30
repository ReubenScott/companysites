<?php
/***********************************************************
	Filename: plugins/alipay/uninstall.php
	Note	: 支付宝插件卸载，删除payment记录数据
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class uninstall_alipay extends phpok_plugin
{
	var $path;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__)).'/';
	}

	function index()
	{
		//卸载插件
		$this->model('payment')->delete_code('alipay');
		return true;
	}
}
?>