<?php
/***********************************************************
	Filename: {phpok}/api/email_control.php
	Note	: 邮件发送操作
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月3日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class email_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	//发送邮件
	public function index_f()
	{
		//验证邮件
		$email = $this->get('email');
		if(!$email) $this->json(1032);
		//网站主题
		$title = $this->get('title');
		$content = $this->get('content','html');
		if(!$content) $this->json(1031);
		if(!$title) $title = phpok_cut($content,50,'…');
		//判断邮件服务器是否配置好了
		if(!$this->site['email_server']) $this->json(1033);
		if(!$this->site['email_account']) $this->json(1034);
		if(!$this->site['email_pass']) $this->json(1035);
		if(!$this->site['email']) $this->json(1036);
		$list = explode(',',$email);
		foreach($list AS $key=>$value)
		{
			$value = trim($value);
			//验证邮箱
			if($value && phpok_check_email($value))
			{
				$value_name = str_replace(strstr($value,'@'),'',$value);
				$this->lib('email')->send_mail($value,$title,$content,$value_name);
			}
		}
		$this->json('ok',true);		
	}

	public function register_f()
	{
		//过滤防止恶意注册
		if(!$_SESSION['register_spam_code'] || $_SESSION['register_spam_code'] != $this->get('_spam'))
		{
			$this->json(1003);
		}
		//判断是否是会员
		if($_SESSION['user_id'])
		{
			$this->json(1004);
		}
		$group_id = $this->get('group_id','int');
		if($group_id)
		{
			$group_rs = $this->model("usergroup")->get_one($group_id);
			if(!$group_rs || !$group_rs['status']) $group_id = 0;
		}
		if(!$group_id)
		{
			$group_rs = $this->model('usergroup')->get_default();
			if(!$group_rs || !$group_rs["status"]) $this->json(1010);
			$group_id = $group_rs["id"];
		}
		if(!$group_id) $this->json(1011);
		$gid = $group_id;
		//检测组是否符合要求
		if(!$group_rs['register_status'] || $group_rs['register_status'] == '1') $this->json(1037);
		if(!$group_rs['tbl_id']) $this->json(1025);
		$p_rs = $this->model('project')->get_one($group_rs['tbl_id'],false);
		if(!$p_rs['module']) $this->json(1027);
		//检测邮箱
		$email = $this->get('email');
		if(!$email) $this->json(1038);
		if(!phpok_check_email($email)) $this->json(1039);
		$uid = $this->model('user')->uid_from_email($email);
		if($uid) $this->json(1043);
		//创建一条数据
		$title = str_rand(10).$this->time;
		$array = array('site_id'=>$this->site['id'],'module_id'=>$p_rs['module'],'project_id'=>$p_rs['id']);
		$array['title'] = $title;
		$array['dateline'] = $this->time;
		$array['status'] = 1;
		$insert_id = $this->model('list')->save($array);
		if(!$insert_id) $this->json(1040);
		//存储扩展表数据
		$ext = array('id'=>$insert_id,'site_id'=>$p_rs['site_id'],'project_id'=>$p_rs['id']);
		$ext['account'] = '';
		$ext['email'] = $email;
		$ext['gid'] = $group_id;
		$this->model('list')->save_ext($ext,$p_rs['module']);
		//创建一个ID串
		$identifier = "content-".$insert_id;
		$i_array = array();
		$i_array["id"] = $insert_id;
		$i_array["site_id"] = $p_rs["site_id"];
		$i_array["phpok"] = $identifier;
		$i_array["type_id"] = "content";
		$this->model('id')->save($i_array);
		//获取邮件模板ID
		$email_rs = $this->model('email')->get_identifier('register_code',$p_rs['site_id']);
		if(!$email_rs) $this->json(1041);
		$link = $this->url('register','','_code='.rawurlencode($title).'&group_id='.$group_id);
		$this->assign('link',$link);
		$this->assign('email',$email);
		$title = $this->fetch($email_rs["title"],"content");
		$content = $this->fetch($email_rs["content"],"content");
		//发送邮件
		$this->lib('email')->send_mail($email,$title,$content);
		$this->json('ok',true);
	}

}
?>