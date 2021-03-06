<?php
/***********************************************************
	Filename: phpok/model/sysmenu.php
	Note	: 后台核心应用管理，主表：qinggan_sysmenu
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-27 14:36
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class sysmenu_model extends phpok_model
{
	function __construct()
	{
		parent::model();
	}

	# 获取一条信息
	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."sysmenu WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	function get_one_condition($condition="")
	{
		$sql = "SELECT * FROM ".$this->db->prefix."sysmenu ";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		return $this->db->get_one($sql);
	}

	# 取得指定菜单
	function get_list($parent_id=0,$status=0)
	{
		# 当未指定子菜单时，直接获取父栏目信息
		$parent_id = intval($parent_id);
		$sql = "SELECT * FROM ".$this->db->prefix."sysmenu WHERE parent_id=".$parent_id." ";
		if($status)
		{
			$sql .= " AND status=1 ";
		}
		$sql .= " ORDER BY taxis ASC,id DESC";
		return $this->db->get_all($sql);
	}

	# 取得全部菜单，并生成树状分类，仅限后台使用
	function get_all($site_id=0,$status=0)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."sysmenu WHERE 1=1 ";
		if($status)
		{
			$sql .= " AND status=1 ";
		}
		if($site_id)
		{
			$sql_in = "0,".$site_id;
			$sql .= " AND site_id IN(".$sql_in.") ";
		}
		$sql .= " ORDER BY taxis ASC,id DESC";
		$tmp_list = $this->db->get_all($sql);
		if(!$tmp_list) return false;
		$rslist = array();
		foreach($tmp_list AS $key=>$value)
		{
			if(!$value["parent_id"])
			{
				$rslist[$value["id"]] = $value;
			}
		}
		foreach($tmp_list AS $key=>$value)
		{
			if($value["parent_id"])
			{
				$rslist[$value["parent_id"]]["sublist"][$value["id"]] = $value;
			}
		}
		return $rslist;
	}

	# 根据条件取得菜单
	function get_menu_id($site_id=0,$ctrl="",$func="",$identifier="")
	{
		if(!$ctrl) return false;
		$sql = " SELECT * FROM ".$this->db->prefix."sysmenu WHERE appfile='".$ctrl."' ";
		if($site_id)
		{
			$sql .= " AND site_id IN(0,".$site_id.") ";
		}
		$sql .= " AND parent_id !=0 ";
		$sql.= " ORDER BY id ASC";
		$tmplist = $this->db->get_all($sql);
		if(!$tmplist) return false;
		# 最接近
		$first = $second = $third = false;
		foreach($tmplist AS $key=>$value)
		{
			if($value["identifier"] && $value["identifier"] == $identifer && $value["func"] && $value["func"] == $func && $value["appfile"] == $ctrl)
			{
				if(!$third)
				{
					$third = true;
					$third_id = $value["id"];
				}
			}
			if(!$value["identifier"] && $value["func"] && $value["func"] == $func && $value["appfile"] == $ctrl)
			{
				if(!$second)
				{
					$second = true;
					$second_id = $value["id"];
				}
			}
			if(!$value["identifier"] && !$value["func"] && $value["appfile"] == $ctrl)
			{
				if(!$first)
				{
					$first = true;
					$first_id = $value["id"];
				}
			}
		}
		if($third)
		{
			return $third_id;
		}
		if($second)
		{
			return $second_id;
		}
		if($first)
		{
			return $first_id;
		}
		return false;
	}

	# 更新状态
	function update_status($id,$status=0)
	{
		$sql = "UPDATE ".$this->db->prefix."sysmenu SET status='".$status."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//更新排序
	function update_taxis($id,$taxis=255)
	{
		$sql = "UPDATE ".$this->db->prefix."sysmenu SET taxis='".$taxis."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	# 存储核心菜单
	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if(!$id)
		{
			return $this->db->insert_array($data,"sysmenu");
		}
		else
		{
			return $this->db->update_array($data,"sysmenu",array("id"=>$id));
		}
	}

}
?>