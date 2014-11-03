<?php include_once(INSTALL_DIR."tpl/head.php");?>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
function check_save()
{
	var url = "index.php?step=saveconfig&file="+$("#file").val();
	var host = $("#host").val();
	if(!host)
	{
		host = 'localhost';
		$("#host").val(host);
	}
	url += "&host="+encodeURIComponent(host);
	var port = $("#port").val();
	if(!port)
	{
		port = "3306";
		$("#port").val(port);
	}
	url += "&port="+port;
	var user = $("#user").val();
	if(!user)
	{
		alert("请填写数据库账号！");
		return false;
	}
	url += "&user="+encodeURIComponent(user);
	//密码
	var pass = $("#pass").val();
	if(!pass)
	{
		var q = confirm("请填写数据库密码，如果您确定为空，请按确定");
		if(q == '0') return false;
	}
	if(pass)
	{
		url += "&pass="+encodeURIComponent(pass);
	}
	var data = $("#data").val();
	if(!data)
	{
		alert("请填写您的数据库名称，不能为空");
		return false;
	}
	url += "&data="+encodeURIComponent(data);
	var prefix = $("#prefix").val();
	if(!prefix) prefix = 'qinggan_';
	url += "&prefix="+prefix;
	$.ajax({
		"url":url,
		"cache":false,
		"async":false,
		"dataType":"html",
		"success":function(info){
			if(info == 'ok')
			{
				alert("数据库信息配置完成！");
				window.location.href = "index.php?step=import";
			}
			else
			{
				if(!info) info="操作失败";
				alert(info);
				return false;
			}
		}
	});
	return false;
}
</script>
	<h3>三、配置数据库信息</h3>
	<form method="post" onsubmit="return check_save()">
	<table>
	<tr>
		<td align="right" width="150px">连接数据库类型：</td>
		<td><select name="file" id="file">
			<?php if($is_mysql) { ?><option value="mysql">MySQL</option><?php } ?>
			<?php if($is_mysqli) { ?><option value="mysqli" selected>MySQLi</option><?php } ?>
		</select></td>
	</tr>
	<tr>
		<td align="right">数据库服务器：</td>
		<td><input type="text" name="host" id="host" value="<?php echo $dbconfig['host'];?>" /></td>
	</tr>
	<tr>
		<td align="right">端口：</td>
		<td><input type="text" name="port" id="port" value="<?php echo $dbconfig['port'] ? $dbconfig['port'] : '3306';?>" /></td>
	</tr>
	<tr>
		<td align="right">账号：</td>
		<td><input type="text" name="user" id="user" value="<?php echo $dbconfig['user'] ? $dbconfig['user'] : 'root';?>" /></td>
	</tr>
	<tr>
		<td align="right">密码：</td>
		<td><input type="text" name="pass" id="pass" value="<?php echo $dbconfig['pass'] ? $dbconfig['pass'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">数据库名称：</td>
		<td><input type="text" name="data" id="data" value="<?php echo $dbconfig['data'] ? $dbconfig['data'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">数据表前缀：</td>
		<td><input type="text" name="prefix" id="prefix" value="<?php echo $dbconfig['prefix'] ? $dbconfig['prefix'] : '';?>" /></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td><input type="button" value="上一步，检测环境" class="submit" onclick="window.location.href='index.php?step=check'" /></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td><input type="submit" value="下一步，存储配置信息并导入SQL信息" class="submit" /></td>
	</tr>
	</table>
	</form>
</div>
</body>
</html>