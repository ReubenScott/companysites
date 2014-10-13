/***********************************************************
	Filename: {phpok}/form.js
	Note	: 自定义表单中涉及到的JS
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-12-26 11:02
***********************************************************/
function phpok_form_password(id,len)
{
	var list = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	if(!len || len == "undefined") len = 8;
	var rand = "";
	for(var i = 0;i<len;i++)
	{
		var num = Math.floor(Math.random()*36+0);
		rand = rand + list[num];
	}
	var htm = "随机密码："+rand;
	$("#"+id+"_html").html(htm);
	$("#"+id).val(rand);
}

//表单扩展按钮
//btn，类型
function phpok_btn_action(btn,id)
{
	if(btn == "image")
	{
		if(!id || id == "undefined")
		{
			$.dialog.alert("未指定ID");
			return false;
		}
		var url = get_url("open","input") + "&ext="+$.str.encode("png,jpg,gif,jpeg,bmp")+"&id="+id;
		$.dialog.open(url,{
			title: "图片管理器",
			lock : true,
			width: "80%",
			height: "70%",
			resize: false
		});
	}
}

function phpok_btn_view(btn,id)
{
	if(btn == "image")
	{
		var url = $("#"+id).val();
		if(!url || url == "undefined")
		{
			$.dialog.alert("图片不存在，请在表单中填写图片地址");
		}
		else
		{
			$.dialog({
				"title":"预览",
				"content": '<img src="'+url+'" border="0" />',
				"lock":true
			});
		}
	}
}

//清空
function phpok_btn_clear(btn,id)
{
	$("#"+id).val("");
}

//添加自定义字段
function ext_add(id,module)
{
	if(!id || id == 0)
	{
		//虚弹出窗口编辑或创建字段
		var url = get_url("fields","set1");
		if(module && module != "undefined")
		{
			url += "&module="+module;
		}
		$.dialog.open(url,{
			"title":"字段创建",
			"width":"730px",
			"height":"580px",
			"resize": false,
			"lock": true
		});
		return false;
	}
	var url = get_url("ext","add") + "&module="+module+"&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		autosave(module,module,auto_refresh);
	}
	else
	{
		$.dialog.alert(rs.content);
		return false;
	}
}

//创建新的字段

//读取浮动字段选择器
//module，是字段选择器ID
//words，已经使用的字段ID
function ext_fields(module,words,pageid,type)
{
	var url = get_url("ext","float") + "&module="+module+"&words="+$.str.encode(words);
	if(!pageid || pageid == "undefined")
	{
		pageid = "1";
	}
	url += "&pageid="+pageid;
	if(type && type != "undefined")
	{
		url += "&type="+type;
	}
	$.ajax({
		"url" : url,
		"async" : true,
		"dataType" : 'html',
		'cache' : false,
		'success' : function(content){
			if($("#float_fields").length>0)
			{
				$("#float_fields").remove();
			}
			$(".main .right").append(content);
			$(window).scroll(function(){
				 $("#float_fields").css({top:$(this).scrollTop()+40});
			});
		}
	});
}

function ext_delete(id,module,title)
{
	var qc = confirm("确定要删除扩展字段："+title+" 吗？删除后是不能恢复的！");
	if(qc == "0")
	{
		return false;
	}
	url = get_url("ext","delete");
	url += "&module="+$.str.encode(module);
	url += "&id="+id;
	var rs = json_ajax(url);
	if(rs.status == "ok")
	{
		autosave(module,module,auto_refresh);
	}
	else
	{
		alert(rs.content);
		return false;
	}
}

//编辑字段
function ext_edit(id,module)
{
	var url = get_url("ext","edit") + "&id="+id;
	url += "&module="+$.str.encode(module);
	$.dialog.open(url,{
		"title" : "编辑扩展字段属性",
		"width" : "700px",
		"height" : "95%",
		"resize" : false,
		"lock" : true,
		'close'	: function(){
			direct(window.location.href);
		}
	});
}

function _phpok_form_opt(val,id,eid,etype)
{
	if(!val || val == "undefined")
	{
		$("#"+id).html("").hide();
		return false;
	}
	var url = get_url("form","config") + "&id="+$.str.encode(val);
	if(eid && eid != "undefined")
	{
		url += "&eid="+eid;
	}
	if(etype && etype != "undefined")
	{
		url += "&etype="+etype;
	}
	$.ajax({
		"url" : url,
		"cache" : false,
		"dataType" : "html",
		"success" : function (rs)
		{
			if(rs && rs != "exit")
			{
				$("#"+id).html(rs).show();
			}
		}
	});
}

