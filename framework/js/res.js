/***********************************************************
	Filename: {phpok}/js/res.js
	Note	: 资源管理器中涉及到的JS
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-12-31 15:08
***********************************************************/

//预览缩略图
function mark_picture_view(rs)
{
	$("#mark_picture").val(rs.filename);
	var htm = '<img src="'+rs.filename+'" border="0" />';
	$("#mark_picture_html").html(htm);
	$("#mark_picture_delete").show();
	$("#mark_picture_button").hide();
	$("#gd_position").show();
	$("#gd_trans").show();
}
//删除图片
function mark_picture_delete()
{
	var val = $("#mark_picture").val();
	if(val)
	{
		var url = get_url("res","delete") + "&filename="+rawurlencode(val);
		var rs = json_ajax(url);
		if(rs.status != "ok")
		{
			alert(rs.content);
			return false;
		}
	}
	$("#mark_picture").val("");
	$("#mark_picture_html").html("");
	$("#mark_picture_delete").hide();
	$("#mark_picture_button").show();
	$("#gd_position").hide();
	$("#gd_trans").hide();
}

//如果有图片
function if_picture()
{
	var pic = $("#mark_picture").val();
	if(pic)
	{
		var htm = '<img src="'+pic+'" border="0" />';
		$("#mark_picture_html").html(htm);
		$("#mark_picture_delete").show();
		$("#mark_picture_button").hide();
		$("#gd_position").show();
		$("#gd_trans").show();
	}
}
//检查图片方案是否完整
function check_gd()
{
	var url = get_url("res","gd_chk");
	var id = $("#id").val();
	if(id)
	{
		url += "&id="+id;
	}
	else
	{
		var identifier = $("#identifier").val();
		if(!identifier)
		{
			alert("标识不能为空！");
			return false;
		}
		if(!$.str.identifier(identifier))
		{
			alert("标识串不符合要求！");
			return false;
		}
		url += "&identifier="+rawurlencode(identifier);
		var rs = json_ajax(url);
		if(rs.status != "ok")
		{
			alert(rs.content);
			return false;
		}
	}
	return true;
}

