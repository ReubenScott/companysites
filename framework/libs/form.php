<?php
/***********************************************************
	Filename:  libs/form.php
	Note	: 表单选项管理器
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年12月2日
***********************************************************/
if(!defined("APP_SET")){exit("<h1>Access Denied</h1>");}
class form_lib
{
	//表单对象
	public $cls;

	//构造函数
	function __construct()
	{
		//自动装载表单信息
		$flist = $GLOBALS['app']->model('form')->form_all();
		if($flist)
		{
			foreach($flist AS $key=>$value)
			{
				$file = $GLOBALS['app']->dir_phpok.'form/'.$key.'_'.$GLOBALS['app']->app_id.'.php';
				if(!is_file($file))
				{
					$file = $GLOBALS['app']->dir_phpok.'form/'.$key.'_admin.php';
				}
				//如果文件存在
				if(is_file($file))
				{
					$cls_name = $key."_form";
					include_once($file);
					$this->cls[$key] = new $cls_name();
				}
			}
		}
	}

	//格式化表单信息
	function format($rs)
	{
		//对象不存在时，返回否
		if(!$this->cls[$rs['form_type']])
		{
			return false;
		}
		$info = $this->cls[$rs['form_type']]->format($rs);
		$rs['html'] = $info;
		return $rs;
	}


	//弹出窗口，用于创建字段
	function open_form_setting($saveurl)
	{
		if(!$saveurl) return false;
		$GLOBALS['app']->assign('saveUrl',$saveurl);
		//读取格式化类型
		$field_list = $GLOBALS['app']->model('form')->field_all();
		$form_list = $GLOBALS['app']->model('form')->form_all();
		$format_list = $GLOBALS['app']->model('form')->format_all();
		$GLOBALS['app']->assign('fields',$field_list);
		$GLOBALS['app']->assign('formats',$format_list);
		$GLOBALS['app']->assign('forms',$form_list);
		//创建字段
		$GLOBALS['app']->view("field_create");
	}
}
?>