<?php
/***********************************************************
	Filename:  admin/index_control.php
	Note	: 后台首页控制台
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 13:03
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class index_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		if(!$this->license_code) $this->license = "LGPL";
		$license = strtoupper($this->license);
		$code = '';
		if($license == "PBIZ" && $this->license_code && $this->license_name)
		{
			//$code = '<a href="http://license.phpok.com/'.$this->license_code.'.html" target="_blank"><span style="color:darkblue;">个人（'.$this->license_name.'）商业授权</span></a>';
			$code = '<span style="color:darkblue;">个人（'.$this->license_name.'）商业授权</span>';
		}
		elseif($license == "CBIZ" && $this->license_code && $this->license_name)
		{
			//$code = '<a href="http://license.phpok.com/'.$this->license_code.'.html" target="_blank"><span style="color:darkblue;">企业（'.$this->license_name.'）商业授权</span></a>';
			$code = '<span style="color:darkblue;">企业（'.$this->license_name.'）商业授权</span>';
		}
		else
		{
			$code = "<span class='red'>LGPL开源授权</span>";
		}
		$this->assign("license",$code);
		$this->assign("version",$this->version);
		$this->view("index");
	}

	function clear_f()
	{
		$this->lib('file')->rm(ROOT."data/cache/");
		$this->lib('file')->rm(ROOT."data/tpl_www/");
		$this->lib('file')->rm(ROOT."data/tpl_admin/");
		$list = $this->lib('file')->ls(ROOT."data/");
		if(!$list) $list = array();
		foreach($list AS $key=>$value)
		{
			if(is_file($value) && basename($value) != "install.lock") $this->lib('file')->rm($value);
		}
		$list = $this->lib("file")->ls(FRAMEWORK."config/");
		if(!$list) $list = array();
		include(FRAMEWORK."config/config.global.php");
		foreach($list AS $key=>$value)
		{
			$tmp = basename($value);
			if($tmp != 'config.global.php' && $tmp != 'config_admin.php')
			{
				include($value);
				if($config['engine'] && $config['engine']['cache'])
				{
					$this->cache->config($config['engine']['cache']);
					$this->cache->clear();
				}
			}
		}
		json_exit("缓存清空完成",true);
	}

	function site_f()
	{
		$siteid = $this->get("id","int");
		if(!$siteid)
		{
			error("请选择要维护的站点！",$this->ur('index'));
		}
		$rs = $this->model("site")->get_one($siteid);
		if(!$rs)
		{
			error("站点信息不存在",$this->url("index"));
		}
		$_SESSION['admin_site_id'] = $siteid;
		error("您正在切换到网站：<span style='color:red;font-weight:bold;'>".$rs['title']."</span>，请稍候…",$this->url("index"),"ok");
	}
}
?>