//删除GD方案
function gd_delete(id,identifier)
{
	var qc = confirm("确定要删除图片方案："+identifier+" 吗？删除后，请更新图片库，以保证图片同步");
	if(qc == "0")
	{
		return false;
	}
	var url = get_url("res","gd_delete") + "&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		alert("图片方案删除成功！");
		direct(window.location.href);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

//分类添加
function cate_add()
{
	var url = get_url("res","cate_save");
	var title = $("#title_0").val();
	if(!title)
	{
		alert("分类名称不能为空！");
		return false;
	}
	url += "&title="+rawurlencode(title);
	//根目录
	var root = $("#root_0").val();
	if(!root) root = "res/";
	url += "&root="+rawurlencode(root);
	//存储方式
	var folder = $("#folder_0").val();
	if(folder)
	{
		url += "&folder="+rawurlencode(folder);
	}
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		alert("分类："+title+"添加成功！");
		direct(window.location.href);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

//编辑分类信息
function cate_update(id)
{
	var url = get_url("res","cate_save");
	url += "&id="+id;
	var title = $("#title_"+id).val();
	if(!title)
	{
		alert("分类名称不能为空！");
		return false;
	}
	url += "&title="+rawurlencode(title);
	//根目录
	var root = $("#root_"+id).val();
	if(!root) root = "res/";
	url += "&root="+rawurlencode(root);
	//存储方式
	var folder = $("#folder_"+id).val();
	if(folder)
	{
		url += "&folder="+rawurlencode(folder);
	}
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		alert("分类："+title+"更新成功！");
		direct(window.location.href);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

//分类删除
function cate_delete(id)
{
	var title = $("#title_"+id).val();
	var qc = confirm("确定要删除分类："+title+" 吗？删除后所有分类都会迁移到默认分类");
	if(qc == "0")
	{
		return false;
	}
	var url = get_url("res","cate_delete")+"&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		alert("分类："+title+"删除成功");
		direct(window.location.href);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

function cate_default(id)
{
	var title = $("#title_"+id).val();
	var qc = confirm("确定要设置分类："+title+" 为默认存储吗？设置成功后以后上传的附件默认存到此目录下");
	if(qc == "0")
	{
		return false;
	}
	var url = get_url("res","cate_default")+"&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		alert("分类："+title+" 设置为默认存储成功");
		direct(window.location.href);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

//更改附件名称
function attr_title(id)
{
	var title = $("#title_"+id).val();
	if(!title)
	{
		alert("名称不能为空");
		return false;
	}
	var url = get_url("res","update_title")+"&id="+id;
	url += "&title="+rawurlencode(title);
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		$.dialog.tips("附件名称更新成功",2,"title_"+id);
	}
	else
	{
		$.dialog.tips(rs.content,2,"title_"+id);
	}
	return false;
}

//删除附件信息
function attr_delete(id)
{
	var title = $("#title_"+id).val();
	$.dialog.confirm("确定要删除附件："+title+" 吗？删除后相关的调用将会失效",function(){
		var url = get_url("res","delete")+"&id="+id;
		var rs = json_ajax(url);
		if(rs.status == "ok")
		{
			$.dialog.alert("附件删除成功",function(){
				$("#attr_"+id).remove();
			});
		}
		else
		{
			$.dialog.alert(rs.content);
		}
	});
}

//附件管理
function attr_manage(id)
{
	var url = get_url("res","set") + "&id="+id;
	direct(url);
}

//弹出裁图窗口
function attr_cut(id,type,is_gd)
{
	var url = get_url("res","cut") + "&id="+id;
	url += "&type="+type;
	if(is_gd && is_gd != "undefined")
	{
		url += "&gd=1";
	}
	$.dialog.open(url,{
		"width"		: "100%",
		"height"	: "100%",
		"fixed"		: true,
		"title"		: "在线裁图"
	},false);
}

//重新生成图片方案
function recreate(id,gd)
{
	var url = get_url("res","recreate") + "&id="+id;
	if(gd && gd != "undefined")
	{
		url += "&gd="+gd;
	}
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		$.dialog.alert("图片方案更新成功，您需要手动刷新后才能看到效果");
		return false;
	}
	else
	{
		$.dialog.alert(rs.content);
	}
	return false;
}

//编辑器默认加入的图片
function gd_editor(id)
{
	var url = get_url("res","gd_editor") + "&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		direct(window.location.href);
	}
	else
	{
		$.dialog.alert(rs.content);
	}
}

//编辑附件时的提示信息
function res_setok()
{
	var title = $("#title").val();
	if(!title)
	{
		alert("附件名称不能为空");
		return false;
	}
	return true;
}

/* 高级搜索 */
function show_search()
{
	if($("#adv_search").is(":hidden"))
	{
		$("#adv_search").show();
	}
	else
	{
		$("#adv_search").hide();
	}
}

/* 下载附件 */
function download_it(id)
{
	var url = get_url("res","download") + "&id="+id;
	direct(url);
}

function pl_update()
{
	var id = $.input.checkbox_join("mylist");
	if(!id || id == "undefined")
	{
		$.dialog.alert("未指定要操作的附件");
		return true;
	}
	var url = get_url("res","update_pl") + "&id="+$.str.encode(id);
	$.dialog.open(url,{
		"title":"批量更新附件信息，更新过程中，请不要关掉浏览器！",
		"lock": true,
		"width": "700px",
		"height":"525px"
	});
}

function update_pl_pictures()
{
	var url = get_url("res","update_pl") + "&id=all";
	$.dialog.open(url,{
		"title":"批量更新附件信息，更新过程中，请不要关掉浏览器！",
		"lock": true,
		"width": "700px",
		"height":"525px"
	});
}

function pl_delete()
{
	var id = $.input.checkbox_join("mylist");
	if(!id || id == "undefined")
	{
		$.dialog.alert("未指定要操作的附件");
		return true;
	}
	$.dialog.confirm("确定要删除选中的复件吗？删除后是不可恢复的",function(){
		var url = get_url("res","delete_pl") + "&id="+$.str.encode(id);
		var rs = json_ajax(url);
		if(rs.status == "ok")
		{
			$.dialog.alert("批量删除附件操作成功",function(){direct(window.location.href);});
		}
		else
		{
			$.dialog.alert(rs.content);
		}
	});
}

function preview_attr(id)
{
	var url = get_url("res_action","preview") + "&id="+id;
	$.dialog.open(url,{
		title: "预览",
		lock : true,
		width: "700px",
		height: "70%",
		resize: true
	});
}