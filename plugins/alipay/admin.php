<?php
/***********************************************************
	Filename: plugins/alipay/admin.php
	Note	: 支付宝插件在后台的一些应用
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月18日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class admin_alipay extends phpok_plugin
{
	var $path;
	var $info;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__))."/";
		$this->info = $this->plugin_info();
	}

	//格式化扩展内容数据
	function format($ext,$is_array=false)
	{
		if(!$ext || !$this->info['param']['alipay_ext']) return false;
		$alipay_ext = str_replace("\r","",$this->info['param']['alipay_ext']);
		if(!$alipay_ext) return false;
		$list = explode("\n",$alipay_ext);
		$ext = is_string($ext) ? unserialize($ext) : $ext;
		$rslist = '';
		foreach($list AS $key=>$value)
		{
			if(!$value || !trim($value)) continue;
			$tmp = explode(',',$value);
			if($ext[$tmp[0]])
			{
				if($is_array)
				{
					$rslist[] = array('title'=>$tmp[1],'content'=>$ext[$tmp[0]]);
				}
				else
				{
					$rslist[] = $tmp[1].'：'.$ext[$tmp[0]];
				}
			}
		}
		return $rslist;
	}
}
?>