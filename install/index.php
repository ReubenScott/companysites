<?php
/***********************************************************
	Filename: install/index.php
	Note	: 安装升级包
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2014年1月7日
***********************************************************/
define("PHPOK_SET",true);
//定义根目录
define("INSTALL_DIR",str_replace("\\","/",dirname(__FILE__))."/");
define("ROOT",INSTALL_DIR."../");

include_once(INSTALL_DIR."global.php");

if(is_file(ROOT."data/install.lock"))
{
	error("您已安装过PHPOK4，要重新安装请删除data/install.lock文件");
}

if(!is_file(ROOT."config.php"))
{
	error("配置文件：config.php 不存在，请检查！");
}

if(!G("step"))
{
	include(INSTALL_DIR."tpl/index.php");
	exit;
}
elseif(G("step") == 'check')
{
	$rslist = array();
	//检测是否支持PHP5
	$rslist['php5'] = array("title"=>"PHP5","status"=>(PHP_VERSION>=5 ? true : false));
	//检测MySQL是支持
	$rslist['mysql'] = func_check("mysql_close","MySQL 支持");
	if(!$rslist['mysql']['status']) $rslist['mysql'] = func_check("mysqli_close","MySQL 支持");
	//检测是否支持Curl
	$rslist['curl'] = func_check("curl_close","Curl 支持");
	$rslist['socket'] = func_check("socket_accept","Socket 支持");
	$rslist['session'] = func_check("session_start","Session 支持");
	$rslist['xml'] = func_check("xml_set_object","XML解析");
	$rslist['gd'] = func_check("gd_info","GD库支持");
	$rslist['zlib'] = func_check("gzclose","压缩文件支持(Zlib)");
	//判断可写目录
	//创建文件
	$tmpfile = time().".txt";
	@touch(ROOT."data/".$tmpfile);
	$rslist["data_write"] = array("title"=>"data 目录可写","status"=>(is_file(ROOT."data/".$tmpfile) ? true : false));
	@touch(ROOT."data/cache/".$tmpfile);
	$rslist["cache_write"] = array("title"=>"data/cache 目录可写","status"=>(is_file(ROOT."data/cache/".$tmpfile) ? true : false));
	@touch(ROOT."data/session/".$tmpfile);
	$rslist["session_write"] = array("title"=>"data/session 目录可写","status"=>(is_file(ROOT."data/session/".$tmpfile) ? true : false));
	@touch(ROOT."data/tpl_admin/".$tmpfile);
	$rslist["admin_write"] = array("title"=>"data/tpl_admin 目录可写","status"=>(is_file(ROOT."data/tpl_admin/".$tmpfile) ? true : false));
	@touch(ROOT."data/tpl_www/".$tmpfile);
	$rslist["www_write"] = array("title"=>"data/tpl_www 目录可写","status"=>(is_file(ROOT."data/tpl_www/".$tmpfile) ? true : false));
	@touch(ROOT."res/".$tmpfile);
	$rslist["res_write"] = array("title"=>"res 目录可写","status"=>(is_file(ROOT."res/".$tmpfile) ? true : false));
	//删除附件
	@unlink(ROOT."data/".$tmpfile);
	@unlink(ROOT."data/cache/".$tmpfile);
	@unlink(ROOT."data/session/".$tmpfile);
	@unlink(ROOT."data/tpl_admin/".$tmpfile);
	@unlink(ROOT."data/tpl_www/".$tmpfile);
	@unlink(ROOT."res/".$tmpfile);
	//
	include(INSTALL_DIR."tpl/check.php");
}
elseif(G("step") == "config")
{
	//配置数据库链接
	include(ROOT."config.php");
	$dbconfig = $config['db'];
	unset($config);
	//支持的数据库类型
	$is_mysql = function_exists("mysql_close") ? true : false;
	$is_mysqli = function_exists("mysqli_close") ? true : false;
	include(INSTALL_DIR."tpl/config.php");
}
elseif(G("step") == "saveconfig")
{
	$file = G("file");
	if(!$file) $file = "mysql";
	$dbconfig = array("file"=>$file);
	$dbconfig['host'] = G("host",false);
	$dbconfig['port'] = G("port",false);
	$dbconfig['user'] = G("user",false);
	$dbconfig['pass'] = G("pass",false);
	$dbconfig['data'] = G("data",false);
	$dbconfig['prefix'] = G("prefix",false);
	if(!$dbconfig['user']) exit("请填写数据库账号");
	if(!$dbconfig['data']) exit("数据库名称不能为空");
	if(!$dbconfig['prefix']) $dbconfig['prefix'] = 'qinggan_';
	if(!$dbconfig['host']) $dbconfig['host'] = 'localhost';
	if(!$dbconfig['port']) $dbconfig['port'] = '3306';
	//测试数据库链接是否正则
	$cls_sql_file = ROOT."framework/engine/db/".$file.".php";
	if(!is_file($cls_sql_file)) exit("数据库类文件：".$cls_sql_file."不存在！");
	include($cls_sql_file);
	$db_name = "db_".$file;
	$db = new $db_name($dbconfig);
	//判断数据库连接正确与否
	if(!$db->conn_status())
	{
		exit("数据库连接失败，请检查您的填写信息");
	}
	//存储配置信息
	$content = file_get_contents(ROOT."config.php");
	//查找替换
	$content = preg_replace('/\$config\["db"\]\["file"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["file"] = "'.$dbconfig['file'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["host"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["host"] = "'.$dbconfig['host'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["port"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["port"] = "'.$dbconfig['port'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["user"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["user"] = "'.$dbconfig['user'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["pass"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["pass"] = "'.$dbconfig['pass'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["data"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["data"] = "'.$dbconfig['data'].'";',$content);
	$content = preg_replace('/\$config\["db"\]\["prefix"\]\s*=\s*[\'|"][a-zA-Z0-9\-\_]*[\'|"];/isU','$config["db"]["prefix"] = "'.$dbconfig['prefix'].'";',$content);
	file_put_contents(ROOT."config.php",$content);
  exit("ok");	
}
elseif(G("step") == "import")
{

	$db = connect_db("tpl","index.php?step=config");
	//导入SQL数据
	if(!is_file(INSTALL_DIR."phpok.sql"))
	{
		error("缺少SQL文件：install/phpok.sql，请检查您的安装包是否完整");
	}
	$sql = file_get_contents(INSTALL_DIR."phpok.sql");
	if($db->prefix != "qinggan_")
	{
		$sql = str_replace("qinggan_",$db->prefix,$sql);
	}
	//执行安装
	format_sql($sql);
	error("数据库信息导入成功，请稍等，正在执行一下：网站信息配置","index.php?step=site","ok",3);
}
elseif(G("step") == "site")
{
	//配置网站信息
	$rs = root_url();
	$rs['title'] = "PHPOK企业通用网站";
	
	include(INSTALL_DIR."tpl/site.php");
}
elseif(G("step") == "save")
{
	$title = G("title",false);
	$dir = G("dir",false);
	$domain = G("domain",false);
	$admin_name = G("admin_name",false);
	$admin_pass = G("admin_pass",false);
	if(!$title || !$dir || !$domain || !$admin_name || !$admin_pass)
	{
		error("网站信息配置没有填写完整，请返回修改","index.php?c=site","error");
	}
	//连接数据库
	$db = connect_db("tpl","index.php?step=config");
	//清除地址库信息
	$sql = "TRUNCATE ".$db->prefix."address";
	$db->query($sql);
	//清除管理员信息
	$sql = "TRUNCATE ".$db->prefix."adm";
	$db->query($sql);
	//清除管理员权限
	$sql = "TRUNCATE ".$db->prefix."adm_popedom";
	$db->query($sql);
	//清除购物车信息
	$sql = "TRUNCATE ".$db->prefix."cart";
	$db->query($sql);
	$sql = "TRUNCATE ".$db->prefix."cart_product";
	$db->query($sql);
	//清空开发日志
	$sql = "TRUNCATE ".$db->prefix."log";
	$db->query($sql);
	//清空订单信息
	$sql = "TRUNCATE ".$db->prefix."order";
	$db->query($sql);
	$sql = "TRUNCATE ".$db->prefix."order_product";
	$db->query($sql);
	$sql = "TRUNCATE ".$db->prefix."order_address";
	$db->query($sql);
	//清空支付方案
	$sql = "TRUNCATE ".$db->prefix."payment";
	$db->query($sql);
	//清空插件
	$sql = "TRUNCATE ".$db->prefix."plugins";
	$db->query($sql);
	//清空回复
	$sql = "TRUNCATE ".$db->prefix."reply";
	$db->query($sql);
	$sql = "UPDATE ".$db->prefix."list SET replydate=0";
	$db->query($sql);
	//清空SESSION信息
	$sql = "TRUNCATE ".$db->prefix."session";
	$db->query($sql);
	//清空标签
	$sql = "TRUNCATE ".$db->prefix."tag";
	$db->query($sql);
	$sql = "TRUNCATE ".$db->prefix."tag_list";
	$db->query($sql);
	//清空临时存储
	$sql = "TRUNCATE ".$db->prefix."temp";
	$db->query($sql);
	//更新网站数据
	$sql = "TRUNCATE ".$db->prefix."site_domain";
	$db->query($sql);
	$sql = "INSERT INTO ".$db->prefix."site_domain(id,site_id,domain) VALUES(1,1,'".$domain."')";
	$db->query($sql);
	$sql = "DELETE FROM ".$db->prefix."site WHERE id !=1";
	$db->query($sql);
	$sql = "UPDATE ".$db->prefix."site SET title='".$title."',dir='".$dir."',email_pass='' WHERE id=1";
	$db->query($sql);
	//更新管理员
	$pass_sub = rand(11,99);
	$pass = md5($admin_pass.$pass_sub).":".$pass_sub;
	$sql = "INSERT INTO ".$db->prefix."adm(account,pass,email,status,if_system) VALUES('".$admin_name."','".$pass."','','1',1)";
	$db->query($sql);
	//网站安装成功
	@touch(ROOT."data/install.lock");
	include(INSTALL_DIR."tpl/end.php");
}

?>