function phpok_btn_editor_picture(id)
{
	var url = get_url("edit","picture") + "&input="+id;
	$.dialog.open(url,{
		"title" : "图片库",
		"width" : "760px",
		"height" : "80%",
		"resize" : false,
		"lock" : true
	});
}

function phpok_btn_editor_file(id)
{
	var url = get_url("edit","file") + "&input="+id+"&nopic=1";
	$.dialog.open(url,{
		"title" : "附件资源",
		"width" : "760px",
		"height" : "80%",
		"resize" : false,
		"lock" : true
	});
}

function phpok_btn_editor_video(id)
{
	var url = get_url("edit","video") + "&input="+id+"&nopic=1";
	$.dialog.open(url,{
		"title" : "添加影音",
		"width" : "760px",
		"height" : "80%",
		"resize" : false,
		"lock" : true
	});
}

//删除单个主题关联
function phpok_title_delete_single(id)
{
	$("#"+id).val("");
	$("#title_"+id).hide();
	$("#phpok-btn-"+id+"-delete").hide();
}

//删除多个主题关联
function phpok_title_delete(id,val)
{
	if(val && val != "undefined")
	{
		//移除DIV值
		$("#"+id+"_div_"+val).remove();
		//移除值
		var c = $("#"+id).val();
		if(c == "" || c == "undefined")
		{
			$("#"+id+"_div").hide();
			$("#"+id+"_button_checkbox").hide();
			$("#"+id).val("");
			return true;
		}
		var clist = c.split(",");
		var n_list = new Array();
		var m = 0;
		for(var i=0;i<clist.length;i++)
		{
			if(clist[i] != val)
			{
				n_list[m] = clist[i];
				m++;
			}
		}
		if(n_list.length<1)
		{
			$("#"+id+"_div").hide();
			$("#"+id+"_button_checkbox").hide();
			$("#"+id).val("");
		}
		else
		{
			$("#"+id).val(n_list.join(","));
		}
		return true;
	}
	val = $.input.checkbox_join(id+"_div");
	if(!val || val == "undefined")
	{
		$.dialog.alert("请选择要删除的信息");
		return false;
	}
	var lst = val.split(",");
	for(var i=0;i<lst.length;i++)
	{
		phpok_title_delete(id,lst[i]);
	}
	return true;
}

//选择主题关联
function phpok_title_select(project_id,is_multi,title,input)
{
	var url = get_url("inp","title")+"&project_id="+$.str.encode(project_id);
	if(is_multi && is_multi != 'undefined')
	{
		url += "&multi=1";
	}
	url += "&identifier="+$.str.encode(input);
	$.dialog.open(url,{
		"title" : title,
		"width" : "760px",
		"height" : "80%",
		"resize" : false,
		"lock" : true,
		"ok": function(){
			var data = $.dialog.data("title_data");
			if(data)
			{
				$("#"+input).val(data);
				window.eval("action_"+input+"_show()");
				//window.setTimeout("action_"++"_show()",500);
			}
		}
	});
}

function phpok_user_delete(id,val)
{
	//移除DIV值
	$("#"+id+"_div_"+val).remove();
	//移除值
	var c = $("#"+id).val();
	if(c == "" || c == "undefined")
	{
		$("#"+id+"_div").html("");
		$("#"+id).val("");
		return true;
	}
	var clist = c.split(",");
	var n_list = new Array();
	var m = 0;
	for(var i=0;i<clist.length;i++)
	{
		if(clist[i] != val)
		{
			n_list[m] = clist[i];
			m++;
		}
	}
	if(n_list.length<1)
	{
		$("#"+id+"_div").html("");
		$("#"+id).val("");
	}
	else
	{
		$("#"+id).val(n_list.join(","));
	}
	return true;
}

/* PHPOK编辑器扩展按钮属性 */
function phpok_edit_type(id)
{
	var t = "#sMode_"+id;
	if($(t).val() == "可视化")
	{
		$(eval("pageInit_"+id+"()"));
		$(t).val("源代码");
	}
	else
	{
		$("#"+id).xheditor(false);
		eval("CodeMirror_PHPOK_"+id+"()");
		//$("#textarea_"+id+" xhe_default:first").hide();
		//$("#textarea_"+id+" CodeMirror:first").show();
		$(t).val("可视化");
	}
}

