<?php
/***********************************************************
	Filename: phpok/config/config_admin.php
	Note	: 后台控制器
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-17 15:36
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}

//后台自动加载的JS，此处的JS对应的CSS，HTML及图片路径是相对于网站根目录
//Swfupload 上传控件涉及到的JS，需要同时加载css/swfupload.css
//$config['autoload_js'] .= ',js/swfupload/swfupload.js';
//加载Ueditor可视化编辑器
//$config['autoload_js'] .= ',js/ueditor/ueditor.config.js,js/ueditor/ueditor.all.min.js,js/ueditor/lang/zh-cn/zh-cn.js';
//时间日期控制，需要加载网站根目录下的time.css
//$config['autoload_js'] .= ',js/dyndatetime/jquery.dyndatetime.pack.js,js/dyndatetime/lang/calendar-utf8.js';

$config['autoload_js'] .= ',global.admin.js';


$config["db"]["debug"] = false;

$config["engine"]["cache"]["status"] = false;


//后台登录界面皮肤更换，仅限登录界面
$config['admin_tpl_login'] = 'login';