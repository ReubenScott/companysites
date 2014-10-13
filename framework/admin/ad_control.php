<?php
/***********************************************************
	Filename: {phpok}/admin/ad_control.php
	Note	: 广告管理系统（自动生成JS被调用）及统计管理
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年5月13日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class ad_control extends phpok_control
{
	function __construct()
	{
		parent::control();
	}

	function index_f()
	{
		$pageid = $this->get($this->config["pageid"],"int");
		if(!$pageid) $pageid = 1;
		$psize = $this->config["psize"];
		if(!$psize) $psize = 30;
		$offset = ($pageid-1) * $psize;
		$pageurl = $this->url("ad");
		$condition = "1=1";
		$keywords = $this->get("keywords");
		if($keywords)
		{
			$condition .= " AND title LIKE '%".$keywords."%'";
			$pageurl .= "&keywords=".rawurlencode($keywords);
			$this->assign("keywords",$keywords);
		}
		$rslist = $this->model('ad')->get_list($condition,$offset,$psize,"id");
		if($rslist)
		{
			$idlist = array_keys($rslist);
			$idstring = implode(",",$idlist);
			$date = $this->get("date");
			$time = $this->system_time;
			if($date)
			{
				$pageurl .= "&date=".rawurlencode($date);
				$time = strtotime($date);
			}
			$extlist = $this->model('ad')->stat_list_num($idstring,date("Y-m-d",$time));
			foreach($rslist AS $key=>$value)
			{
				$value["today_pv"] = intval($extlist[$value["id"]]["pv"]);
				$value["today_ip"] = intval($extlist[$value["id"]]["ip"]);
				$value["today_uv"] = intval($extlist[$value["id"]]["uv"]);
				$value["today_hits"] = intval($extlist[$value["id"]]["hits"]);
				$rslist[$key] = $value;
			}
			$this->assign("date",date("Y-m-d",$time));
		}
		$total = $this->model('ad')->get_total($condition);
		$pagelist = phpok_page($pageurl,$total,$pageid,$psize,"home=首页&prev=上一页&next=下一页&last=尾页&half=5&opt=第[num]页&add=[total]/[psize]&always=1");
		$this->assign("rslist",$rslist);
		$this->assign("pagelist",$pagelist);
		$this->view("ad_list");
	}

	function set_f()
	{
		$id = $this->get("id","int");
		if($id)
		{
			$rs = $this->model('ad')->get_one($id);
			$this->assign("rs",$rs);
			$this->assign("id",$id);
		}
		$catelist = $this->model('res')->cate_all();
		$this->assign("catelist",$catelist);
		$this->view("ad_set");
	}

	function save_f()
	{
		$id = $this->get("id","int");
		$title = $this->get("title");
		$pic_id = $this->get("pic_id","int");
		$content = $this->get("content","html");
		$url = $this->get("url");
		if(!$title)
		{
			error("广告名称不能为空",$this->url("ad","set")."&id=".$id,"error");
		}
		$array = array("title"=>$title,"pic_id"=>$pic_id,"content"=>$content,"url"=>$url);
		$this->model('ad')->save($array,$id);
		error("广告信息配置成功",$this->url("ad"),"ok");
	}

	function status_f()
	{
		$id = $this->get("id","int");
		if(!$id)
		{
			json_exit("未指定广告ID");
		}
		$rs = $this->model('ad')->get_one($id);
		$status = $rs["status"] ? 0 : 1;
		$this->model('ad')->set_status($id,$status);
		json_exit("设置成功",true);
	}

	function delete_f()
	{
		$id = $this->get("id","int");
		if(!$id)
		{
			json_exit("未指定广告ID");
		}
		$this->model('ad')->ad_delete($id);
		json_exit("删除成功",true);
	}

	//流量统计
	function stat_f()
	{
		$id = $this->get("id","int");
		if(!$id)
		{
			error("未指定广告ID",$this->url("ad"),"error");
		}
		$rs = $this->model('ad')->get_one($id);
		if(!$rs)
		{
			error("广告信息不存在",$this->url("ad"),"error");
		}
		$this->assign("rs",$rs);
		$date = $this->get("date");
		if(!$date) $date = date("Y-m-d",$this->system_time);
		$type = $this->get("type");
		if(!$type) $type = "hour";
		$format = "%Y-%m-%d";
		$condition = "";
		if($type == "year")
		{
			$format = "%Y";
		}
		elseif($type == "month")
		{
			$format = "%m";
			$condition = " FROM_UNIXTIME(addtime,'%Y')='".date("Y",strtotime($date))."' ";
			$date = date("Y",strtotime($date));
		}
		elseif($type == "hour")
		{
			$format = "%k";
			$condition = " FROM_UNIXTIME(addtime,'%Y-%m-%d')='".$date."' ";
		}
		else
		{
			$format = "%d";
			$condition = " FROM_UNIXTIME(addtime,'%Y-%m')='".date("Y-m",strtotime($date))."' ";
			$date = date("Y-m",strtotime($date));
		}
		$rslist = $this->model('ad')->stat_num($id,$format,$condition);
		$pvlist = $this->model('ad')->pv_num($id,$format,$condition);
		$this->assign("rslist",$rslist);
		$this->assign("pvlist",$pvlist);
		if($pvlist)
		{
			$tlist = array();
			foreach($pvlist AS $key=>$value)
			{
				$tlist[$value["title"]]["pv"] = $value["pv"];
				$tlist[$value["title"]]["ip"] = 0;
				$tlist[$value["title"]]["uv"] = 0;
				$tlist[$value["title"]]["hits"] = 0;
				$tlist[$value["title"]]["title"] = $value["title"];
			}
			if($rslist)
			{
				foreach($rslist AS $key=>$value)
				{
					$tlist[$value["title"]]["ip"] = intval($value["ip"]);
					$tlist[$value["title"]]["uv"] = intval($value["uv"]);
					$tlist[$value["title"]]["hits"] = intval($value["hits"]);
				}
			}
			$this->assign("tlist",$tlist);
		}
		$this->assign("date",$date);
		$this->assign("type",$type);
		$this->assign("id",$id);
		$this->view("ad_stat");
		//$rs = $this->model('ad')->get_one($id);
	}
}
?>