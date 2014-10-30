<?php
/***********************************************************
	Filename:  model/data.php
	Note	: 前台用于调用的数据
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年11月9日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class data_model extends base_model
{
	//缓存数据
	private $cdata;
	
	function __construct()
	{
		parent::model();
	}

	//取得文章列表
	public function arclist($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		//取得项目信息
		$project_ext = $rs['in_project'] == 2 ? true : false;
		$project_rs = $this->_project($rs['pid'],$project_ext);
		if(!$project_rs) return false;
		if(!$project_rs['url']) $project_rs['url'] = listurl($project_rs['identifier']);
		//判断是否有绑定模块，没有绑定模块，跳过
		if(!$project_rs['module']) return false;
		//取得扩展字段信息
		$flist = $this->module_field($project_rs['module']);
		$field = 'l.*';
		$nlist = "";
		if($flist)
		{
			foreach($flist AS $key=>$value)
			{
				if($value['field_type'] != 'longtext' && $value['field_type'] != 'longblob')
				{
					$field .= ',ext.'.$key;
					$nlist[$key] = $value;
				}
				else
				{
					if($rs['in_text'] && ($value['field_type'] == 'longtext' || $value['field_type'] == 'longblob'))
					{
						$field .= ",ext.".$key;
						$nlist[$key] = $value;
					}
				}
			}
		}
		$sql = "SELECT ".$field." FROM ".$this->db->prefix."list l ";
		$sql.= "JOIN ".$this->db->prefix."list_".$project_rs['module']." ext ";
		$sql.= "ON(l.id=ext.id AND l.site_id=ext.site_id AND l.project_id=ext.project_id) ";
		$sql.= "WHERE l.project_id=".$rs['pid']." AND l.site_id=".$this->site['id']." ";
		$sql.= " AND l.hidden=0 ";
		if(!$rs['not_status'])
		{
			if($_SESSION['user_id'])
			{
				$sql .= " AND (l.status=1 OR (l.user_id=".$_SESSION['user_id']." AND l.status=0)) ";
			}
			else
			{
				$sql .= " AND l.status=1 ";
			}
		}
		//$sql.= "AND l.status=1 AND l.hidden=0 ";
		//不包含主题
		if($rs['notin'])
		{
			$sql .= "AND l.id NOT IN(".$rs['notin'].") ";
		}
		if(!$rs['in_sub'])
		{
			$sql .= "AND l.parent_id=0 ";
		}
		if($rs['cate'])
		{
			$tmp = $this->_id($rs['cate'],$this->site['id']);
			if($tmp['type'] == 'cate') $rs['cateid'] = $tmp['id'];
		}
		if($rs['cateid'])
		{
			//读取这个分类下的所有子分类信息
			$array = array($rs['cateid']);
			$this->_cate_id($array,$rs['cateid']);
			$sql .= "AND l.cate_id IN(".implode(",",$array).") ";
		}
		//绑定某个会员
		if($rs['user_id'])
		{
			$sql.= "AND l.user_id IN(".$rs['user_id'].") ";
		}
		if($rs['attr'])
		{
			$sql.= "AND l.attr LIKE '%".$rs['attr']."%' ";
		}

		if($rs['idin'])
		{
			$sql .= " AND l.id IN(".$rs['idin'].") ";
		}
		//绑定Tag
		if($rs['tag'])
		{
			$list = explode(",",$rs['tag']);
			$tid_sql = "SELECT DISTINCT l.tid FROM qinggan_tag_list l JOIN qinggan_tag t ON(l.id=t.id) ";
			$tag_condition = '';
			foreach($list AS $key=>$value)
			{
				$tag_condition [] = " t.title='".$value."'";
			}
			$tid_sql.= "WHERE (".implode(" OR ",$condition).") ";
			$condition .= " AND l.id IN(".$tid_sql.")";
		}
		//关键字
		if($rs['keywords'])
		{
			$list = explode(",",$rs['keywords']);
			$condition = '';
			foreach($list AS $key=>$value)
			{
				$condition [] = " l.seo_title LIKE '%".$value."%'";
				$condition [] = " l.seo_keywords LIKE '%".$value."%'";
				$condition [] = " l.seo_desc LIKE '%".$value."%'";
				$condition [] = " l.title LIKE '%".$value."%'";
				$condition [] = " l.tag LIKE '%".$value."%'";
			}
			$sql.= "AND (".implode(" OR ",$condition).") ";
		}
		//必须的字段
		if($rs['fields_need'])
		{
			$list = explode(",",$rs['fields_need']);
			foreach($list AS $key=>$value)
			{
				$sql .= " AND ".$value." != '' AND ".$value." != '0' AND ".$value." is NOT NULL ";
			}
		}
		//自定义SQL扩展
		if($rs['sqlext'])
		{
			$sql.= " AND ".$rs['sqlext'];
		}
		//更深一层的扩展
		if($rs['ext'] && is_array($rs['ext']))
		{
			foreach($rs['ext'] AS $key=>$value)
			{
				$sql .= " AND ext.".$key."='".$value."'";
			}
		}
		$orderby = $rs['orderby'] ? $rs['orderby'] : $project_rs['orderby'];
		if(!$orderby) $orderby = 'l.sort DESC,l.dateline DESC,l.id DESC ';
		$sql.= 'ORDER BY '.$orderby.' ';
		//非列表模式，强制只读取一条
		if(!$rs['is_list']) $rs['psize'] = 1;
		//
		if($rs['psize'])
		{
			$sql .= "LIMIT ".intval($rs['offset']).','.$rs['psize'];
		}
		$rslist = $this->db->get_all($sql);
		//当数据获取为空时，如果包含项目信息，将返回项目信息并返回空列表
		if(!$rslist && !$rs['in_project'] && !$rs['in_cate'])
		{
			return false;
		}
		//如果内容存在
		if($rslist)
		{
			//更新附件信息，分类信息，会员信息，主题信息
			$res = $cate = $user = $tid = "";
			foreach($rslist AS $key=>$value)
			{
				//绑定分类
				if($value['cate_id']) $cate[] = $value['cate_id'];
				//绑定会员
				if($value['user_id']) $user[] = $value['user_id'];
				//格式化扩展字段
				if($nlist && is_array($nlist))
				{
					foreach($nlist AS $k=>$v)
					{
						//绑定上传的附件
						if($value[$k] && $v['form_type'] == 'upload')
						{
							$tmp = explode(",",$value[$k]);
							foreach($tmp AS $kk=>$vv)
							{
								$vv = intval($vv);
								if($vv) $res[] = $vv;
							}
						}
						elseif($value[$k] && $v['form_type'] == 'title')
						{
							$tmp = explode(",",$value[$k]);
							foreach($tmp AS $kk=>$vv)
							{
								$vv = intval($vv);
								if($vv) $tid[] = $vv;
							}
						}
						elseif($value[$k] && $v['form_type'] == 'url')
						{
							$tmp = unserialize($value[$k]);
							$link = $this->site['url_type'] == 'rewrite' ? $tmp['rewrite'] : $tmp['default'];
							if(!$link) $link = $tmp['default'];
							$value[$k] = $link;
							if(!$value['url'] && $k != 'url') $value['url'] = $link;
						}
					}
				}
				//绑定链接
				if(!$value['url'])
				{
					$value['url'] = msgurl(($value['identifier'] ? $value['identifier'] : $value['id']));
				}
				$rslist[$key] = $value;
			}
			if($res) $res = $this->_res_info($res);
			if($cate) $cate = $this->_cate_info($cate);
			if($user) $user = $this->_user_info($user);
			if($tid) $tid = $this->_tid_info($tid);
			foreach($rslist AS $key=>$value)
			{
				$rslist[$key] = $this->_format($value,$nlist,$res,$cate,$user,$tid);
			}
			//如果包含子主题，再执行一次格式化
			if($rs['in_sub'])
			{
				$list = '';
				foreach($rslist AS $key=>$value)
				{
					if(!$value['parent_id'])
					{
						$value['sonlist'] = '';
						foreach($rslist AS $k=>$v)
						{
							if($v['parent_id'] == $value['id'])
							{
								$value['sonlist'][] = $v;
							}
						}
						$list[] = $value;
					}
				}
				$rslist = $list;
			}
		}
		if(!$rs['in_cate'] && !$rs['in_project'] && $rslist)
		{
			if(!$rs['is_list']) return current($rslist);
			return $rslist;
		}
		//如果包含项目
		$array = array();
		if($rs['in_project'])
		{
			$array['project'] = $project_rs;
		}
		if($rs['in_cate'])
		{
			$cate_ext = $rs['in_cate'] == 2 ? true : false;
			$array['cate'] = $this->cate(array("pid"=>$rs['pid'],"cateid"=>$rs['cateid'],"cate_ext"=>$cate_ext));
		}
		if(!$rs['is_list'])
		{
			$array['rs'] = current($rslist);
		}
		else
		{
			$array['rslist'] = $rslist;
		}
		return $array;		
	}

	//取得文章总数
	public function total($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		//取得项目信息
		$project_rs = $this->_project($rs['pid'],false);
		//判断是否有绑定模块，没有绑定模块，跳过
		if(!$project_rs['module']) return false;
		$sql = "SELECT count(l.id) FROM ".$this->db->prefix."list l ";
		$sql.= "JOIN ".$this->db->prefix."list_".$project_rs['module']." ext ON(l.id=ext.id AND l.site_id=ext.site_id) ";
		$sql.= "WHERE l.project_id=".$rs['pid']." AND l.site_id=".$this->site['id']." ";
		$sql.= " AND l.hidden=0 ";
		if(!$rs['not_status']) $sql .= " AND l.status=1 ";
		//不包含主题
		if($rs['notin'])
		{
			$sql .= "AND l.id NOT IN(".$rs['notin'].") ";
		}
		if(!$rs['in_sub'])
		{
			$sql .= "AND l.parent_id=0 ";
		}
		if($rs['cate'])
		{
			$tmp = $this->_id($rs['cate'],$this->site['id']);
			if($tmp['type'] == 'cate') $rs['cateid'] = $tmp['id'];
		}
		if($rs['cateid'])
		{
			//读取这个分类下的所有子分类信息
			$array = array($rs['cateid']);
			$this->_cate_id($array,$rs['cateid']);
			$sql .= "AND l.cate_id IN(".implode(",",$array).") ";
		}
		//绑定某个会员
		if($rs['user_id'])
		{
			$sql.= "AND l.user_id IN(".$rs['user_id'].") ";
		}
		if($rs['attr'])
		{
			$sql.= "AND l.attr LIKE '%".$rs['attr']."%' ";
		}
		//绑定Tag
		if($rs['tag'])
		{
			$list = explode(",",$rs['tag']);
			$tid_sql = "SELECT DISTINCT l.tid FROM qinggan_tag_list l JOIN qinggan_tag t ON(l.id=t.id) ";
			$tag_condition = '';
			foreach($list AS $key=>$value)
			{
				$tag_condition [] = " t.title='".$value."'";
			}
			$tid_sql.= "WHERE (".implode(" OR ",$condition).") ";
			$condition .= " AND l.id IN(".$tid_sql.")";
		}
		//关键字
		if($rs['keywords'])
		{
			$list = explode(",",$rs['keywords']);
			$condition = '';
			foreach($list AS $key=>$value)
			{
				$condition [] = " l.seo_title LIKE '%".$value."%'";
				$condition [] = " l.seo_keywords LIKE '%".$value."%'";
				$condition [] = " l.seo_desc LIKE '%".$value."%'";
				$condition [] = " l.title LIKE '%".$value."%'";
				$condition [] = " l.tag LIKE '%".$value."%'";
			}
			$sql.= "AND (".implode(" OR ",$condition).") ";
		}
		//必须的字段
		if($rs['fields_need'])
		{
			$list = explode(",",$rs['fields_need']);
			foreach($list AS $key=>$value)
			{
				$sql.= " AND ".$value." != '' AND ".$value." is NOT NULL AND ".$value." != 0 ";
			}
		}
		//自定义SQL扩展
		if($rs['sqlext'])
		{
			$sql.= " AND ".$rs['sqlext'];
		}
		return $this->db->count($sql);
	}
	//取得单篇文章信息
	public function arc($rs)
	{
		$tmpid = $rs['id'] ? $rs['id'] : ($rs['phpok'] ? $rs['phpok'] : $rs['title_id']);
		if(!$tmpid) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."list WHERE id='".$tmpid."' OR identifier='".$tmpid."'";
		$info_rs = $this->db->get_one($sql);
		if(!$info_rs) return false;
		$rs['id'] = $info_rs['id'];
		//当没有绑定模块时，直接返回结果集
		if(!$info_rs['module_id']) return $info_rs;
		$sql = "SELECT * FROM ".$this->db->prefix."list_".$info_rs['module_id']." WHERE id='".$rs['id']."'";
		$ext_rs = $this->db->get_one($sql);
		$flist = $this->module_field($info_rs['module_id']);
		if($flist && $ext_rs)
		{
			$res = $tid = "";
			foreach($flist AS $k=>$v)
			{
				//内容分页格式化
				if($ext_rs[$k] && $v['form_type'] == 'editor')
				{
					$tmp = $this->info_page($ext_rs[$k],$rs['pageid']);
					if($tmp && is_array($tmp))
					{
						$info_rs[$k.'_pagelist'] = $tmp['pagelist'];
						$ext_rs[$k] = $tmp['content'];
					}
					else
					{
						$ext_rs[$k] = $tmp;
					}
				}
				else
				{
					$ext_rs[$k] = str_replace('[:page:]','',$ext_rs[$k]);
				}
				//绑定上传的附件
				if($ext_rs[$k] && $v['form_type'] == 'upload')
				{
					$tmp = explode(",",$ext_rs[$k]);
					foreach($tmp AS $kk=>$vv)
					{
						$vv = intval($vv);
						if($vv) $res[] = $vv;
					}
				}
				elseif($ext_rs[$k] && $v['form_type'] == 'title')
				{
					$tmp = explode(",",$value[$k]);
					foreach($tmp AS $kk=>$vv)
					{
						$vv = intval($vv);
						if($vv) $tid[] = $vv;
					}
				}
				//将内容UBB执行格式化
				$ext_rs[$k] = $this->ubb($ext_rs[$k],false);
			}
			if($res) $res = $this->_res_info($res);
			if($tid) $tid = $this->_tid_info($tid);
			$ext_rs = $this->_format($ext_rs,$flist,$res,"","",$tid);
		}
		if($ext_rs) $info_rs = array_merge($ext_rs,$info_rs);
		if(!$info_rs['url']) $info_rs['url'] = $info_rs['identifier'] ? listurl($info_rs['identifier']) : listurl($info_rs['id']);
		return $info_rs;
	}

	//内容分类
	function info_page($content,$pageid=0)
	{
		if(!$content) return false;
		if(!$pageid) $pageid = 1;
		$lst = explode('[:page:]',$content);
		$t = $pageid-1;
		if($lst[$t])
		{
			$total = count($lst);
			if($total>1)
			{
				$array = array();
				for($i=0;$i<$total;$i++)
				{
					$array[$i] = $i+1;
				}
			}
			return array('pagelist'=>$array,'content'=>$lst[$t]);
		}
		return $lst[0];
	}

	//取得当前分类信息
	public function cate($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		//取得项目信息
		$project_rs = $this->_project($rs['pid'],false);
		if(!$project_rs['cate']) return false;
		if($rs['cate'])
		{
			$tmp = $this->_id($rs['cate'],$this->site['id']);
			if($tmp['type'] == 'cate') $rs['cateid'] = $tmp['id'];
		}
		if(!$rs['cateid']) $rs['cateid'] = $project_rs['cate'];
		$sql = "SELECT * FROM ".$this->db->prefix."cate WHERE id='".$rs['cateid']."' AND status=1";
		$cate_rs = $this->db->get_one($sql);
		if(!$cate_rs) return false;
		if($rs['cate_ext'])
		{
			$ext = $this->ext_all('cate-'.$cate_rs['id']);
			if($ext) $cate_rs = array_merge($ext,$cate_rs);
		}
		if(!$cate_rs['url'])
		{
			$cate_rs['url'] = listurl($project_rs['identifier'],$cate_rs['identifier']);
		}
		return $cate_rs;
	}

	//取得分类，不带项目
	function cate_id($rs)
	{
		$id = $rs['id'] ? $rs['id'] : $rs['cateid'];
		if(!$id && !$rs['phpok']) return false;
		if(!$id)
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'cate') $id = $tmp['id'];
		}
		if(!$id) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."cate WHERE id='".$id."' AND status=1";
		$cate_rs = $this->db->get_one($sql);
		if(!$cate_rs) return false;
		if($rs['cate_ext'])
		{
			$ext = $this->ext_all('cate-'.$cate_rs['id']);
			if($ext) $cate_rs = array_merge($ext,$cate_rs);
		}
		return $cate_rs;
	}

	//取得当前分类下的子类
	public function subcate($rs)
	{
		if(!$rs['cateid'] && !$rs['phpok']) return false;
		if(!$rs['cateid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'cate') return false;
			$rs['cateid'] = $tmp['id'];
		}
		$list = array();
		$this->cate_sublist($list,$rs['cateid'],$rs['cate_ext'],$rs['project']);
		return $list;
	}
	
	//取得分类列表
	public function catelist($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		//取得项目信息
		$project_rs = $this->_project($rs['pid'],false);
		//判断是否有绑定分类
		if(!$project_rs['cate']) return false;
		if($rs['cate'])
		{
			$tmp = $this->_id($rs['cate'],$this->site['id']);
			if($tmp['type'] == 'cate') $rs['cateid'] = $tmp['id'];
		}
		$list = array();
		$this->cate_sublist($list,$project_rs['cate'],$rs['catelist_ext'],$project_rs['identifier']);
		if(!$list || !is_array($list) || count($list)<1) return false;
		//格式化分类
		$array = array('all'=>$list);
		//
		$cateid = $rs['cateid'] ? $rs['cateid'] : $project_rs['cate'];
		//读子分类
		foreach($list AS $key=>$value)
		{
			if($value['parent_id'] == $cateid)
			{
				$array['sublist'][$value['id']] = $value;
			}
		}
		//
		if($rs['cateid'] && $rs['cateid'] != $project_rs['cate'])
		{
			$array['cate'] = $list[$rs['cateid']];
			if($array['cate']['parent_id'] != $project_rs['cate'] && $list[$array['cate']['parent_id']])
			{
				$array['parent'] = $list[$array['cate']['parent_id']];
				//取得同级分类
				foreach($list AS $key=>$value)
				{
					if($value['parent_id'] == $array['parent']['id'])
					{
						$array['plist'][$value['id']] = $value;
					}
				}
			}
		}
		return $array;
	}

	//取得项目信息
	public function project($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		$rs = $this->_project($rs['pid'],$rs['project_ext']);
		if(!$rs) return false;
		//绑定链接
		if(!$rs['url']) $rs['url'] = listurl($rs['identifier']);
		return $rs;
	}

	//取得父级项目信息
	public function _project_parent($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		$project_rs = $this->_project($rs['pid'],false);
		if(!$project_rs || !$project_rs['parent_id']) return false;
		$rs = $this->_project($project_rs['parent_id'],$rs['parent_ext']);
		if(!$rs) return false;
		//绑定链接
		if(!$rs['url']) $rs['url'] = listurl($rs['identifier']);
		return $rs;
	}
	
	//取得子项目信息
	public function sublist($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."project WHERE parent_id=".$rs['pid']." AND status=1 ";
		$sql.= "ORDER BY taxis ASC,id DESC";
		$rslist = $this->db->get_all($sql);
		if(!$rslist) return false;
		if($rs['sublist_ext'])
		{
			foreach($rslist AS $key=>$value)
			{
				$ext_rs = $this->ext_all('project-'.$value['id']);
				if($ext_rs) $value = array_merge($ext_rs,$value);
				$rslist[$key] = $value;
			}
		}
		foreach($rslist AS $key=>$value)
		{
			if(!$value['url']) $value['url'] = listurl($value['identifier']);
			$rslist[$key] = $value;
 		}
 		return $rslist;

	}
	//读取当前分类的子分类
	private function cate_sublist(&$list,$parent_id=0,$is_ext=false,$identifier='')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."cate WHERE parent_id='".$parent_id."' AND status=1 ORDER BY taxis ASC,id DESC";
		$rslist = $this->db->get_all($sql);
		if($rslist)
		{
			foreach($rslist AS $key=>$value)
			{
				//扩展项信息
				if($is_ext)
				{
					$ext = $this->ext_all('cate-'.$value['id']);
					if($ext) $value = array_merge($ext,$value);
				}
				if($identifier)
				{
					$value['url'] = listurl($identifier,$value['identifier']);
				}
				$list[$value['id']] = $value;
				$this->cate_sublist($list,$value['id'],$is_ext,$identifier);
			}
		}
	}

	//取得自定义字段信息
	public function fields($rs)
	{
		if(!$rs['pid'] && !$rs['phpok']) return false;
		if(!$rs['pid'])
		{
			$tmp = $this->_id($rs['phpok'],$this->site['id']);
			if(!$tmp || $tmp['type'] != 'project') return false;
			$rs['pid'] = $tmp['id'];
		}
		if(!$rs['pid']) return false;
		$project_rs = $this->_project($rs['pid'],false);
		if(!$project_rs || !$project_rs['module']) return false;
		//自定义字段
		$array = array();
		$flist = $this->module_field($project_rs['module']);
		//如果存在扩展字段，对扩展字段进行处理，标前识加前缀等
		if($flist)
		{
			foreach($flist AS $key=>$value)
			{
				if($rs['prefix'])
				{
					$value["identifier"] = $rs['prefix'].$value['identifier'];
				}
				if($rs['info'][$value['identifier']])
				{
					$value['content'] = $rs['info'][$value['identifier']];
				}
				$flist[$key] = $value;
			}
			//如果包含主题
			if($rs['in_title'])
			{
				$tmp_id = $rs['prefix'].'title';
				$array['title'] = array('id'=>0,"module_id"=>$project_rs['module'],'title'=>($project_rs['alias_title'] ? $project_rs['alias_title'] : '主题'),'identifier'=>$tmp_id,'field_type'=>'varchar','form_type'=>'text','format'=>'safe','taxis'=>1,'width'=>'300','content'=>$rs['info']['title']);
				$array = array_merge($array,$flist);
			}
			else
			{
				$array = $flist;
			}
		}
		//判断是否格式化
		if($rs['fields_format'])
		{
			foreach($array AS $key=>$value)
			{
				if($value['ext'])
				{
					$ext = unserialize($value['ext']);
					unset($value['ext']);
					if($ext) $value = array_merge($ext,$value);
				}
				$array[$key] = $GLOBALS['app']->lib('form')->format($value);
			}
		}
		return $array;
	}
	
	//取得项目信息
	public function _project($id,$ext=false)
	{
		if($this->cdata['project'][$id])
		{
			$rs = $this->cdata['project'][$id];
		}
		else
		{
			$sql = "SELECT * FROM ".$this->db->prefix."project WHERE id=".$id;
			$rs = $this->db->get_one($sql);
			$this->cdata['project'][$id] = $rs;
		}
		if(!$rs) return false;
		if($ext)
		{
			$ext = $this->ext_all('project-'.$id);
			if($ext) $rs = array_merge($ext,$rs);
		}
		return $rs;
	}

	function module_field($mid)
	{
		$cdata = $this->cdata["module"][$mid];
		if($cdata)
		{
			return $cdata;
		}
		else
		{
			$sql = "SELECT * FROM ".$this->db->prefix."module_fields WHERE module_id='".$mid."' ORDER BY taxis ASC,id DESC";
			$cdata = $this->db->get_all($sql,'identifier');
			if($cdata) $this->cdata["module"][$mid] = $cdata;
			return $cdata;
		}
	}

	public function id($identifier,$site_id=0)
	{
		return $this->_id($identifier,$site_id);
	}

	//通过标识串获取内容信息
	private function _id($identifier,$site_id=0)
	{
		$site_id = $site_id ? '0,'.$site_id : '0';
		//在项目中检测
		$sql = "SELECT id FROM ".$this->db->prefix."project WHERE identifier='".$identifier."' AND site_id IN(".$site_id.")";
		$check_rs = $this->db->get_one($sql);
		if($check_rs) return array("id"=>$check_rs['id'],'type'=>'project');
		//在分类中检测
		$sql = "SELECT id FROM ".$this->db->prefix."cate WHERE identifier='".$identifier."' AND site_id IN(".$site_id.")";
		$check_rs = $this->db->get_one($sql);
		if($check_rs) return array("id"=>$check_rs['id'],'type'=>'cate');
		//在内容里检测
		$sql = "SELECT id FROM ".$this->db->prefix."list WHERE identifier='".$identifier."' AND site_id IN(".$site_id.")";
		$check_rs = $this->db->get_one($sql);
		if($check_rs) return array("id"=>$check_rs['id'],'type'=>'content');
		return false;
	}

	//获取项目，分类的扩展信息
	public function ext_all($id)
	{
		$sql = "SELECT ext.ext,ext.identifier,ext.form_type,c.content FROM ".$this->db->prefix."ext ext ";
		$sql.= "LEFT JOIN ".$this->db->prefix."extc c ON(ext.id=c.id) ";
		$sql.= "WHERE ext.module='".$id."'";
		$rslist = $this->db->get_all($sql,'identifier');
		if(!$rslist) return false;
		$res = '';
		foreach($rslist AS $key=>$value)
		{
			//当内容表单为网址时
			if($value['form_type'] == 'url' && $value['content'])
			{
				$value['content'] = unserialize($value['content']);
				$url = $this->site['url_type'] == 'rewrite' ? $value['content']['rewrite'] : $value['content']['default'];
				if(!$url) $url = $value['content']['default'];
				$value['content'] = $url;
				//绑定扩展自定义url
				if(!$rslist['url']) $rslist['url'] = array('form_type'=>'text','content'=>$url);
			}
			elseif($value['form_type'] == 'upload' && $value['content'])
			{
				$tmp = explode(',',$value['content']);
				foreach($tmp AS $k=>$v)
				{
					$v = intval($v);
					if($v) $res[] = $v;
				}
			}
			$rslist[$key] = $value;
		}
		//格式化内容数据，并合并附件数据
		$flist = "";
		foreach($rslist AS $key=>$value)
		{
			$flist[$key] = $value;
			$rslist[$key] = $value['content'];
		}
		if($res && is_array($res)) $res = $this->_res_info($res);
		$rslist = $this->_format($rslist,$flist,$res);
		return $rslist;
	}

	//读取分类下的子分类id
	private function _cate_id(&$array,$parent_id=0)
	{
		$sql = "SELECT id FROM ".$this->db->prefix."cate WHERE parent_id='".$parent_id."'";
		$rslist = $this->db->get_all($sql);
		if($rslist)
		{
			foreach($rslist AS $key=>$value)
			{
				$array[] = $value['id'];
				$this->_cate_id($array,$value['id']);
			}
		}
	}

	public function res_info($id)
	{
		return $this->_res_info($id);
	}

	//读取附件信息
	private function _res_info($id)
	{
		if(!$id) return false;
		if(is_string($id)) $id = array($id);
		$id = array_unique($id);
		$id = implode(',',$id);
		$sql = "SELECT id,name,filename,addtime,title,note,download FROM ".$this->db->prefix."res WHERE id IN(".$id.")";
		$reslist = $this->db->get_all($sql,'id');
		if(!$reslist) return false;
		$sql = "SELECT ext.res_id,ext.filename,gd.identifier FROM ".$this->db->prefix."res_ext ext ";
		$sql.= "JOIN ".$this->db->prefix."gd gd ON(ext.gd_id=gd.id) ";
		$sql.= "WHERE ext.res_id IN(".$id.")";
		$extlist = $this->db->get_all($sql);
		if($extlist)
		{
			foreach($extlist AS $key=>$value)
			{
				$reslist[$value["res_id"]]["gd"][$value['identifier']] = $value['filename'];
			}
		}
		return $reslist;
	}

	//格式化单列信息
	private function _format($rs,$flist="",$reslist="",$catelist="",$userlist="",$tlist="")
	{
		if(!$rs || !is_array($rs)) return false;
		if($flist)
		{
			foreach($flist AS $key=>$value)
			{
				$ext = array();
				if($value['ext'])
				{
					$ext = unserialize($value['ext']);
				}
				//格式化附件信息
				if($value['form_type'] == "upload" && $rs[$value['identifier']] && $reslist && is_array($reslist))
				{
					if($ext['is_multiple'])
					{
						$res = "";
						$tmp = explode(',',$rs[$value['identifier']]);
						foreach($tmp AS $k=>$v)
						{
							$v = intval($v);
							if($v && $reslist[$v]) $res[$v] = $reslist[$v];
						}
						$rs[$value['identifier']] = $res;
					}
					else
					{
						$rs[$value['identifier']] = $reslist[$rs[$value['identifier']]];
					}
				}
			}
		}
		//格式化分类信息
		if($rs['cate_id'] && $catelist && $catelist[$rs['cate_id']]) $rs['cate_id'] = $catelist[$rs['cate_id']];
		//格式化会员信息
		if($rs['user_id'] && $userlist && $userlist[$rs['user_id']]) $rs['user_id'] = $userlist[$rs['user_id']];
		return $rs;
	}

	//读取分类基础信息
	private function _cate_info($id)
	{
		if(!$id) return false;
		if(is_string($id)) $id = array($id);
		$id = array_unique($id);
		$id = implode(',',$id);
		$sql = "SELECT * FROM ".$this->db->prefix."cate WHERE id IN(".$id.")";
		return $this->db->get_all($sql,"id");
	}

	//读取会员基础信息
	private function _user_info($id)
	{
		if(!$id) return false;
		if(is_string($id)) $id = array($id);
		$id = array_unique($id);
		$id = implode(',',$id);
		$sql = "SELECT * FROM ".$this->db->prefix."user WHERE id IN(".$id.")";
		return $this->db->get_all($sql,"id");
	}

	//读取内容基础信息
	private function _title_info($id)
	{
		if(!$id) return false;
		if(is_string($id)) $id = array($id);
		$id = array_unique($id);
		$id = implode(',',$id);
		$sql = "SELECT * FROM ".$this->db->prefix."list WHERE id IN(".$id.")";
		return $this->db->get_all($sql,"id");
	}
}