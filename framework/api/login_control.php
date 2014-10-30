<?php
/***********************************************************
	Filename:  api/login_control.php
	Note	: API登录接口
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月2日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class login_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	//登录网站
	function index_f()
	{
		//判断是否是会员
		if($_SESSION['user_id'])
		{
			$this->json(1004);
		}
		//判断是否启用验证码功能
		if($this->config['is_vcode'] && function_exists('imagecreate'))
		{
			$code = $this->get('_chkcode');
			if(!$code) $this->json(1008);
			if(md5(strtolower($code)) != $_SESSION["vcode_api"]) $this->json(1009);
		}
		//获取登录信息
		$user = $this->get("user");
		$pass = $this->get("pass");
		if(!$user || !$pass) $this->json(1022);
		//判断会员信息是否存在
		$chk_rs = $this->model('user')->chk_name($user);
		if(!$chk_rs) $this->json(1023);
		//尝试登录信息
		$info = user_login($chk_rs["id"],$pass);
		if($info != "ok")
		{
			$this->json($info,false,true,false);
		}
		//注销随机码
		unset($_SESSION['login_spam_code']);
		$this->json('ok',true);
	}

	//请求取回密码功能
	function getpass_f()
	{
		//过滤防止恶意操作
		if(!$_SESSION['getpass_spam_code'] || $_SESSION['getpass_spam_code'] != $this->get('_spam'))
		{
			$this->json(1003);
		}
		//判断是否是会员
		if($_SESSION['user_id'])
		{
			$this->json(1004);
		}
		//检测是否启用验证码
		if($this->config['is_vcode'] && function_exists('imagecreate'))
		{
			$code = $this->get('_chkcode');
			if(!$code) $this->json(1008);
			if(md5($code) != $_SESSION[$this->app_id."_vcode"])
			{
				$this->json(1009);
			}
		}
		$email = $this->get('email');
		if(!$email) $this->json(1038);
		if(!phpok_check_email($email)) $this->json(1039);
		$uid = $this->model('user')->uid_from_email($email);
		if(!$uid) $this->json(1044);
		$user = $this->model('user')->get_one($uid);
		//随机生成验证串，并存储进来
		$title = str_rand(10).$this->time;
		$this->model('user')->save(array('code'=>$title),$uid);

		//获取邮件模板ID
		$email_rs = $this->model('email')->get_identifier('getpass',$this->site['id']);
		if(!$email_rs) $this->json(1041);
		
		$link = $this->url('login','repass','_code='.rawurlencode($title));
		$this->assign('link',$link);
		$this->assign('email',$email);
		$this->assign('code',$title);
		$this->assign('user',$user);
		$title = $this->fetch($email_rs["title"],"content");
		$content = $this->fetch($email_rs["content"],"content");

		//发送邮件
		$this->lib('email')->send_mail($email,$title,$content);
		unset($_SESSION['getpass_spam_code']);//注销随机码
		$this->json('ok',true);
	}

	//通过取回密码进行修改
	function repass_f()
	{
		//过滤防止恶意注册
		if(!$_SESSION['repass_spam_code'] || $_SESSION['repass_spam_code'] != $this->get('_spam'))
		{
			$this->json(1021);
		}
		//判断是否是会员
		if($_SESSION['user_id'])
		{
			$this->json(1004);
		}
		//判断是否启用验证码功能
		if($this->config['is_vcode'] && function_exists('imagecreate'))
		{
			$code = $this->get('_chkcode');
			if(!$code) $this->json(1008);
			if(md5($code) != $_SESSION[$this->app_id."_vcode"]) $this->json(1009);
		}

		$code = $this->get('code');
		$user = $this->get('user');
		$email = $this->get('email');
		$newpass = $this->get('newpass');
		$chkpass = $this->get('chkpass');
		if(!$newpass) $this->json(1005);
		if(!$chkpass) $this->json(1006);
		if($newpass != $chkpass) $this->json(1007);
		//检测验证串
		if(!$code) $this->json(1047);
		$time = intval(substr($code,-10));
		if(($this->time - $time) > (24*60*60)) $this->json(1045);
		//检查会员信息
		if(!$user) $this->json(1001);
		$chk_rs = $this->model('user')->chk_name($user);
		if(!$chk_rs) $this->json(1023);
		$uid = $chk_rs['id'];
		$user = $this->model('user')->get_one($uid);
		if(!$user) $this->json(1023);
		//如果会员的验证串不一致，禁止修改
		if($user['code'] != $code) $this->json(1046);
		//检查密码
		$array = array('pass'=>password_create($newpass),'code'=>'');
		$this->model('user')->save($array,$uid);
		unset($_SESSION['repass_spam_code']);
		$this->json('ok',true);
	}
}

?>