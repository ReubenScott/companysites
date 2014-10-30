<?php
/***********************************************************
	Filename: config.php
	Note	: 配置文件，此配置应用于全局，修改完后建议设为只读
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-15 15:46
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
// 连接数据库引挈，当前配置仅适用于小站点使用
$config["db"]["file"] = "mysql";
$config["db"]["host"] = "localhost";
$config["db"]["port"] = "3306";
$config["db"]["user"] = "root";
$config["db"]["pass"] = "123456";
$config["db"]["data"] = "phpok";
$config["db"]["prefix"] = "qinggan_";
$config["db"]["debug"] = false;

?>