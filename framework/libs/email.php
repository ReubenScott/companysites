<?php
/***********************************************************
	Filename: libs/system/email.php
	Note	: 发送邮件类
	Version : 3.0
	Author  : qinggan
	Update  : 2010-05-10
***********************************************************/
//引入phpmail控件发送邮件
class email_lib
{
	var $app;
	var $tpl;
	var $timeout = 15;
	var $smtp_charset = "utf8";
	var $smtp_server = "";
	var $smtp_port = 25;
	var $smtp_ssl = 0;
	var $smtp_user = "";
	var $smtp_pass = "";
	var $smtp_reply = "";
	var $smtp_admin = "";
	var $smtp_to = "";
	var $smtp_fromname = "Webmaster";
	var $smtp;
	var $is_debug = false;

	//读取邮件信息
	function __construct()
	{
		include_once($GLOBALS['app']->dir_phpok."engine/phpmailer/class.phpmailer.php");
		//初始化邮件服务器参数
		$this->smtp_charset = ($GLOBALS['app']->site["email_charset"] == "gbk" && function_exists("iconv")) ? "gbk" : "utf8";
		$this->smtp_server = $GLOBALS['app']->site["email_server"];
		$this->smtp_port = $GLOBALS['app']->site["email_port"] ? $GLOBALS['app']->site["email_port"] : 25;
		$this->smtp_ssl = $GLOBALS['app']->site["email_ssl"] ? true : false;
		$this->smtp_user = $GLOBALS['app']->site["email_account"];
		$this->smtp_pass = $GLOBALS['app']->site["email_pass"];
		$this->smtp_reply = $GLOBALS['app']->site["email"] ? $GLOBALS['app']->site["email"] : $GLOBALS['app']->site["email"];
		$this->smtp_admin = $GLOBALS['app']->site["email"];
		$this->smtp_fromname = $GLOBALS['app']->site["email_name"];
		if(!$this->smtp_fromname)
		{
			$tmp = strstr($this->smtp_admin,'@');
			$this->smtp_fromname = str_replace($tmp,'',$this->smtp_admin);
		}
	}

	function set_debug($debug = false)
	{
		$this->is_debug = $debug;
	}

	function setting($var,$val)
	{
		if($var && $val)
		{
			$this->$var = $val;
		}
	}

	//连接到email环境中
	function send_mail($sendto,$subject,$content,$user_name="")
	{
		if(!$sendto || !$subject || !$content || !$this->smtp_server || !$this->smtp_user || !$this->smtp_pass || !$this->smtp_reply || !$this->smtp_admin)
		{
			return false;
		}
		$mail = new PHPMailer();
		$mail->CharSet = $this->smtp_charset;
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = $this->is_debug;//是否启用调试
		$mail->IsHTML(true);
		$mail->Username = trim($this->smtp_user);
		$mail->Password = trim($this->smtp_pass);
		$mail->Host = trim($this->smtp_server);
		$mail->Port = $this->smtp_port;
		if($this->smtp_ssl)
		{
			$mail->SMTPSecure = 'ssl';
		}
		$mail->LE = "\r\n";
		$mail->Timeout = 15;
		//发件人
		$mail->From = $this->smtp_admin;
		$mail->FromName = $this->smtp_fromname;
		if($mail->CharSet != "utf8")
		{
			$subject = $this->app->charset($subject,"UTF-8","GBK");
			$content = $this->app->charset($content,"UTF-8","GBK");
			$mail->FromName = $this->app->charset($mail->FromName,"UTF-8","GBK");
		}
		$mail->Subject = $subject;
		$mail->MsgHTML($content);
		$sendto_array = explode(";",$sendto);
		if(count($sendto_array)<2)
		{
			if(!$user_name)
			{
				$user_name = str_replace(strstr($sendto,"@"),"",$sendto);
			}
			if($mail->CharSet != "utf8")
			{
				 $user_name = $this->app->charset($user_name,"UTF-8","GBK");
			}
			$mail->AddAddress($sendto,$user_name);
		}
		else
		{
			foreach($sendto_array AS $key=>$value)
			{
				$v_name = str_replace(strstr($value,"@"),"",$value);
				$mail->AddAddress($value,$v_name);
			}
		}
		return $mail->Send();
	}

}
?>