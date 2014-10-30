<?php
/***********************************************************
	Filename:  model/menu.php
	Note	: 导航菜单管理
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-02-08 16:59
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class menu_model extends base_model
{
	var $site_id = 0;
	function __construct()
	{
		parent::model();
	}

	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."menu WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
}
?>