<?php
/***********************************************************
	Filename:  model/config.php
	Note	: 配置信息，此信息主要存储在data目录下
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-11-27 11:40
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class config_model extends base_model
{
	function __construct()
	{
		parent::model();
	}
}
?>