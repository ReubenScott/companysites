/***********************************************************
	Filename:  js/admlogin.js
	Note	: 管理员登录页涉及到的JS
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2014年1月6日
***********************************************************/
//验证码
function login_code(appid)
{
	var src_url = api_url("vcode","","appid="+appid);
	$("#src_code").attr("src",src_url);
}


//验证并登录
function admlogin()
{
	var username = $("#username").val();
	if(!username)
	{
		alert("管理员账号不能为空");
		return false;
	}
	//密码验证
	var pass = $("#password").val();
	if(!pass)
	{
		alert("密码不能为空！");
		return false;
	}
	//这一步是验证管理员账号是否存在，存在的话返回密码密文
	var url = api_url('admlogin','name',"user="+encodeURIComponent(username));
	url = url.replace(/&amp;/g,"&");
	var c = $.ajax({'url':url,cache:false,async:false,dataType:"html"}).responseText;
	var rs = $.parseJSON(c);
	if(rs.status != 'ok')
	{
		if(!rs.content) rs.content = '会员验证失败';
		alert(rs.content);
		return false;
	}
	var v_str = rs.content.key;
	if(v_str)
	{
		pass += v_str;
	}
	//进行客户端密码加密
	$("#password").val($.md5(pass));
	//现在进行通话，执行登录
	$("#adminlogin").ajaxSubmit({
		url:api_url('admlogin','login'),
		type:'post',
		dataType:'json',
		success:function(rs){
			if(rs.status == 'ok')
			{
				alert("欢迎您，"+rs.content+"，您已经成功登录后台！");
				window.location.href = get_url('index');
			}
			else
			{
				if(!rs.content) rs.content = '登录失败';
				alert(rs.content);
				return false;
			}
		}
	});
	return false;
}

