/***********************************************************
	Filename: {phpok}js/global.www.js
	Note	: 前台公共JS加载
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月12日
***********************************************************/
//前台通用弹出窗口
function phpok_open(id,title)
{
	if(id == "login" || id == "register")
	{
		var url = get_url("ajax","exit","filename="+id);
	}
	else
	{
		var url = id;
	}
	if(!title || title == "undefined") title = '弹出窗';
	$.dialog.open(url,{
		 "title":title
		,"resize":true
		,"lock":true
		,"id":"phpok_open_frame"
		,"fixed":true
		,"drag":false
	});
}

//前台常用JS函数封装
;(function($){
//定义验证码
$.fn.phpok_vcode = function(ext){
	var url = api_url('vcode','','tmp='+Math.random());
	if(ext && ext != 'undefined')
	{
		url += "&"+ext;
	}
	$(this).attr('src',url);
}
$.phpok_www = {
	//语言包替换
	lang_replace: function(str,id,val){
		if(!str || str == "undefined") return false;
		if(!id || !val) return str;
		return str.replace("{"+id+"}",val);
	},
	logout: function(nickname){
		var info = this.lang_replace(lang[1001],'nickname',nickname);
		$.dialog.confirm(info,function(){
			var url = api_url('logout');
			var rs = json_ajax(url);
			if(rs && rs.status == "ok")
			{
				$.dialog.alert(rs.content,function(){
					$.phpok.reload();
				})
			}
			else
			{
				if(!rs.content) rs.content = lang[1002];
				$.dialog.alert(rs.content);
				return false;
			}
		});
	}
};
})(jQuery);