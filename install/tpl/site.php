<?php include_once(INSTALL_DIR."tpl/head.php");?>
<script type="text/javascript" src="../framework/jquery.js"></script>
<script type="text/javascript">
function check_save()
{
	var title = $("#title").val();
	if(!title)
	{
		alert("网站名称不能为空");
	}
	var dir = $("#dir").val();
	if(!dir)
	{
		alert("网站目录不能为空，如果是根目录，请填写 / ");
		return false;
	}
	var domain = $("#domain").val();
	if(!domain)
	{
		alert("网站域名不能为空");
		return false;
	}
	var admin_name = $("#admin_name").val();
	if(!admin_name)
	{
		alert("管理员账号不存在");
		return false;
	}
	var admin_pass = $("#admin_pass").val();
	if(!admin_pass)
	{
		alert("管理员密码不能为空，请填写");
		return false;
	}
	return true;
}
</script>
	<h3>四、配置网站信息</h3>
	<form method="post" action="index.php?step=save" onsubmit="return check_save()">
	<table>
	<tr>
		<td align="right" width="150px">网站名称：</td>
		<td><input type="text" name="title" id="title" value="<?php echo $rs['title'];?>" style="width:300px;" /></td>
	</tr>
	<tr>
		<td align="right">网站目录：</td>
		<td><input type="text" name="dir" id="dir" value="<?php echo $rs['dir'];?>" style="width:300px;" /></td>
	</tr>
	<tr>
		<td align="right">网站域名：</td>
		<td><input type="text" name="domain" id="domain" value="<?php echo $rs['domain'];?>" style="width:300px;" /></td>
	</tr>
	<tr>
		<td align="right">管理员账号：</td>
		<td><input type="text" name="admin_name" id="admin_name" value="admin" style="width:300px;" /></td>
	</tr>
	<tr>
		<td align="right">管理员密码：</td>
		<td><input type="text" name="admin_pass" id="admin_pass" value="" style="width:300px;" /></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
		<td><input type="submit" value="完成安装" class="submit" /></td>
	</tr>
	</table>
	</form>
</div>
</body>
</html>