<?php include_once(INSTALL_DIR."tpl/head.php");?>
<script type="text/javascript" src="../js/jquery.js"></script>
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
	<h3>五、完成安装</h3>
	<table>
	<tr>
		<td align="right" width="150px" height="30px">网站前台：</td>
		<td><a href="../index.php">点这里访问</a></td>
	</tr>
	<tr>
		<td align="right" height="30px">网站后台：</td>
		<td><a href="../admin.php">点这里访问后台</a></td>
	</tr>
	</table>
</div>
</body>
</html>