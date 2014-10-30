<?php
/***********************************************************
	Filename: plugins/alipay/setting.php
	Note	: 支付宝参数配置
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月14日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class setting_alipay extends phpok_plugin
{
	var $path;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__))."/";
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

	//更新支付宝的状态
	function status()
	{
		$rs = $this->plugin_info();
		$this->model('payment')->status($rs['id'],$rs['status']);
	}
}
?>