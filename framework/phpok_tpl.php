<?php
/***********************************************************
	Filename: phpok/phpok_tpl.php
	Note	: PHPOK模板引挈，简单实用
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-19 10:27
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
class phpok_tpl
{
	# 模板编译后的前缀ID，后面跟下划线
	var $tpl_id = 1;

	# 模板目录，后面以/结尾
	var $dir_tpl = "tpl/";

	# 编译后的存储目录
	var $dir_cache = "data/cache/";

	# PHP路径
	var $dir_php = "./";
	var $dir_root = "./";

	# 路径调整
	var $path_change = "";

	# 自动检测刷新模板
	var $refresh_auto = true;

	# 强制刷新模板
	var $refresh = false;

	# 模板后缀
	var $tpl_ext = "html";

	# HTML头部信息，防止被下载
	var $html_head = '<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?>';

	# tpl_value，附值
	var $tpl_value;

	//构造函数
	function __construct($config=array())
	{
		if($config["id"]) $this->tpl_id = $config["id"];
		if($config["dir_tpl"]) $this->dir_tpl = $config["dir_tpl"];
		if($config["dir_cache"]) $this->dir_cache = $config["dir_cache"];
		if($config["dir_php"]) $this->dir_php = $config["dir_php"];
		if($config["dir_root"]) $this->dir_root = $config["dir_root"];
		if($config["path_change"]) $this->path_change = $config["path_change"];
		$this->refresh_auto = $config["refresh_auto"] ? true : false;
		$this->refresh = $config["refresh"] ? true : false;
		$this->tpl_ext = $config["tpl_ext"] ? $config["tpl_ext"] : "html";
		if($this->dir_tpl && substr($this->dir_tpl,-1) != "/") $this->dir_tpl .= "/";
		if($this->dir_cache && substr($this->dir_cache,-1) != "/") $this->dir_cache .= "/";
	}

	//附值变量信息
	//var 变量名
	//val 变量值
	function assign($var,$val="")
	{
		if(!$var || is_array($var)) return false;
		$this->tpl_value[$var] = $val;
	}

	//注销变量
	function unassign($var)
	{
		if(!$var) return false;
		if($this->tpl_value[$var])
		{
			$this->tpl_value[$var] = "";
			return true;
		}
		return false;
	}

	//注销全部变量
	function unassign_all()
	{
		$this->tpl_value = "";
		return true;
	}

	function path_change($val="")
	{
		$this->path_change = $val;
	}

	//输出编译后的模板信息
	//tpl是指模板文件，支持子目录
	# type 类型，可选值为：file,file-ext,content,msg,abs-file
	#	file，相对路径，不带后缀
	#	file-ext，相对路径，带后缀
	#	content/msg，纯模板内容
	#	abs-file，带后缀的绝对路径
	function output($tpl,$type="file",$path_format=true)
	{
		if(!$tpl) $this->error("模板信息为空！");
		$comp_id = $this->comp_id($tpl,$type);
		if(!$comp_id) $this->error("没有指定模板源！");
		# 编译模板信息
		$this->compiling($comp_id,$tpl,$type,$path_format);
		$this->assign("session",$_SESSION);
		$this->assign("get",$_GET);
		$this->assign("post",$_POST);
		$this->assign("cookie",$_COOKIE);
		if(!$this->tpl_value || !is_array($this->tpl_value))
		{
			$this->tpl_value = array();
		}
		$varlist = (is_array($GLOBALS))?array_merge($GLOBALS,$this->tpl_value):$this->tpl_value;
		extract($varlist);
		include($this->dir_cache.$comp_id);
	}

	//取得内容，不直接输出，参数output
	function fetch($tpl,$type="file",$path_format=true)
	{
		ob_start();
		$this->output($tpl,$type,$path_format);
		$msg = ob_get_contents();
		ob_end_clean();
		return $msg;
	}

	# 取得编译后的文件ID
	function comp_id($tpl,$type="file")
	{
		$string = $this->tpl_id."_";
		if($type == "file" || $type == "file-ext")
		{
			$tpl = strtolower($tpl);
			$tpl = str_replace("/","_folder_",$tpl);
			$string .= $tpl;
		}
		elseif($type == "abs-file")
		{
			$string .= substr(md5($tpl),9,16);
			$string .= "_abs";
		}
		else
		{
			$string .= substr(md5($tpl),9,16);
			$string .= "_c";
		}
		$string .= ".php";
		return $string;
	}



	//显示HTML信息
	function display($tpl,$type="file",$path_format=true)
	{
		$this->output($tpl,$type,$path_format);
		exit;
	}

	//编译模板内容，通过正则替换自己需要的
	# compiling_id，生成的编译文件ID
	# tpl，模板源文件
	# type，模板类型
	function compiling($compiling_id,$tpl,$type="file",$path_format=true)
	{
		//判断是否刷新
		$is_refresh = false;
		if(!file_exists($this->dir_cache.$compiling_id) || $this->refresh) $is_refresh = true;
		if($type !="file" && $type != "file-ext" && $type != "abs-file") $is_refresh = true; #当模板不是使用文件时，则强制刷新，因为无法判断模板的时间
		if(!$is_refresh && ($type == "file" || $type == "file-ext" || $type == "abs-file"))
		{
			if($type == "file")
			{
				$tplfile = $this->dir_root.$this->dir_tpl.$tpl.".".$this->tpl_ext;
			}
			elseif($type == "file-ext")
			{
				$tplfile = $this->dir_root.$this->dir_tpl.$tpl;
			}
			else
			{
				$tplfile = $tpl;
			}
			if(!file_exists($tplfile))
			{
				$this->error("模板文件：".$tplfile." 不存在！");
			}
			if($this->refresh_auto && filemtime($tplfile) > filemtime($this->dir_cache.$compiling_id)) $is_refresh = true;
		}
		if(!$is_refresh) return true;
		$html_content = $this->get_content($tpl,$type);
		if(!$html_content) exit($this->ascii("模板的内容为空！"));
		$php_content = $this->html_to_php($html_content,$path_format);
		file_put_contents($this->dir_cache.$compiling_id,$this->html_head.$php_content);
		return true;
	}

	function html_to_php($content,$path_format=true)
	{
		//替换head内容
		$content = preg_replace(array('/<!--\s+head\s+(.+)\s+-->/eisU'),array('$this->head_php(\'\\1\')'),$content);
		$content = preg_replace(array('/<!--\s+plugin\s*(.*)\s+-->/eisU'),array('$this->plugin_php(\'\\1\')'),$content);
		//提取内容并置换
		# 第一步，替换模板中的最常见的代码，不复杂的HTML
		$old = array(
			'/(\{|<!--)\s*(\/if|endif|end|endforeach|\/foreach|endfor|\/for|endwhile|\/while|\/loop)\s*(\}|-->)/isU', //这个是条件，循环等结束时使用
			'/(\{|<!--)\s*(else)\s*(\}|-->)/isU', //这个是条件里的else
			'/\{debug\s+\$(.+)\}/isU',
			'/<!--\s*php\s*-->/isU', //多行PHP程序执行开始
			'/<!--\s*\/\s*php\s*-->/isU' //多行PHP程序执行结束
		);
		$new = array(
			'<?php } ?>',
			'<?php } else { ?>',
			'<?php echo "<pre>".print_r($\\1,true)."</pre>"; ?>',
			'<?php',
			'?>'
		);
		$content = preg_replace($old,$new,$content);
		# 第二步，整理模板中的css，images，js路径
		if($this->path_change && $path_format)
		{
			$tmp_path_list = explode(",",$this->path_change);
			$tmp_path_list = array_unique($tmp_path_list);
			foreach($tmp_path_list AS $key=>$value)
			{
				$value = trim($value);
				if(!$value) continue;
				$content = str_replace($value."/",$this->dir_tpl.$value."/",$content);
			}
		}

		#第五步，替换复杂的语法
		$old = array(
			'/(\{|<!--\s*)(arclist|arc|catelist|cate|project|sublist|parent|plist|fields|user|userlist)[:]*([a-zA-Z\_0-9\$]*)\s+(.+)(\}|\s*-->)/eisU',
			'/(\{|<!--\s*)\/(arclist|arc|catelist|cateinfo|subcate|project|sublist)[:]*([a-zA-Z\_0-9\$]*)(\}|\s*-->)/eisU',
			'/(\{|<!--\s*)unset\s*(:|\(|=)\s*([^\)]+)[\)]*(\}|\}|\s*-->)/eisU',
			'/(\{|<!--\s*)\$([a-zA-Z0-9_\$\[\]\'\\\"\.\-]{1,60})\s+AS\s+(.+)(\}|\s*-->)/eisU', //数组循环，不带foreach写法
			'/(\{|<!--\s*)foreach\s*\(\s*(.+)\s+AS\s+(.+)\s*\)\s*(\}|\s*-->)/eisU', //Foreach循环，PHP写法
			'/(\{|<!--\s*)loop\s+(.+)(\}|\s*-->)/eisU', //使用loop循环，比较复杂的循环，参数多
			'/(\{|<!--\s*)(while|for)\s*\(\s*(.+)\s*\)\s*(\}|\s*-->)/eisU', //while/for语法循环，PHP写法
			'/(\{|<!--\s*)(while|for)\s+(.+)(\}|\s*-->)/eisU', //while/for语法循环
			'/(\{|<!--\s*)(if|else\s*if)\s*\(\s*(.+)\s*\)\s*(\}|\s*-->)/eisU', //判断语法，PHP写法，因为这个写法多了括号，所以要放在上面，优先级高些
			'/(\{|<!--\s*)(if|else\s*if)\s+(.+)(\}|\s*-->)/eisU', //判断语法
			'/(\{|<!--\s*)include\s+(.+)(\}|\s*-->)/eisU', //包含文件
			'/(\{|<!--\s*)inc\s*(:|=)\s*(.+)(\}|\s*-->)/eisU', //包含文件
			'/(\{|<!--)\s*(run|php)\s*(:|\s+)\s*(.+)\s*(\}|-->)/eisU', //这个是执行单行的PHP信息
			'/\{url\s+(.+)\/\}/eisU', //包含文件
			'/\{ajaxurl\s+(.+)\/\}/eisU', //Ajax网址
			'/\{func\s+(.+)\}/eisU' //包含文件
		);
		$new = array(
			'$this->data_php(\'\\2\',\'\\3\',\'\\4\')',
			'$this->undata_php(\'\\3\')',
			'$this->undata_php(\'\\3\')',
			'$this->foreach_php(\'$\\2\',\'\\3\')',
			'$this->foreach_php(\'\\2\',\'\\3\')',
			'$this->loop_php(\'\\2\')',
			'$this->for_while_php(\'\\2\',\'\\3\')',
			'$this->for_while_php(\'\\2\',\'\\3\')',
			'$this->if_php(\'\\2\',\'\\3\')',
			'$this->if_php(\'\\2\',\'\\3\')',
			'$this->include_php(\'\\2\')',
			'$this->inc_php(\'\\3\')',
			'$this->php_runing(\'\\4\')',
			'$this->url_php(\'\\1\')',
			'$this->ajaxurl_php(\'\\1\')',
			'$this->func_php(\'\\1\')'
		);
		$content = preg_replace($old,$new,$content);

		# 第六步，基本HTML语法输出
		$old = array(
			'/\{\$([a-zA-Z\_].*)\s*\}/eisU',
			'/\{\s*(:|=|echo\s+)\s*(.+)\}/eisU' //复杂的PHP输出，兼容旧版写法，适用于三元写法
		);
		$new = array(
			'$this->echo_php(\'$\\1\')',
			'$this->echo_php(\'\\2\')'
		);
		$content = preg_replace($old,$new,$content);
		return $content;
	}

	//更换头部信息
	function head_php($string)
	{
		if(!$string || !trim($string)) return '';
		$string = trim($string);
		$string = stripslashes($string);
		$string = preg_replace("/[\"|']{1}(.+)[\"|']{1}/eisU","rawurlencode('\\1')",$string);//将内容格式化并去除引号
		$string = preg_replace("/(\x20{2,})/"," ",$string);
		$string = str_replace(" ","&",$string);
		$string = $this->str_format($string);
		parse_str($string,$list);
		$tmpc = "";
		if($list)
		{
			foreach($list AS $key=>$value)
			{
				$value = substr($value,0,1) == '$' ? $value : '"'.$value.'"';
				$list[$key] = "'".$key."'=>".rawurldecode($value);
			}
			$tmpc = 'array('.implode(",",$list).')';
		}
		$tmpc = stripslashes($tmpc);
		return '<?php echo tpl_head('.$tmpc.');?>';
	}

	function php_runing($string)
	{
		if(!$string || !trim($string)) return '';
		$string = trim($string);
		$string = stripslashes($string);
		$string = $this->str_format($string,false,false);
		return '<?php '.$string.';?>';
	}

	//暂时不管参数
	function plugin_php($string)
	{
		return '<?php echo phpok_plugin();?>';
	}

	//
	function data_php($a,$b="",$c="")
	{
		if(!$a) return '<?php echo "";?>';
		if(!$b) $b = '$list';
		if(substr($b,0,1) != '$') $b = '$'.$b;
		$tmp_c = 'array()';
		if($c)
		{
			$c = preg_replace("/(\x20{2,})/"," ",$c);# 去除多余空格，只保留一个空格
			$c = str_replace(" ","&",$c);
			$c = $this->str_format($c);
			parse_str($c,$list);
			if($list)
			{
				$tmp = array();
				foreach($list AS $key=>$value)
				{
					$t = substr($value,0,1) == '$' ? $value : '"'.$value.'"';
					$tmp[] = "'".$key."'=>".$t;
				}
				$tmp_c = "array(".implode(",",$tmp).")";
				$tmp_c = stripslashes($tmp_c);
			}
		}
		$info = '<?php '.$b."=phpok('_".$a."',".$tmp_c.");?>";
		return $info;
	}

	//注销PHP信息
	function undata_php($b="")
	{
		if(!$b) $b= '$list';
		if(substr($b,0,1) != '$') $b = '$'.$b;
		$b = preg_replace("/(\x20{2,})/"," ",$b);# 去除多余空格，只保留一个空格
		$b = $this->str_format($b);
		$b = str_replace(" ",",",$b);
		return '<?php unset('.$b.');?>';
	}

	function include_php($string)
	{
		//$string = preg_replace("/[\"|']{1}(.+)[\"|']{1}/eisU","rawurlencode('\\1')",$string);//将内容格式化并去除引号
		$rs = $this->str_to_list($string);
		if(!$rs) return false;
		if(!$rs["tpl"] && !$rs["file"] && !$rs["php"]) return false;
		$string = "";
		foreach($rs AS $key=>$value)
		{
			if($key != "tpl" && $key != "file")
			{
				if(substr($value,0,1) != '$') $value = '"'.$value.'"';
				$string .= '<?php $'.$key.'='.$value.';?>';
				$string .= '<?php $this->assign("'.$key.'",'.$value.'); ?>';
			}
		}
		# 当存在file参数量
		if($rs["file"] && file_exists($this->dir_php.$rs["file"]))
		{
			$string .= '<?php include("'.$this->dir_php.$rs["file"].'");?>';
		}
		if($rs["tpl"])
		{
			$string .= '<?php $this->output("'.$rs["tpl"].'","file"); ?>';
		}
		return $string;
	}

	function inc_php($string)
	{
		if(!$string) return false;
		$string = 'tpl="'.$string.'"';
		return $this->include_php($string);
	}

	//处理网址
	function url_php($string)
	{
		if(!$string || !trim($string)) return false;
		$string = trim($string);
		$string = preg_replace("/(\x20{2,})/"," ",$string);# 去除多余空格，只保留一个空格
		$string = str_replace(" ","&",$string);
		parse_str($string,$list);
		if(!$list || count($list)<1) return false;
		$array = array();
		foreach($list AS $key=>$value)
		{
			$value = $this->str_format($value);
			if(substr($value,0,1) != '$') $value = "'".$value."'";
			$array[] = "'".$key."'=>".$value;
		}
		return '<?php echo phpok_url(array('.implode(",",$array).'));?>';
	}

	function ajaxurl_php($string)
	{
		if(!$string || !trim($string)) return $this->return_false();
		$string = trim($string);
		$string = preg_replace("/(\x20{2,})/"," ",$string);# 去除多余空格，只保留一个空格
		parse_str($string,$list);
		if(!$list || count($list)<1) return $this->return_false();
		$url = $GLOBALS['app']->url.$GLOBALS['app']->config['www_file']."?";
		$url.= $GLOBALS['app']->config['ctrl_id']."=ajax";
		foreach($list AS $key=>$value)
		{
			$value = $this->str_format($value);
			if(substr($value,0,1) == '$')
			{
				$url .= "&".$key.'=<?php echo rawurlencode('.$value.');?>';
			}
			else
			{
				$url .= "&".$key.'='.rawurlencode($value);
			}
		}
		return $url;
	}

	function return_false()
	{
		return '<?php echo "";?>';
	}

	function func_php($string)
	{
		if(!$string) return false;
		$string = trim($string);
		if(!$string) return false;
		$string = stripslashes($string);
		$string = preg_replace("/[\"|']{1}(.+)[\"|']{1}/eisU","rawurlencode('\\1')",$string);//将内容格式化并去除引号
		$string = preg_replace("/(\x20{2,})/"," ",$string);# 去除多余空格，只保留一个空格
		$list = explode(" ",$string);
		$func = $list[0];
		if(!$func || !function_exists($func))
		{
			return false;
		}
		$string = '<?php echo '.$func.'(';
		$newlist = array();
		foreach($list AS $key=>$value)
		{
			if($key>0)
			{
				if($value)
				{
					$value = $this->str_format($value);
					if($value)
					{
						if(substr($value,0,1) != '$')
						{
							$newlist[] = "'".rawurldecode($value)."'";
						}
						else
						{
							$newlist[] = rawurldecode($value);
						}
					}
				}
			}
		}
		$newstring = implode(",",$newlist);
		$string .= $newstring.');?>';
		return $string;
	}

	# PHP输出
	function echo_php($string)
	{
		if(!$string) return false;
		$string = trim($string);
		$string = stripslashes($string);
		$string = $this->str_format($string,false,false);
		return '<?php echo '.$string.';?>';
	}

	# While/For循环
	function for_while_php($left,$string)
	{
		if(!$string || !$left) return false;
		$string = trim($string);
		$string = stripslashes($string);
		$string = $this->str_format($string,false);
		$php = '<?php '.$left.'('.$string.'){ ?>';
		return $php;
	}

	# Foreach 简单循环
	function foreach_php($from,$value)
	{
		if(!$from || !$value) return false;
		$list = explode("=>",$value);
		if($list[1])
		{
			$key = $list[0];
			$value = $list[1];
		}
		$string = 'from="'.$from.'" value="'.$value.'"';
		if($key)
		{
			$string .= ' key="'.$key.'"';
		}
		return $this->loop_php($string);
	}

	# IF 条件操作
	function if_php($left_string,$string)
	{
		if(!$string || !$left_string) return false;
		$string = trim($string);
		$string = stripslashes($string);
		# 通过正则替换文本中的.为[]
		$string = $this->str_format($string,false,false);
		if(strtolower(substr($left_string,0,4)) == "else") $left_string = '}'.$left_string;
		$php = '<?php '.$left_string.'('.$string.'){ ?>';
		return $php;
	}

	function get_loop_id($from)
	{
		$from = substr($from,1);
		$from = str_replace(array("['",'["',"']",'"]','$','-'),"_",$from);
		$from = str_replace(array("[","]"),"_",$from);
		if(substr($from,-1) != "_")
		{
			$from .= "_";
		}
		return $from."id";
	}

	# Loop循环格式化，此循环支持指定ID，可用于统计
	function loop_php($string)
	{
		$rs = $this->str_to_list($string,"key,value,from");
		if(!$rs) return false;
		if(!$rs || !is_array($rs) || count($rs)<1 || !$rs["from"]) return false;
		# 初始化循环的ID，未设置ID的用户，将取得rslist里的信息
		if(!$rs["id"]) $rs["id"] = $this->get_loop_id($rs["from"]);
		$id = $rs["id"];
		if(in_array(substr($id,0,1),array("0","1","2","3","4","5","6","7","8","9"))) $id = "phpok_".$id;
		if(substr($id,0,1) == '$') $id = substr($id,1);
		# 计算当前循环对应数量ID
		$php  = '<?php $'.$id.'["num"] = 0;';
		# 判断是否数组
		$php .= $rs["from"].'=is_array('.$rs["from"].') ? '.$rs["from"].' : array();';
		# 计算循环数据的总数
		$php .= '$'.$id.'["total"] = count('.$rs["from"].');';
		# 计算循环对应的索引ID
		if(!$rs["index"]) $rs["index"] = 0;
		$index_id = $rs["index"] - 1;
		$php .= '$'.$id.'["index"] = '.$index_id.';';
		if(!$rs["value"])
		{
			$rs["value"] = '$value';
		}
		$php .= 'foreach('.$rs["from"].' AS ';
		if($rs["key"] || $rs["item"])
		{
			if(!$rs["key"]) $rs["key"] = $rs["item"];
			$php .= $rs["key"]."=>";
		}
		$php .= $rs["value"].'){ ';
		$php .= '$'.$id.'["num"]++;';
		$php .= '$'.$id.'["index"]++;';
		$php .= ' ?>';
		return $php;
	}

	# 格式化文本，去除首尾引号，将.数组变成[]模式
	# string，要格式化的文本
	# auto_dollar，前面是否主动添加 $ 符号，默认为否
	# del_mark，是否删除引号
	function str_format($string,$auto_dollar=false,$del_mark=true)
	{
		if($string == '') return false;
		//$string = stripslashes($string);
		if($del_mark)
		{
			if(substr($string,0,1) == '"' || substr($string,0,1) == "'") $string = substr($string,1);
			if(substr($string,-1) == '"' || substr($string,-1) == "'") $string = substr($string,0,-1);
		}
		$string = $this->points_to_array($string);
		if($auto_dollar && substr($string,0,1) != '$')
		{
			$string = '$'.$string;
		}
		return $string;
	}

	# 点变成数组
	function points_to_array($string)
	{
		if(!$string) return false;
		//if(substr($string,0,1) != '$' && substr($string,1,1) != '$') return $string;
		for($i=0;$i<5;$i++)
		{
			$string = preg_replace('/\$([\w\[\]\>\-]+)\.([\w]+\b)/iU','$\\1[\\2]',$string);
		}
		$string = preg_replace('/\[([a-z\_][a-z0-9\_]*)\]/iU',"['\\1']",$string);
		return $string;
	}

	# 字符串格式化为数组
	function str_to_list($string,$need_dollar="")
	{
		if(!$string || !trim($string)) return false;
		$string = trim($string);# 去除首尾空格
		$string = stripslashes($string); # 将取消代码的转议
		$string = preg_replace("/[\"|']{1}(.+)[\"|']{1}/eisU","rawurlencode('\\1')",$string);//将内容格式化并去除引号
		$string = preg_replace("/(\x20{2,})/"," ",$string);# 去除多余空格，只保留一个空格
		$list = explode(" ",$string); # 格式化为数组
		$rs = array();
		if($need_dollar && !is_array($need_dollar))
		{
			$need_dollar = explode(",",$need_dollar);
		}
		else
		{
			if(!$need_dollar) $need_dollar = array();
		}
		foreach($list AS $key=>$value)
		{
			$value = trim($value);
			if($value)
			{
				$str = explode("=",$value);
				$str_key = strtolower($str[0]);
				$str_value = $str[1];
				if($str_key && $str_value)
				{
					$str_value = rawurldecode($str_value);
					$str_value = in_array($str_key,$need_dollar) ? $this->str_format($str_value,true) : $this->str_format($str_value,false);
					$rs[$str_key] = $str_value;
				}
			}
		}
		return $rs;
	}

	//取得模板的内容
	function get_content($tpl,$type="file")
	{
		if(!$tpl) return false;
		if($type == "content" || $type == "msg") return $tpl;
		if($type == "file")
		{
			$tplfile = $this->dir_root.$this->dir_tpl.$tpl.".".$this->tpl_ext;
		}
		elseif($type == "file-ext")
		{
			$tplfile = $this->dir_root.$this->dir_tpl.$tpl;
		}
		else
		{
			$tplfile = $tpl;
		}
		if(!file_exists($tplfile))
		{
			$this->error("模板文件：".$tplfile." 不存在！");
		}
		return file_get_contents($tplfile);
	}

	function ascii($str)
	{
		if(!$str)
		{
			return false;
		}
		$str = iconv("UTF-8", "UTF-16BE", $str);
		for ($i = 0; $i < strlen($str); $i++,$i++)
		{
			$code = ord($str{$i}) * 256 + ord($str{$i + 1});
			if ($code < 128)
			{
				$output .= chr($code);
			}
			else if($code != 65279)
			{
				$output .= "&#".$code.";";
			}
		}
		return $output;
	}

	function error($msg)
	{
		exit($this->ascii($msg));
	}

	function ext()
	{
		return $this->tpl_ext;
	}

	function get_tpl($tplname,$default="default")
	{
		$tplfile = $this->dir_tpl.$tplname.".".$this->tpl_ext;
		if(file_exists($tplfile))
		{
			return $tplname;
		}
		else
		{
			return $default;
		}
	}

	//检测文件是否存在
	function check_exists($tplname,$isext=false,$ifabs=false)
	{
		$tplfile = $tplname;
		if(!$isext) $tplfile .= ".".$this->tpl_ext;
		if(!$ifabs) $tplfile = $this->dir_root.$this->dir_tpl.$tplfile;
		if(is_file($tplfile))
		{
			return true;
		}
		return false;
	}
}
