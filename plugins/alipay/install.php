<?php
/***********************************************************
	Filename: plugins/alipay/install.php
	Note	: 支付宝插件安装，安装过程需要对payment表插入一条数据
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class install_alipay extends phpok_plugin
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

	//存储安装插件信息
	function save()
	{
		//取得插件信息，存储扩展配置
		$rs = $this->plugin_info();
		$ext = array();
		$ext['alipay_pid'] = $this->get("alipay_pid");
		$ext['alipay_key'] = $this->get("alipay_key");
		$ext['alipay_email'] = $this->get("alipay_email");
		$ext['alipay_title'] = $this->get("alipay_title");
		$ext['alipay_note'] = $this->get("alipay_note","html");
		$ext['alipay_ext'] = $this->get("alipay_ext");
		$this->plugin_save($ext,$rs['id']);
		//写入一条记录到payment来
		$title = $ext['alipay_title'] ? $ext['alipay_title'] : '支付宝';
		$data = array('code'=>$rs['id'],'title'=>$title,'note'=>$ext['alipay_note'],'site_id'=>0,'status'=>$rs['status']);
		$data['taxis'] = $rs['taxis'];
		$this->model('payment')->save($data);
	}
}
?>