/*
 * PHPOK自定义表单中关于附件上传涉及到的JS操作
 * 最后修改时间：2013年10月19日
 * 此JS涉及到外部调用的JS函数get_url，json_ajax，$.str，$.dialog,$.parseJSON
 */
;(function($){
	$.phpok_upload = function(opts){
		var self = this;
		var defaults = {
			is_multi:false,
			id:'upload',
			flash_url:'js/swfupload/swfupload.swf',
			upload_url:get_url('upload'),
			file_size_limit:'2MB',
			file_types:'*.*',
			file_types_description:'文件',
			button_image_url:'images/swfupload.png',
			button_width:'92',
			button_height:'23',
			debug:false
		};
		this.opts = $.extend(defaults,opts);
		this.opts.eid = '#'+this.opts.id;
		this.swfupload = function(){
			var setting = {
				"flash_url"						: this.opts.flash_url,
				"upload_url"					: this.opts.upload_url,
				"post_params"					: {},
				"file_size_limit"				: this.opts.file_size_limit,	
				"file_types"					: this.opts.file_types,	
				"file_types_description"		: this.opts.file_types_description,	
				"file_upload_limit"				: "0",	
				"file_queue_limit"				: "0",	
				"button_window_mode"			: "transparent",
				"custom_settings"				: {
					"progressTarget"		    : this.opts.id+"_progress",
					"cancelButtonId"			: this.opts.id+"_btnCancel"
				},
				"debug"							: this.opts.debug,	
				"button_image_url"				: this.opts.button_image_url,	
				"button_placeholder_id"			: this.opts.id+"_spanButtonPlaceHolder",	
				"button_width"					: this.opts.button_width,	
				"button_height"					: this.opts.button_height,
				"button_action"					: this.opts.is_multi ? SWFUpload.BUTTON_ACTION.SELECT_FILES : SWFUpload.BUTTON_ACTION.SELECT_FILE,
				"file_queued_handler"			: fileQueued,
				"file_queue_error_handler"		: fileQueueError,	
				"file_dialog_complete_handler"	: this.swfupload_file_dialog_complete,	
				"upload_start_handler"			: uploadStart,	
				"upload_progress_handler"		: uploadProgress,	
				"upload_error_handler"			: uploadError,
				"upload_success_handler"		: this.swfupload_success,	
				"upload_complete_handler"		: this.swfupload_complete,	
				"queue_complete_handler"		: this.swfupload_queue_complete	
			};
			this.swfu = new SWFUpload(setting);
		};
		this.swfupload_submit = function (sessId,sessVal){
			var cate_id = $(this.opts.eid+"_cateid").val();
			if(cate_id && cate_id != "undefined")
			{
				this.swfu.addPostParam("cateid",cate_id);
			}
			if(sessId && sessVal)
			{
				this.swfu.addPostParam(sessId,sessVal);
			}
			this.swfu.startUpload();
		};
		this.swfupload_queue_complete = function (numFilesUploaded)
		{
			$(self.opts.eid+"_div_status").html("已上传文件数量："+numFilesUploaded+"");
		}
		//添加动作
		this.open_action = function(val){
			var content = $(this.opts.eid).val();
			if(this.opts.is_multi)
			{
				content = (content && content != "undefined") ? content + ","+val : val;
				var lst = $.unique(content.split(","));
				content = lst.join(',');
			}
			else
			{
				content = val;
			}
			$(this.opts.eid).val(content);
			this.preview_res(content);
		};
		this.swfupload_file_dialog_complete = function(numFilesSelected, numFilesQueued){
			if (numFilesSelected > 0)
			{
				$("#"+this.customSettings.cancelButtonId).attr("disabled",true);
			}
		};
		//更新附件信息
		this.update_res = function(id){
			var title = $(this.opts.eid+"_title_"+id).val();
			if(!title)
			{
				$.dialog.alert("名称不能为空");
				return false;
			}
			var url = api_url("res","update_title_note") +"&id="+id;
			url += "&title="+$.str.encode(title);
			var note = $(this.opts.eid+"_content_"+id).val();
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
		};
		//删除附件功能
		this.del_res = function(id){
			var content = $(this.opts.eid).val();
			if(!content || content == "undefined")
			{
				return false;
			}
			if(content == id)
			{
				$(this.opts.eid).val("");
				$(this.opts.eid+"_attrlist").html("").hide();
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
			$(this.opts.eid).val(content);
			this.preview_res(content);
		};
		//预览图片
		this.preview = function(id){
			var url = get_url("res_action","preview") + "&id="+id;
			$.dialog.open(url,{
				title: "预览",
				lock : true,
				width: "700px",
				height: "70%",
				resize: true
			});
		};
		//排序
		this.sort = function(){
			var t = [];
			$("."+this.opts.id+"_sort").each(function(i){
				var val = $(this).val();
				var data = $(this).attr("data");
				t.push({"id":val,"data":data});
			});
			t = t.sort(function(a,b){return parseInt(a['id'])>parseInt(b['id']) ? 1 : -1});
			var list = new Array();
			for(var i in t)
			{
				list[i] = t[i]['data'];
			}
			var val = list.join(",");
			$(this.opts.eid).val(val);
			this.preview_res(val);
		};
		//通过SWFUpload上传后响应
		this.swfupload_success = function(file,serverData){
			var progress = new FileProgress(file, this.customSettings.progressTarget);
			progress.setComplete();
			progress.setStatus("上传完成！");
			progress.toggleCancel(false);
			var rs =  $.parseJSON(serverData);
			//如果附件只支持一个
			if(self.opts.is_multi)
			{
				var content = $(self.opts.eid).val();
				content = content ? content+","+rs.id : rs.id;
				$(self.opts.eid).val(content);
			}
			else
			{
				var content = rs.id;
				$(self.opts.eid).val(rs.id);
			}
			self.preview_res(content);
		};
		//全部上传完成后，禁用取消按钮
		this.swfupload_complete = function(file){
			if (this.getStats().files_queued === 0)
			{
				$("#"+this.customSettings.cancelButtonId).attr("disabled",true);
				//document.getElementById().disabled = true;
			}
		};
		//获取列表
		this.preview_res = function(id){
			$(this.opts.eid+"_sort").hide();
			if(!id || id == "undefined")
			{
				id = $(this.opts.eid).val();
				if(!id || id == "undefined")
				{
					$(this.opts.eid+"_attrlist").html("").hide();
					return false;
				}
			}
			var url = api_url("res","idlist") + "&id="+$.str.encode(id);
			var rs = json_ajax(url);
			if(rs.status == "ok")
			{
				var list = rs.content;
				var total = count(list);
				var html = '<table class="ext_upload">';
				var t = 1;
				var tmp = id.split(",");
				for(var mt=0;mt<tmp.length;mt++)
				{
					var i = tmp[mt];
					html += '<tr>';
					html += '<td class="ext_img"><img src="'+list[i]["ico"]+'" alt="'+list[i]["title"]+'" /></td>';
					html += '<td valign="top" class="ext_res">';
					html += '<div class="ext_title" style="width:360px;margin-bottom:5px;">';
					html += '<input type="text" id="'+this.opts.id+'_title_'+list[i]["id"]+'" value="'+list[i]["title"]+'" class="ext_input" placeholder="名称" style="width:350px;" />';
					html += '</div><div class="ext_note" style="width:360px;margin-bottom:5px;">';
					html += '<textarea id="'+this.opts.id+'_content_'+list[i]["id"]+'" class="ext_textarea" placeholder="备注" style="width:350px;height:40px;">'+list[i]["note"]+'</textarea>';
					html += '</div><div class="ext_action" style="width:360px;">';
					html += '<input type="button" value="更新附件信息" class="ext_btn" onclick="obj_'+this.opts.id+'.update_res('+list[i]['id']+')" /> ';
					html += '<input type="button" value="预览" class="ext_btn" onclick="obj_'+this.opts.id+'.preview('+list[i]["id"]+')" />';
					html += '<input type="button" value="删除" class="ext_btn red" onclick="obj_'+this.opts.id+'.del_res('+list[i]["id"]+');" /> ';
					if(total>1)
					{
						html += '<input type="text" class="ext_taxis '+this.opts.id+'_sort" value="'+t.toString()+'" data="'+list[i]['id']+'" />';
					}
					html += '</div></td></tr>';
					t++;
				}
				html += '</table>';
				$(this.opts.eid+"_attrlist").html(html).show();
				if(total>1)
				{
					$(this.opts.eid+"_sort").show();
				}
			}
			else
			{
				$(this.opts.eid+"_attrlist").hide();
				$.dialog.alert(rs.content);		
			}
		}
	};
})(jQuery);

