<?php
/***********************************************************
	Filename: {phpok}/www/payment_control.php
	Note	: 付款操作
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月14日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class payment_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	//执行付款操作
	function index_f()
	{
		$rs = $this->auth_check();
		$backurl = $_SESSION['user_id'] ? $this->url('order','info','id='.$rs['id']) : $this->url('order','info','sn='.$rs['sn'].'&passwd='.$rs['passwd']);
		//如果该订单已付款，则禁用付款操作
		if($rs['pay_status'] == 'PAID')
		{
			error('该订单已付款',$backurl);
		}
		//取得付款方案
		$payment = $this->get('payment','int');
		if(!$payment) $payment = $rs['pay_id'];
		$payment_rs = $this->model('payment')->get_one($payment);
		if(!$payment_rs || !$payment_rs['status'])
		{
			error('付款异常，未指定付款方式或付款方案不存在或未启用付款方案','','error');
		}
		//更新付款方式
		$array = array('pay_id'=>$payment_rs['id']);
		$this->model('order')->save($array,$rs['id']);
		//转到支付默认执行页
		$apiurl = api_url('plugin','exec','id='.$payment_rs['code'].'&exec=payment&oid='.$rs['id']);
		header("Location:".$apiurl);
		exit;
	}

	//权限验证
	function auth_check()
	{
		$sn = $this->get('sn');
		$back = $this->get('back');
		if(!$back) $back = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->url;
		//判断订单是否存在
		if($sn) $rs = $this->model('order')->get_one_from_sn($sn,$_SESSION['user_id']);
		if(!$rs)
		{
			$id = $this->get('id','int');
			if(!$id) error("无法获取订单信息，请检查！",$back,'error');
			$rs = $this->model('order')->get_one($id);
			if(!$rs) error("订单信息不存在，请检查！",$back,'error');
		}
		//判断是否有维护订单权限
		if($_SESSION['user_id'])
		{
			if($rs['user_id'] != $_SESSION['user_id']) error('您没有权限维护此订单：'.$rs['sn'],$back,'error');
		}
		else
		{
			$passwd = $this->get('passwd');
			if($passwd != $rs['passwd']) error('您没有权限维护此订单：'.$rs['sn'],$back,'error');
		}
		return $rs;
	}

}

?>