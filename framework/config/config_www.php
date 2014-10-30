<?php
/***********************************************************
	Filename:  config/config_admin.php
	Note	: 后台控制器
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-17 15:36
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}

//前台自动加载的JS，此处的JS对应的CSS，HTML及图片路径是相对于网站根目录
$config["autoload_js"] .= ",global.www.js";
//jQUery插件Superslide
$config["autoload_js"] .= ",jquery.superslide.js";

$config["autoload_func"] = "";


//$config["engine"]["cache"]["status"] = false;

$config['is_mobile'] = false;
