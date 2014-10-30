<?php
/***********************************************************
	Filename: plugins/alipay/api.php
	Note	: 支付宝接口执行端
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月15日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class api_alipay extends phpok_plugin
{
	var $path;
	function __construct()
	{
		parent::plugin();
		$this->path = str_replace("\\","/",dirname(__FILE__))."/";
	}

	//默认执行付款操作
	function payment()
	{
		//加载Alipay支付应用
		include_once($this->path."lib/alipay_submit.class.php");
		//取得插件信息
		$plugin = $this->plugin_info();
		
		$id = $this->get('oid','int');
		if(!$id)
		{
			error('未指定订单ID！');
		}
		$rs = $this->model('order')->get_one($id);
		if($rs['pay_status'] == 'PAID')
		{
			error('订单已经支付了，您不用再另外支付');
		}

		//异步通知，不支持带参数的url
        $notify_url = $this->url."alipay.php";
        //$notify_url = api_url('plugin','exec','id='.$plugin['id'].'&exec=notify&oid='.$id,true);
        //同步通知页面路径
        $return_url = api_url('plugin','exec','id='.$plugin['id'].'&exec=notice',true);
        //付款金额
        $total_fee = price_format_val($rs['pay_price'],$rs['pay_currency'],$rs['pay_currency']);
        //商品展示地址
        $show_url = $this->url('order','info','sn='.$rs['sn'].'&passwd='.$rs['passwd']);

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($plugin['param']['alipay_pid']),
				"payment_type"	=> 1,
				"notify_url"	=> $this->url."alipay.php",
				"return_url"	=> $return_url,
				"seller_email"	=> $plugin['param']['alipay_email'],
				"out_trade_no"	=> $rs['sn'],
				"subject"	=> '订单：'.$rs['sn'],
				"total_fee"	=> $total_fee,
				"body"	=> '支付订单：'.$rs['sn'].'费用',
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> '',
				"exter_invoke_ip"	=> client_ip(),
				"_input_charset"	=> 'utf-8'
		);

		//合作身份者id，以2088开头的16位纯数字
		$alipay_config = array('partner'=>$plugin['param']['alipay_pid'],'key'=>$plugin['param']['alipay_key']);
		$alipay_config['sign_type'] ='MD5';
		$alipay_config['input_charset']= strtolower('utf-8');
		$alipay_config['cacert']    = $this->path.'cacert.pem';
		$alipay_config['transport']    = 'http';
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
		echo '<html>'."\n";
		echo '<head>'."\n\t";
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'."\n\t";
		echo '<title>付款中</title>'."\n";
		echo '</head>'."\n<body>\n";
		echo $html_text;
		echo "\n".'</body>'."\n</html>";
	}

	//执行通知操作
	function notify()
	{
		include_once($this->path."lib/alipay_notify.class.php");
		$plugin = $this->plugin_info();
		$sn = $this->get('out_trade_no');
		//当无法获取sn信息时
		if(!$sn) exit('错误，未指定订单编号');
		$rs = $this->model('order')->get_one_from_sn($sn);
		if(!$rs) exit('错误，没有该产品信息');
		//注销其他四个变量
		unset($_GET[$this->config['ctrl_id']],$_GET[$this->config['func_id']]);
		unset($_GET['id'],$_GET['exec']);
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config = array('partner'=>$plugin['param']['alipay_pid'],'key'=>$plugin['param']['alipay_key']);
		$alipay_config['sign_type'] ='MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['cacert']    = $this->path.'cacert.pem';
		$alipay_config['transport']    = 'http';
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verify($_GET);
		if(!$verify_result)
		{
			exit('fail');
		}
		//附款日期
		$pay_date = $this->get('notify_time');
		if($pay_date) $pay_date = strtotime($pay_date);
		//附款金额，支付宝接口仅支持人民币
		$price = $this->get('total_fee','float');
		//更新订单信息
		$array = array('pay_status'=>"PAID",'pay_date'=>$pay_date,'pay_price'=>$price);
		//更新扩展数据
		$alipay = array();
		$alipay['buyer_email'] = $this->get('buyer_email');
		$alipay['buyer_id'] = $this->get('buyer_id');
		$alipay['time'] = $this->get('notify_time');
		$alipay['seller_email'] = $this->get('seller_email');
		$alipay['seller_id'] = $this->get('seller_id');
		$alipay['total_fee'] = $this->get('total_fee');
		$alipay['trade_no'] = $this->get('trade_no');
		$alipay['trade_status'] = $this->get('trade_status');
		$array['ext'] = serialize($alipay);
		$this->model('order')->save($array,$rs['id']);
		echo 'success';
	}

	function notice()
	{
		include_once($this->path."lib/alipay_notify.class.php");
		$plugin = $this->plugin_info();
		$sn = $this->get('out_trade_no');
		//当无法获取sn信息时
		if(!$sn) error('错误，未指定订单编号',$this->url);
		$rs = $this->model('order')->get_one_from_sn($sn);
		if(!$rs) error('错误，没有该产品信息',$this->url);
		//状态为已付款，后不验证，直接跳到成功说明
		if($rs['pay_status'] == 'PAID')
		{
			$burl = $_SESSION['user_id'] ? $this->url('order','info','id='.$rs['id']) : $this->url('order','info','sn='.$sn.'&passwd='.$rs['passwd']);
			error('您的订单：'.$sn.' 付款成功，请稍候，系统将引导您查看订单信息',$burl,'success');
		}
		//注销其他四个变量
		unset($_GET[$this->config['ctrl_id']],$_GET[$this->config['func_id']]);
		unset($_GET['id'],$_GET['exec']);
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config = array('partner'=>$plugin['param']['alipay_pid'],'key'=>$plugin['param']['alipay_key']);
		$alipay_config['sign_type'] ='MD5';
		$alipay_config['input_charset']= 'utf-8';
		$alipay_config['cacert']    = $this->path.'cacert.pem';
		$alipay_config['transport']    = 'http';
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verify($_GET);
		if(!$verify_result)
		{
			error('订单验证不通过，请登录支付宝查看，并进入后台手动更新信息',$this->url);
		}
		//附款日期
		$pay_date = $this->get('notify_time');
		if($pay_date) $pay_date = strtotime($pay_date);
		//附款金额，支付宝接口仅支持人民币
		$price = $this->get('total_fee','float');
		//更新订单信息
		$array = array('pay_status'=>"PAID",'pay_date'=>$pay_date,'pay_price'=>$price);
		//更新扩展数据
		$alipay = array();
		$alipay['buyer_email'] = $this->get('buyer_email');
		$alipay['buyer_id'] = $this->get('buyer_id');
		$alipay['time'] = $this->get('notify_time');
		$alipay['seller_email'] = $this->get('seller_email');
		$alipay['seller_id'] = $this->get('seller_id');
		$alipay['total_fee'] = $this->get('total_fee');
		$alipay['trade_no'] = $this->get('trade_no');
		$alipay['trade_status'] = $this->get('trade_status');
		$array['ext'] = serialize($alipay);
		$this->model('order')->save($array,$rs['id']);
		$burl = $_SESSION['user_id'] ? $this->url('order','info','id='.$rs['id']) : $this->url('order','info','sn='.$sn.'&passwd='.$rs['passwd']);
		error('您的订单：'.$sn.' 付款成功，请稍候，系统将引导您查看订单信息',$burl,'success');
	}

	//订单扩展信息
	function content()
	{
		$sn = $this->get('sn');
		$passwd = $this->get('passwd');
		if(!$sn || !$passwd) $this->json('参数不正确');
		$rs = $this->model('order')->get_one_from_sn($sn);
		if(!$rs) $this->json('订单信息不存在');
		if($rs['passwd'] != $passwd) $this->json('订单验证不通过');
		if(!$rs['ext']) $this->json('没有扩展信息');
		$param = unserialize($rs['ext']);
		$rs = $this->plugin_info();
		if($rs['param']['alipay_ext'])
		{
			$rslist = '';
			$rs['param']['alipay_ext'] = str_replace("\r","",$rs['param']['alipay_ext']);
			$list = explode("\n",$rs['param']['alipay_ext']);
			foreach($list AS $key=>$value)
			{
				if(!$value || !trim($value)) continue;
				$tmp = explode(',',$value);
				if($param[$tmp[0]])
				{
					$rslist[$tmp[0]] = array('title'=>$tmp[1],'content'=>$param[$tmp[0]]);
				}
			}
			$this->json($rslist,true);
		}
		$this->json('未指定参数信息');
	}
}
?>