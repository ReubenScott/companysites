<?php
/***********************************************************
	Filename:  model/search.php
	Note	: 搜索涉及到的查询，这里仅用于全站搜索简单查询
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月21日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class search_model extends base_model
{
	function __construct()
	{
		parent::model();
	}

	//取得查询结果数量
	function get_total($condition="")
	{
		$sql = "SELECT count(l.id) FROM ".$this->db->prefix."list l WHERE l.status=1 AND l.hidden=0 ";
		if($condition)
		{
			$sql.= " AND ".$condition;
		}
		return $this->db->count($sql);
	}

	//查询ID数量
	function id_list($condition="",$offset=0,$psize=30)
	{
		$sql .= "SELECT l.id FROM ".$this->db->prefix."list l WHERE l.status=1 AND l.hidden=0 ";
		if($condition)
		{
			$sql.= " AND ".$condition;
		}
		$sql.= " ORDER BY l.sort DESC,l.dateline DESC,l.id DESC LIMIT ".intval($offset).",".intval($psize);
		return $this->db->get_all($sql);
	}

	//
}