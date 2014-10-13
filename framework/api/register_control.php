<?php
/***********************************************************
	Filename: {phpok}/api/register_control.php
	Note	: 注册API接口
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年10月30日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class register_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	//检测会员是否存在
	function check_user_f()
	{
		$id = $this->get("id","int");
		$user = $this->get("user");
		$check_rs = $this->check_user($user);
		if($check_rs != 'ok')
		{
			$this->json($check_rs);
		}
		$this->json(102,true);
	}

	//存储注册信息
	function save_f()
	{
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
			$code = md5(strtolower($code));
			if($code != $_SESSION["vcode_api"])
			{
				$this->json(1009);
			}
		}
		//检测会员账号
		$user = $this->get("user");
		$check_rs = $this->check_user($user);
		if($check_rs != 'ok') json_exit($check_rs);
		//检测密码是否符合要求
		$newpass = $this->get('newpass');
		if(!$newpass) $this->json(1005);
		$chkpass = $this->get('chkpass');
		if(!$chkpass) $this->json(1006);
		if($newpass != $chkpass) $this->json(1007);
		//验证邮箱
		$email = $this->get('email');
		if(!$email) $this->json(1038);
		if(!phpok_check_email($email)) $this->json(1039);
		//检测邮箱是否已被使用
		$uid = $this->model('user')->uid_from_email($email);
		if($uid) $this->json(1042);

		//存储主表数据
		$array = array();
		$array["user"] = $user;
		$array["pass"] = password_create($newpass);
		$array['email'] = $email;
		$array['mobile'] = $this->get('mobile');
		//
		$group_id = $this->get("group_id","int");
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
		if(!$group_rs["is_default"] && !$group_rs["is_open"]) $this->json(1012);
		$array["group_id"] = $group_id;
		$array["status"] = $group_rs["register_status"] == '1' ? "1" : "0";
		$array["regtime"] = $this->system_time;
		$uid = $this->model('user')->save($array);
		if(!$uid) $this->json(1013);

		//更新会员表扩展字段内容
		$extlist = $this->model('user')->fields_all();
		$ext = array();
		$ext["id"] = $uid;
		if($extlist)
		{
			foreach($extlist AS $key=>$value)
			{
				$ext[$value["identifier"]] = ext_value($value);
			}
		}
		$this->model('user')->save_ext($ext);
		if($array['status'])
		{
			//状态为1时，直接在线登录
			phpok_user_login($uid);
			$this->json(101,true);
		}
		//未设置审核，直接中止，弹出提示
		if(!$group_rs["tbl_id"] || !$group_rs['register_status'])
		{
			$this->json(101,true);
		}
		//判断项目是否有绑定模块，没有模块的禁止使用
		$p_rs = $this->model('project')->get_one($group_rs['tbl_id'],false);
		if(!$p_rs['module'])
		{
			$this->json(101,true);
		}
		//判断是否有验证串，没有验证串的，停止验证
		$code = $this->get('_code');
		if(!$code)
		{
			$this->json(101,true);
		}
		//取得内容信息
		$info = $this->list_model->get_one_condition("l.title='".$code."'",$p_rs['module']);
		if($info)
		{
			$ext = array('site_id'=>$info['site_id'],'project_id'=>$info['project_id']);
			$ext['account'] = $user;
			$this->model('list')->update_ext($ext,$p_rs['module'],$info['id']);
			//验证串通过更新会员状态
			$this->user_model->set_status($uid,1);
			phpok_user_login($uid);
		}
		$this->json(101,true);
	}

	//检测会员账号，内部使用
	private function check_user($name,$id=0)
	{
		if(!$name) return 1001;
		$rs = $this->model('user')->chk_name($name,$id);
		if($rs) return 1002;
		return 'ok';
	}

	//检测邀请码是否有效
	public function code_f()
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
		$code = $this->get('code');
		if(!$code) $this->json(1024);
		//判断是否存在
		$group_id = $this->get('group_id','int');
		if($group_id)
		{
			$group_rs = $this->model('usergroup')->get_one($group_id);
			if(!$group_rs || !$group_rs['status']) $group_id = 0;
		}
		if(!$group_id)
		{
			$group_rs = $this->model('usergroup')->get_default(1);
			if(!$group_rs) $this->json(1010);
			$group_id = $group_rs['id'];
		}
		if(!$group_rs['register_status'] || $group_rs['register_status'] == '1') $this->json(1026);
		if(!$group_rs['tbl_id']) $this->json(print_r($group_rs,true));
		$p_rs = $this->model("project")->get_one($group_rs["tbl_id"],false);
		if(!$p_rs['module']) $this->json(1027);
		$chk_rs = $this->model("list")->get_one_condition("l.title='".$code."'",$p_rs['module']);
		if(!$chk_rs) $this->json(1028);
		if($chk_rs && $chk_rs["account"]) $this->json(1029);
		if(!$chk_rs["status"]) $this->json(1030);
		$url = $this->url('register','','_code='.rawurlencode($code).'&group_id='.$group_id);
		$this->json($url,true);		
	}
}
?>