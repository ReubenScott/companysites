<?php
/***********************************************************
	Filename: {phpok}/form/html_editor_admin.php
	Note	: 可视化编辑器
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013-03-12 17:53
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class html_editor_form
{
	function __construct()
	{
		global $app;
		$this->app = $app;
	}

	function config()
	{
		$html = $GLOBALS['app']->dir_phpok."form/html/html_editor_admin.html";
		$GLOBALS['app']->view($html,"abs-file");
	}

	//编辑器内容信息
	function format($rs)
	{
		if(!$rs["width"] || $rs["width"] < 450) $rs["width"] = "450";
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
		if($rs['width']) $style["width"] = $rs['width'];
		if($rs['height']) $style["height"] = $rs['height'];
		$rs['form_style'] = '';
		foreach($style AS $key=>$value)
		{
			if($rs['form_style']) $rs['form_style'] .= ';';
			$rs['form_style'] .= $key.':'.$value;
		}
		//
		echo '<pre>----';
		print_r($rs);
		return '2222';
		$GLOBALS['app']->assign("form_rs",$rs);
		$GLOBALS['app']->assign('editor_id',$rs['id']);
		//$file = $this->app->dir_phpok."form/html/editor_from_admin.html";
		$file = $GLOBALS['app']->dir_phpok.'form/html/ueditor_from_admin.html';
		return $GLOBALS['app']->fetch($file,'abs-file');
	}
}