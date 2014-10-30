/***********************************************************
	Filename: js/global.js
	Note	: 前台全局JS
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年07月01日 05时30分
***********************************************************/
function logout(t)
{
	$.dialog.confirm("您好，<span style='color:red'>"+t+"</span>：<div style='text-indent:24px;'>您确定要退出吗?</div>",function(){
		var url = get_url("logout");
		window.location.href = url;
	});
}

function top_search_check()
{
	var k = $("#top_keywords").val();
	if(!k)
	{
		$.dialog.alert("关键字不能为空");
		return false;
	}
}
//jQuery插件之购物车相关操作
;(function($){
	$.cart = {
		//添加到购物车中
		//id为产品ID
		add: function(id){
			var url = api_url('cart','add','id='+id);
			var rs = json_ajax(url);
			if(rs.status == 'ok')
			{
				$.dialog.notice({
					title: '友情提示',
					width: 130,// 必须指定一个像素宽度值或者百分比，否则浏览器窗口改变可能导致artDialog收缩
					content: '成功将商品加入购物车',
					icon: 'face-smile',
					time: 5
				});
				//更新头部购物车信息
				this.total();
			}
			else
			{
				$.dialog.alert(rs.content);
				return false;
			}
		},
		//更新产品数量
		//id为购物车自动生成的ID号（不是产品ID号，请注意）
		update: function(id){
			var qty = $("#qty_"+id).val();
			if(!qty || parseInt(qty) < 1)
			{
				$.dialog.alert('购物车产品数量不能为空');
				return false;
			}
			var url = api_url('cart','qty')+"&id="+id+"&qty="+qty;
			var rs = json_ajax(url);
			if(rs.status == 'ok')
			{
				$.phpok.reload();
			}
			else
			{
				if(!rs.content) rs.content = '更新失败';
				$.dialog.alert(rs.content);
				return false;
			}
		},
		//计算购物车数量
		//这里使用异步Ajax处理
		total:function(){
			var url = api_url('cart','total');
			$.ajax({
				'url':url,
				'dataType':'json',
				'success':function(rs){
					if(rs.status == 'ok')
					{
						$("#head_cart_num").html(rs.content);
					}
				}
			});
		},
		//删除产品信息
		//id为购物车自动生成的ID号（不是产品ID号，请注意）
		del: function(id){
			var t = $("#title_"+id).text();
			$.dialog.confirm("确定要删除产品：<span style='color:red;font-weight:bold;'>"+t+"</span> 吗?",function(){
				var url = api_url('cart','delete')+"&id="+id;
				var rs = json_ajax(url);
				if(rs.status == 'ok')
				{
					$.phpok.reload();
				}
				else
				{
					if(!rs.content) rs.content = '删除失败';
					$.dialog.alert(rs.content);
					return false;
				}
			});
		}
	};
})(jQuery);

//