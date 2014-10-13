<?php
/***********************************************************
	Filename: {phpok}/form/editor_admin.php
	Note	: 可视化编辑器配置
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class editor_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	//格式化表单
	function format($rs)
	{
		if(!$rs["width"]) $rs["width"] = "500";
		//格式化样式
		$style = array();
		if($rs['form_style'])
		{
			$list = explode(";",$rs['form_style']);
			foreach($list AS $key=>$value)
			{
				$tmp = explode(":",$value);
				if($tmp[0] && $tmp[1] && trim($tmp[1]))
				{
					$style[strtolower($tmp[0])] = trim($tmp[1]);
				}
			}
		}
		if($rs['width']) $style["width"] = $rs['width'].'px';
		if($rs['height']) $style["height"] = $rs['height'].'px';
		$rs['form_style'] = '';
		foreach($style AS $key=>$value)
		{
			if($rs['form_style']) $rs['form_style'] .= ';';
			$rs['form_style'] .= $key.':'.$value;
		}
		$GLOBALS['app']->assign("form_rs",$rs);
		$GLOBALS['app']->assign('form_id',$rs['id']);
		//附件保存目录

		$save_path = $GLOBALS['app']->model('res')->cate_all();
		if($save_path)
		{
			$save_path_array = array();
			foreach($save_path AS $key=>$value)
			{
				$save_path_array[] = $value['title'];
			}
			$save_path = "['". implode("','",$save_path_array) ."']";
		}
		else
		{
			$save_path = '["默认分类"]';
		}
		$GLOBALS['app']->assign("save_path",$save_path);
		$file = $GLOBALS['app']->dir_phpok.'form/html/ueditor_from_www.html';
		return $GLOBALS['app']->fetch($file,'abs-file');
	}
}