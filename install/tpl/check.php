<?php include_once(INSTALL_DIR."tpl/head.php");?>
	<h3>二、安装环境检测</h3>
	<ul class="check">
		<?php foreach($rslist AS $key=>$value){ ?>
		<li><?php echo $value['title'];?>：<?php echo $value['status'] ? '<span class="green">√</span>' : '<span class="red">×</span>';?></li>
		<?php } ?>
	</ul>
	<div class="clear"></div>
	<?php
	$show = true;
	if(!$rslist['php5']['status'])
	{
		echo '<div class="red">您的空间不支持PHP5，不能执行下一步安装</div>';
		$show = false;
	}
	if(!$rslist['mysql']['status'])
	{
		echo '<div class="red">您的空间不支持MySQL组件，不能执行下一步安装</div>';
		$show = false;
	}
	if(!$rslist['curl']['status'] && !$rslist['socket']['status'])
	{
		echo '<div class="red">您的空间不支持Curl或Socket功能，不能执行下一步安装</div>';
		$show = false;
	}
	if(!$rslist['session']['status'])
	{
		echo '<div class="red">您的空间不支持Session，不能执行下一步安装</div>';
		$show = false;
	}
	if(!$rslist['gd']['status'])
	{
		echo '<div class="red">您的空间不支持GD库功能，不能执行下一步安装</div>';
		$show = false;
	}
	?>
	<div style="margin:10px;">
		<table>
		<tr>
			<td><input type="button" value="上一步，阅读授权" class="submit" onclick="window.location.href='index.php'" /></td>
			<?php if($show){ ?>
			<td><input type="button" value="下一步，配置数据库连接" class="submit" onclick="window.location.href='index.php?step=config'" /></td>
			<?php }	?>
		</tr>
		</table>
	</div>
</div>
</body>
</html>