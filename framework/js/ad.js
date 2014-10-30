/***********************************************************
	Filename:  js/ad.js
	Note	: 广告后台管理常用到的JS
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年5月13日
***********************************************************/
var is_multiple = false;
function open_action_pic_id(val)
{
	var c = $("#pic_id").val();
	if(is_multiple)
	{
		var new_c = c ? c+","+val : val;
		var list = new_c.split(",");
		list = $.unique(list);
		new_c = list.join(",");
		$("#pic_id").val(new_c);
		preview_res_pic_id();
	}
	else
	{
		$("#pic_id").val(val);
		preview_res_pic_id();
	}
}
function update_res_pic_id(id)
{
	var title = $("#pic_id_res_title_"+id).val();
	if(!title)
	{
		$.dialog.alert("名称不能为空");
		return false;
	}
	var url = get_url("res","update_title_note") +"&id="+id;
	url += "&title="+$.str.encode(title);
	var note = $("#pic_id_res_content_"+id).val();
	if(note)
	{
		url += "&note="+$.str.encode(note);
	}
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		$.dialog.alert("附件信息更新成功");
		return false;
	}
	else
	{
		$.dialog.alert(rs.content);
		return false;
	}
}

//删除附件记录
function del_res_pic_id(id)
{
	var content = $("#pic_id").val();
	if(!content || content == "undefined")
	{
		return false;
	}
	if(content == id)
	{
		$("#pic_id").val("");
		$("#pic_id_attrlist").html("");
		$("#pic_id_attrlist").hide();
		return false;
	}
	var list = content.split(",");
	var newlist = new Array();
	var new_i = 0;
	for(var i=0;i<list.length;i++)
	{
		if(list[i] != id)
		{
			newlist[new_i] = list[i];
			new_i++;
		}
	}
	content = newlist.join(",");
	$("#pic_id").val(content);
	preview_res_pic_id();
}
function preview_res_pic_id()
{
	var id = $("#pic_id").val();
	if(!id || id == "undefined")
	{
		$("#pic_id_attrlist").html("");
		$("#pic_id_attrlist").hide();
	}
	var url = get_url("ajax","exit") + "&filename=res&id="+$.str.encode(id);
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		var list = rs.content;
		var html = '<table width="100%">';
		for(var i in list)
		{
			html += '<tr>';
			html += '<td width="100px" align="center"><img src="'+list[i]["ico"]+'" border="0" alt="'+list[i]["title"]+'" width="80px" height="80px" /></td>';
			html += '<td valign="top">';
			html += '<table><tr>';
			html += '<td><input type="text" id="pic_id_res_title_'+list[i]["id"]+'" value="'+list[i]["title"]+'" class="default" title="附件名" /></td>';
			html += '</tr><tr>';
			html += '<td><textarea id="pic_id_res_content_'+list[i]["id"]+'" class="default" title="备注">'+list[i]["note"]+'</textarea></td>';
			html += '</tr><tr>';
			html += '<td>';
			html += '<input type="button" value="更新附件" class="button" onclick="update_res_pic_id('+list[i]["id"]+')" /> ';
			html += '<input type="button" value="预览" class="button" onclick="preview_pic_id('+list[i]["id"]+')" />';
			html += '<input type="button" value="删除" class="button red" onclick="del_res_pic_id('+list[i]["id"]+');" /> ';
			html += '</td></tr></table></td></tr>';
		}
		html += '</table>';
		$("#pic_id_attrlist").html(html);
		$("#pic_id_attrlist").show();
	}
	else
	{
		$("#pic_id_attrlist").hide();
		$.dialog.alert(rs.content);		
	}
}
//预览
function preview_pic_id(id)
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

//单个文件上传成功后触发的函数
function pic_id_swfupload_success(file,serverData)
{
	//客户端关闭显示
	var progress = new FileProgress(file, this.customSettings.progressTarget);
	progress.setComplete();
	progress.setStatus("上传完成！");
	progress.toggleCancel(false);
	var rs =  $.parseJSON(serverData);
	//如果附件只支持一个
	if(is_multiple)
	{
		var content = $("#pic_id").val();
		content = content ? content+","+rs.id : rs.id;
		$("#pic_id").val(content);
	}
	else
	{
		$("#pic_id").val(rs.id);
	}
	preview_res_pic_id();
}

//上传完成后操作
function pic_id_swfupload_complete(file)
{
	if (this.getStats().files_queued === 0)
	{
		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	}
}

//文件选择完毕后动作
function pic_id_swfupload_file_dialog_complete(numFilesSelected, numFilesQueued)
{
	if (numFilesSelected > 0)
	{
		document.getElementById(this.customSettings.cancelButtonId).disabled = false;
	}
}

//统计总进度
function pic_id_swfupload_queue_complete(numFilesUploaded)
{
	$("#pic_id_div_status").html("已上传文件数量："+numFilesUploaded+"");
}

function check_ad()
{
	var title = $("#title").val();
	if(!title)
	{
		$.dialog.alert("广告名称不能为空");
		return false;
	}
	return true;
}

//编辑广告
function ad_edit(id)
{
	var url = get_url("ad","set");
	if(id)
	{
		url += "&id="+id;
	}
	direct(url);
}

function ad_status(id)
{
	var url = get_url("ad","status") + "&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		direct(window.location.href);
	}
	else
	{
		$.dialog.alert(rs.content);
		return false;
	}
}

function ad_delete(id,title)
{
	$.dialog.confirm("确定要删除广告：<span class='red'>"+title+"</span>？删除后相关统计信息都将一同删除！",function(){
		var url = get_url("ad","delete") + "&id="+id;
		var rs = json_ajax(url);
		if(rs.status == "ok")
		{
			direct(window.location.href);
		}
		else
		{
			$.dialog.alert(rs.content);
			return false;
		}
	});
}

function ad_code(id)
{
	var url = "index.php?app=tj&id="+id;
	var html = '&lt;script type="text/javascript" src="'+url+'"&gt;&lt;/script&gt;';
	$.dialog({
		"content":html,
		"title":"调用代码",
		"lock" : true
	});
}

function ad_code_pl()
{
	var id = $.input.checkbox_join();
	if(!id)
	{
		$.dialog.alert("未选择ID");
		return false;
	}
	var url = "index.php?app=tj&id="+$.str.encode(id);
	var html = '&lt;script type="text/javascript" src="'+url+'"&gt;&lt;/script&gt;';
	$.dialog({
		"content":html,
		"title":"调用代码",
		"lock" : true
	});
}

function ad_stat(id)
{
	var url = get_url("ad","stat") + "&id="+id;
	direct(url);
}