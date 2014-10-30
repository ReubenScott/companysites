<?php
/***********************************************************
	Filename:  www/register_control.php
	Note	: 会员注册信息
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年06月15日 14时45分
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class register_control extends base_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		//随机数，防止恶意注册刷新代码
		$_SESSION['register_spam_code'] = str_rand(10);
		//返回上一级网址
		$_back = $this->get("_back");
		if(!$_back) $_back = $this->url;
		if($_SESSION["user_id"])
		{
			error($this->lang[$this->app_id][1001],$_back);
		}
		$this->assign('_back',$_back);
		//判断是否有启用注册
		if(!$this->site['register_status'])
		{
			$tips = $this->site["register_close"] ? $this->site["register_close"] : $this->lang[$this->app_id][1003];
			error($tips,$_back,'error');
		}
		//取得开放的会员组信息
		$grouplist = $this->model("usergroup")->get_all("(is_open=1 OR is_default=1) AND status=1");
		if(!$grouplist)
		{
			error($this->lang['api'][1010],$_back,'error',10);
		}
		$this->assign("grouplist",$grouplist);
		$gid = $this->get("group_id","int");
		if($gid)
		{
			$group_rs = $this->model("usergroup")->get_one($gid);
			if(!$group_rs || !$group_rs["status"]) $gid = 0;
		}
		if(!$gid)
		{
			if(count($grouplist) == 1)
			{
				$group_rs = current($grouplist);
				$gid = $group_rs['id'];
			}
			else
			{
				foreach($grouplist AS $key=>$value)
				{
					if($value["is_default"])
					{
						$gid = $value["id"];
						$group_rs = $value;
					}
				}
			}
		}
		//判断是否使用验证码注册
		$this->assign("group_id",$gid);
		$this->assign("group_rs",$group_rs);
		if($group_rs["register_status"] && $group_rs["register_status"] != "1")
		{
			if(!$group_rs['tbl_id']) error("未绑定验证项目",$this->url,'error');
			$p_rs = $this->model("project")->get_one($group_rs["tbl_id"],false);
			if(!$p_rs['module']) error("绑定的项目中没有关联模块",$this->url,'error');
			$code = $this->get('_code');
			if(!$code)
			{
				$this->view('register_check_'.$group_rs["register_status"]);
				exit;
			}
			$chk_rs = $this->model("list")->get_one_condition("l.title='".$code."'",$p_rs['module']);
			if(!$chk_rs) error("验证码不正确，请检查",$this->url("register"),"error");
			if($chk_rs && $chk_rs["account"]) error("验证码已使用过，请填写新的验证码",$this->url("register"));
			if(!$chk_rs["status"]) error("验证码未启用或已过期",$this->url("register"));
			if($chk_rs['email']) $this->assign('account',$chk_rs['email']);
			$this->assign("code",$code);
		}
		//取得当前组的扩展字段
		$ext_list = $this->model("user")->fields_all("is_edit=1");
		$extlist = "";
		if(!$ext_list) $ext_list = array();
		foreach($ext_list AS $key=>$value)
		{
			if($value["ext"])
			{
				$ext = unserialize($value["ext"]);
				foreach($ext AS $k=>$v)
				{
					$value[$k] = $v;
				}
			}
			$idlist[] = strtolower($value["identifier"]);
			if($rs[$value["identifier"]])
			{
				$value["content"] = $rs[$value["identifier"]];
			}
			if(!$group_rs['fields'] || ($group_rs['fields'] && in_array($value['identifier'],explode(",",$group_rs['fields']))))
			{
				$extlist[] = $this->lib('form')->format($value);
			}
		}
		$this->assign("extlist",$extlist);
		$this->view("register");
	}

	function save_f()
	{
		$check = $this->site["register_status"];
		if(!$this->site['register_status'])
		{
			$tips = $this->site["register_close"] ? $this->site["register_close"] : "暂停注册";
			error($tips);
		}
		$backurl = $this->get("_back");
		if(!$backurl) $backurl = $this->url;
		if($_SESSION["user_id"])
		{
			error("您已经登录",$backurl);
		}
		$user = $this->get("user");
		if(!$user)
		{
			error("会员账号不能为空",$this->url("register"),"error");
		}
		//检测账号是否存在
		$chk_rs = $this->model('user')->chk_name($user);
		if($chk_rs)
		{
			error("会员账号已存在，请返回",$this->url("register"),"error");
		}
		$newpass = $this->get("newpass");
		$chkpass = $this->get("chkpass");
		if(!$newpass)
		{
			error("密码不能为空",$this->url("register"),"error");
		}
		if($newpass != $chkpass)
		{
			error("两次输入的密码不一致",$this->url("register"),"error");
		}
		$array = array();
		$array["user"] = $this->get("user");
		$array["pass"] = password_create($newpass);
		$group_id = $this->get("group_id","int");
		if($group_id)
		{
			$group_rs = $this->model("usergroup")->get_one($group_id);
			if(!$group_rs || !$group_rs['status']) $group_id = 0;
		}
		if(!$group_id)
		{
			$group_rs = $this->model('usergroup')->get_default();
			if(!$group_rs || !$group_rs["status"]) error("无默认会员组或未启用");
			$group_id = $group_rs["id"];
		}
		if(!$group_id)
		{
			error("会员组不能为空","","error");
		}
		if(!$group_rs["is_default"] && !$group_rs["is_open"])
		{
			error("您没有权限指定此会员组！","","error");
		}
		$array["group_id"] = $group_id;
		$array["status"] = $group_rs["register_status"] ? "1" : "0";
		$array["regtime"] = $this->system_time;
		//存档
		$user_id = $this->model('user')->save($array);
		if(!$user_id)
		{
			error("会员注册失败，请联系管理员","","error");
		}
		//更新会员表扩展字段内容
		$extlist = $this->model('user')->fields_all();
		$ext = array();
		$ext["id"] = $user_id;
		if($extlist)
		{
			foreach($extlist AS $key=>$value)
			{
				$ext_value = ext_value($value);
				$ext[$value["identifier"]] = $ext_value;
			}
		}
		$this->model('user')->save_ext($ext);
		//获取会员内容
		$rs = $this->model('user')->get_one($user_id);
		if(!$rs)
		{
			error("会员注册失败，请联系管理员","","error");
		}
		if($rs["status"])
		{
			user_login($rs["id"]);
			$tip = "会员 <span class='red'>".$rs["user"]."</span> 注册成功";
		}
		else
		{
			$tip = "您的账号 <span class='red'>".$rs["user"]."</span> 注册成功，请等待审核";
			//更新
			$code = $this->get("code");
			if($group_rs["tbl_id"] && $group_rs["register_status"] && $group_rs['register_status'] != '1' && $code)
			{
				$project_rs = $this->model("project")->get_one($group_rs["tbl_id"],false);
				if($project_rs && $project_rs["module"])
				{
					$tbl_id = $project_rs["module"];
					if($tbl_id)
					{
						$info_rs = $this->model("list")->get_one_condition("l.title='".$code."'",$tbl_id);
						if($info_rs)
						{
							$ext_array = array("id"=>$info_rs["id"],"site_id"=>$info_rs["site_id"],"project_id"=>$info_rs["project_id"],"account"=>$rs["user"]);
							$this->model("list")->save_ext($ext_array,$tbl_id);
						}
					}
				}
			}
		}
		$ifopen = $this->get("ifopen","int");
		if($ifopen)
		{
			$html = '系统会在 <span class="red">2秒</span>后关闭窗口，<a href="javascript:parent.window.location.href=parent.window.location.href;void(0);">您可以点这里关闭窗口</a>';
			$html.= '<script type="text/javascript">'."\n";
			$html.= 'window.setTimeout(\'parent.window.location.href=parent.window.location.href\',2000)'."\n";
			$html.= "\n".'</script>';
			error_open($tip,"ok",$html);
		}
		else
		{
			error($tip,$this->url,"ok");
		}
	}

	//验证账号是否为空
	function check_f()
	{
		$user = $this->get("user");
		if(!$user) json_exit("会员账号不能为空");
		$chk_rs = $this->model('user')->chk_name($user);
		if($chk_rs)
		{
			json_exit("会员账号已存在");
		}
		else
		{
			json_exit("检测通过",true);
		}
	}
}
?>