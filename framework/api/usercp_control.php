<?php
/***********************************************************
	Filename:  api/usercp_control.php
	Note	: 会员中心数据存储
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月5日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class usercp_control extends base_control
{
	function __construct()
	{
		parent::control();
		if(!$_SESSION['user_id']) $this->json(1048);
	}

	//存储个人数据
	function info_f()
	{
		//会员头像
		$avatar = $this->get("avatar");
		//更新Email
		$email = $this->get("email");
		if(!$email) $this->json(1038);
		if(!phpok_check_email($email)) $this->json(1039);
		//检测Email是否已被使用
		$uid = $this->model('user')->uid_from_email($email,$_SESSION['user_id']);
		if($uid) $this->json(1042);
		//更新手机号
		$mobile = $this->get('mobile');
		$array = array('avatar'=>$avatar,'email'=>$email,'mobile'=>$mobile);
		$this->model('user')->save($array,$_SESSION['user_id']);
		//获取会员组信息
		$group_id = $_SESSION['user_rs']['group_id'];
		if(!$group_id)
		{
			//取得默认会员组
			$group_rs = $this->model('usergroup')->get_default();
			if(!$group_rs || !$group_rs["status"]) $this->json(1010);
			$group_id = $group_rs["id"];
		}
		$group_rs = $this->model('usergroup')->get_one($group_id);
		if(!$group_rs || !$group_rs["status"]) $this->json(1011);
		//读取扩展属性，并更新存储
		$condition = 'is_edit=1';
		if($group_rs['fields'])
		{
			$tmp = explode(",",$group_rs['fields']);
			$condition .= " AND identifier IN('".(implode("','",$tmp))."')";
		}
		$ext_list = $this->model('user')->fields_all($condition,"id");
		$ext = "";
		foreach(($ext_list ? $ext_list : array()) AS $key=>$value)
		{
			$ext[$value['identifier']] = ext_value($value);
		}
		if($ext && is_array($ext))
		{
			$this->model('user')->update_ext($ext,$_SESSION['user_id']);
		}
		user_login($_SESSION["user_id"]);
		$this->json('ok',true);
	}

	//更新会员头像
	function avatar_f()
	{
		$data = $this->get('data');
		if(!$data) $this->json(1049);
		$array = array('avatar'=>$data);
		$this->model('user')->save($array,$_SESSION['user_id']);
		user_login($_SESSION["user_id"]);
		$this->json('ok',true);
	}

	//更新会员密码功能
	function passwd_f()
	{
		$oldpass = $this->get("oldpass");
		$newpass = $this->get("newpass");
		$chkpass = $this->get("chkpass");
		if(!$oldpass || !$newpass || !$chkpass) $this->json(1050);
		if(!password_check($oldpass,$_SESSION["user_rs"]["pass"])) $this->json(1051);
		if($newpass != $chkpass) $this->json(1007);
		if($oldpass == $newpass) $this->json(1052);
		$password = password_create($newpass);
		$array["pass"] = $password;
		$this->model('user')->save($array,$_SESSION["user_id"]);
		user_login($_SESSION["user_id"]);
		$this->json('ok',true);
	}

	//更新收货地址
	function shipping_f()
	{
		$shipping = array();
		//姓名不能为空
		$shipping['fullname'] = $this->get('s-fullname');
		if(!$shipping['fullname']) $this->json('姓名不能为空');
		//取得性别
		$shipping['gender'] = $this->get('s-gender','int');
		//国家不能为空
		$shipping['country'] = $this->get('s-country');
		if(!$shipping['country']) $this->json('国家不能为空');
		//省份信息不能为空
		$shipping['province'] = $this->get('s-province');
		if(!$shipping['province']) $this->json('请选择您所在省份信息');
		//检测市，县，区
		$shipping['city'] = $this->get('s-city');
		if($shipping['city'] == '市辖区' || $shipping['city'] == '市辖县') $shipping['city'] = '('.$shipping['city'].')';
		$shipping['county'] = $this->get('s-county');
		if($shipping['county'] == '市辖区' || $shipping['county'] == '市辖县') $shipping['county'] = '';
		//检测地址是否有填写
		$shipping['address'] = $this->get('s-address');
		if(!$shipping['address']) $this->json('请填写送货地址信息，要求尽可能详实');
		//邮编号
		$shipping['zipcode'] = $this->get('s-zipcode');
		if(!$shipping['zipcode']) $this->json('邮编不能为空');
		//检测电话或手机
		$shipping['tel'] = $this->get('s-tel');
		$shipping['mobile'] = $this->get('s-mobile');
		if(!$shipping['tel'] && !$shipping['mobile']) $this->json('至少要求填写一个联系方式：电话或手机');
		if($shipping['tel'])
		{
			if(!$this->isTel($shipping['tel'],'tel')) $this->json('电话填写不正确，请填写规范，如：0755-123456789');
		}
		if($shipping['mobile'])
		{
			if(!$this->isTel($shipping['mobile'],'mobile')) $this->json('手机填写不正确，请填写规范，如：158185xxxxx');
		}
		//验证邮箱
		$shipping['email'] = $this->get('s-email');
		if(!$shipping['email']) $this->json('Email不能为空，系统会发送订单状态到这个邮箱上');
		if(!preg_match('/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/',$shipping['email']))
		{
			$this->json('Email不符合要求，请填写正确的Email');
		}
		$id = $this->get('s-id','int');
		if($id)
		{
			$rs = $this->model('address')->get_one($id);
			if(!$rs) $this->json('登记地址信息异常，错误编号：ADDRESS1002');
			if($rs['user_id'] != $_SESSION['user_id']) $this->json('登记地址信息异常，错误编号：ADDRESS1001');
			if($rs['type_id'] != 'shipping') $this->json('登记地址信息异常，错误编号：ADDRESS1003');
			$this->model('address')->save($shipping,$id);
		}
		else
		{
			$shipping['type_id'] = 'shipping';
			$shipping['user_id'] = $_SESSION['user_id'];
			$id = $this->model('address')->save($shipping);
			if(!$id) $this->json('登记地址信息异常，错误编号：ADDRESS1004');
		}
		$this->json('更新成功',true);
	}

	//更新账单地址
	function billing_f()
	{
		$billing = array();
		//姓名不能为空
		$billing['fullname'] = $this->get('b-fullname');
		if(!$billing['fullname']) $this->json('账单姓名不能为空');
		//取得性别
		$billing['gender'] = $this->get('b-gender','int');
		//国家不能为空
		$billing['country'] = $this->get('b-country');
		if(!$billing['country']) $this->json('国家不能为空');
		//省份信息不能为空
		$billing['province'] = $this->get('b-province');
		if(!$billing['province']) $this->json('请选择您所在省份信息');
		//检测市，县，区
		$billing['city'] = $this->get('b-city');
		if($billing['city'] == '市辖区' || $billing['city'] == '市辖县') $billing['city'] = '('.$billing['city'].')';
		$billing['county'] = $this->get('b-county');
		if($billing['county'] == '市辖区' || $billing['county'] == '市辖县') $billing['county'] = '';
		//检测地址是否有填写
		$billing['address'] = $this->get('b-address');
		if(!$billing['address']) $this->json('请填写账单地址信息，要求尽可能详实');
		//邮编号
		$billing['zipcode'] = $this->get('b-zipcode');
		if(!$billing['zipcode']) $this->json('邮编不能为空');
		//检测电话或手机
		$billing['tel'] = $this->get('b-tel');
		$billing['mobile'] = $this->get('b-mobile');
		if(!$billing['tel'] && !$billing['mobile']) $this->json('至少要求填写一个联系方式：电话或手机');
		if($billing['tel'])
		{
			if(!$this->isTel($billing['tel'],'tel')) $this->json('电话填写不正确，请填写规范，如：0755-123456789');
		}
		if($billing['mobile'])
		{
			if(!$this->isTel($billing['mobile'],'mobile')) $this->json('手机填写不正确，请填写规范，如：158185xxxxx');
		}
		$id = $this->get('b-id','int');
		if($id)
		{
			$rs = $this->model('address')->get_one($id);
			if(!$rs) $this->json('登记地址信息异常，错误编号：ADDRESS1005');
			if($rs['user_id'] != $_SESSION['user_id']) $this->json('登记地址信息异常，错误编号：ADDRESS1006');
			if($rs['type_id'] != 'billing') $this->json('登记地址信息异常，错误编号：ADDRESS1007');
			$this->model('address')->save($billing,$id);
		}
		else
		{
			$billing['type_id'] = 'billing';
			$billing['user_id'] = $_SESSION['user_id'];
			$id = $this->model('address')->save($billing);
			if(!$id) $this->json('登记地址信息异常，错误编号：ADDRESS1008');
		}
		$this->json('登录成功',true);
	}

	//是否电话判断
	function isTel($tel,$type='')
	{
		$regxArr = array(
			'mobile'  =>  '/^(\+?86-?)?(18|15|13)[0-9]{9}$/',
			'tel' =>  '/^(\+?86-?)?(010|02\d{1}|0[3-9]\d{2})-\d{7,9}(-\d+)?$/',
			'400' =>  '/^400(-\d{3,4}){2}$/',
		);
		if($type && isset($regxArr[$type]))
		{
			return preg_match($regxArr[$type], $tel) ? true:false;
		}
		foreach($regxArr as $regx)
		{
			if(preg_match($regx, $tel ))
			{
				return true;
			}
		}
		return false;
	}
	
}
?>