/***********************************************************
	Filename: js/global.js
	Note	: 后台通用JS，此JS应加载在jquery.js之后
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 16:58
***********************************************************/
//异步加载js
function load_js(url)
{
	if(!url || url == "undefined") return false;
	var lst = url.split(",");
	var lst_count = lst.length;
	var elist = new Array();
	var tm = 0;
	$("script").each(function(t){
		var src = $(this).attr("src");
		if(src && src != 'undefined'){
			elist[tm] = src;
			tm++; 
		}
	});
	var html = '';
	for(var i=0;i<lst_count;i++)
	{
		if($.inArray(lst[i],elist) < 0)
		{
			html += '<script type="text/javascript" src="'+lst[i]+'"></script>';
		}
	}
	$("head").append(html);
}

// 同步加载Ajax，返回字符串
function get_ajax(turl)
{
	turl = turl.replace(/&amp;/g,"&");
	return $.ajax({url:turl,cache:false,async:false,dataType:"html"}).responseText;
}

// 同步加载Ajax，返回JSON数组
function json_ajax(turl)
{
	turl = turl.replace(/&amp;/g,"&");
	var c = $.ajax({url:turl,cache:false,async:false,dataType:"html"}).responseText;
	return $.parseJSON(c);
}


// 异步加载Ajax，执行函数
function ajax_async(turl,func,type)
{
	if(!turl || turl == "undefined")
	{
		return false;
	}
	if(!func || func == "undefined")
	{
		return false;
	}
	if(!type || type == "undefined")
	{
		type = "json";
	}
	if(type != "html" && type != "json" && type != "text" && type != "xml")
	{
		type = "json";
	}
	turl = turl.replace(/&amp;/g,"&");
	$.ajax({
		url			: turl,
		cache		: false,
		async		: true,
		dataType	: type,
		success		: function(rs){
			try
			{
				if(typeof(func) == "function")
				{
					func(rs);
				}
			}
			catch (e)
			{
				alert("函数不存在");
			}
		}
	});
}

// 跳转页面
function direct(url)
{
	if(!url || url == "undefined") url = window.location.href;
	url = url.replace(/&amp;/g,"&");
	window.location.href=url;
}

// 跳转页面别名
function goto(url)
{
	return direct(url);
}


//自动刷新
function auto_refresh(rs)
{
	direct(window.location.href);
}

function autosave_callback(rs)
{
	return true;
}

/* 计算字符数长度，中文等同于三个字符，英文为一个字符 */
function strlen(str)
{
	var len = str.length;
	var reLen = 0;
	for (var i = 0; i < len; i++)
	{
		if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126)
		{
			reLen += 3;
		} else {
			reLen++;
		}
	}
	if(reLen > 1024 && reLen < (1024 * 1024))
	{
		var reLen = (parseFloat(reLen / 1024).toFixed(3)).toString() + "KB";
	}
	else if(reLen > (1024 * 1024) && reLen < (1024 * 1024 * 1024))
	{
		var reLen = (parseFloat(reLen / (1024 * 1024)).toFixed(3)).toString() + "MB";
	}
	if(!reLen) reLen = "0";
	return reLen;
}


//友情提示
function tips(content,time,id)
{
	if(!time || time == "undefined") time = 1.5;
	if(!id || id == "undefind")
	{
		$.dialog.tips(content,time);
	}
	else
	{
		return $.dialog({
			id: 'Tips',
			title: false,
			cancel: false,
			fixed: true,
			lock: false,
			focus: id,
			resize: false
		}).content(content).time(time || 1.5);
	}
}

/* 表单中涉及到时间控制 */
function show_date(v,formattype)
{
	if(formattype && formattype != "undefined")
	{
		var datetype = "%Y-%m-%d %H:%M";
		var show_time = true;
	}
	else
	{
		var datetype = "%Y-%m-%d";
		var show_time = false;
	}
	$("#"+v).dynDateTime({
		showsTime: show_time,
		ifFormat: datetype,
		timeFormat:"24"
	});
}



//编辑器中当前日期
function phpjs_fck_date()
{
	var myDate = new Date();
	var y = myDate.getFullYear();
	var m = myDate.getMonth() + 1;
	var d = myDate.getDate() + 1;
	m = m.toString();
	if(m.length < 2)
	{
		m = "0" + m;
	}
	d = d.toString();
	if(d.length < 2)
	{
		d = "0" + d;
	}
	return y.toString() + "-" +m+ "-" +d+ " ";
}

function phpjs_fck_time()
{
	var myDate = new Date();
	return myDate.toLocaleTimeString();
}

/* 计算数组或对像中的个数 */
function count(id)
{
	var t = typeof id;
	if(t == 'string') return id.length;
	if(t == 'object')
	{
		var n = 0;
		for(var i in id)
		{
			n++;
		}
		return n;
	}
	return false;
}

//JS语言包替换
function lang_replace(str,id,val)
{
	if(!str || str == "undefined") return false;
	if(!id || !val) return str;
	return str.replace("{"+id+"}",val);
}


