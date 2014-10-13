<?php
/***********************************************************
	Filename: phpok/init.php
	Note	: PHPOK框架入口引挈文件，请不要改动此文件
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-15 15:30
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
header("Content-type: text/html; charset=utf-8");
//将数组存为XML
function array_to_xml(&$xml,$rs,$ext="")
{
	if(is_array($rs))
	{
		foreach($rs AS $key=>$value)
		{
			if(is_array($value))
			{
				$ext .= "	";
				$xml .= "<".$key.">\n";
				array_to_xml($xml,$value,$ext);
				$xml .= "</".$key.">\n";
			}
			else
			{
				$xml .= $ext."<".$key.">".$value."</".$key.">\n";
			}
		}
	}
}

//将XML转成数组
//xml纯内容
function xml_to_array($xml)
{
	$reg = "/<([a-zA-Z0-9\_\-]+)([^>]*?)>([\\x00-\\xFF]*?)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches))
	{
		$count = count($matches[0]);
		$arr = array();
		for($i = 0; $i < $count; $i++)
		{
			$key = $matches[1][$i];
			$ext = $matches[2][$i];
			$val = xml_to_array( $matches[3][$i] );  // 递归
			if(array_key_exists($key, $arr))
			{
				if(is_array($arr[$key]))
				{
					if(!array_key_exists(0,$arr[$key]))
					{
						$arr[$key] = array($arr[$key]);
					}
				}else{
					$arr[$key] = array($arr[$key]);
				}
				if(!$val && $ext && trim($ext))
				{
					$ext = trim($ext);
					preg_match_all("/([0-9a-zA-Z\_]+)\=[\"|'](.+)[\"|']/isU",$ext,$extlist);
					if($extlist[1])
					{
						$tmplist = array();
						foreach($extlist[1] AS $kk=>$vv)
						{
							$tmplist[$vv] = $extlist[2][$kk];
						}
						$arr[$key][] = $tmplist;
					}
					else
					{
						$arr[$key][] = $val;
					}
				}
				else
				{
					if($ext && trim($ext))
					{
						$ext = trim($ext);
						preg_match_all("/([0-9a-zA-Z\_]+)\=[\"|'](.+)[\"|']/isU",$ext,$extlist);
						if($extlist[1])
						{
							$tmplist = array();
							foreach($extlist[1] AS $kk=>$vv)
							{
								$tmplist[$vv] = $extlist[2][$kk];
							}
							$arr[$key][] = array("id"=>$tmplist,"val"=>$val);
						}
						else
						{
							$arr[$key][] = $val;
						}
					}
					else
					{
						$arr[$key][] = $val;
					}
				}
			}else{
				if($val && !is_array($val))
				{
					$val = preg_replace('/\{html:(.+)\}/isU','<\\1>',$val);
					$val = preg_replace('/\{\/(.+):html\}/isU','</\\1>',$val);
				}
				if(!$val && $ext && trim($ext))
				{
					$ext = trim($ext);
					preg_match_all("/([0-9a-zA-Z\_]+)\=[\"|'](.+)[\"|']/isU",$ext,$extlist);
					if($extlist[1])
					{
						$tmplist = array();
						foreach($extlist[1] AS $kk=>$vv)
						{
							$tmplist[$vv] = $extlist[2][$kk];
						}
						$arr[$key] = $tmplist;
					}
					else
					{
						$arry[$key] = $val;
					}
				}
				else
				{
					if($ext && trim($ext))
					{
						$ext = trim($ext);
						preg_match_all("/([0-9a-zA-Z\_]+)\=[\"|'](.+)[\"|']/isU",$ext,$extlist);
						if($extlist[1])
						{
							$tmplist = array();
							foreach($extlist[1] AS $kk=>$vv)
							{
								$tmplist[$vv] = $extlist[2][$kk];
							}
							$arr[$key]["id"] = $tmplist;
							$arr[$key]["val"] = $val;
						}
						else
						{
							$arr[$key] = $val;
						}
					}
					else
					{
						$arr[$key] = $val;
					}
				}
			}
		}
		return $arr;
	}else{
		return $xml;
	}
}


# 将文本转为ASCII码，实现在任意网页下显示中文提示
# PHPOK的开发过程中的错误提示，八成都是中文提示
function phpok_ascii($str)
{
	if(!$str) return false;
	$str = iconv("UTF-8", "UTF-16BE", $str);
	$output = "";
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

# 错误提示，此错误提示将会直接输出并中止下一步执行
function phpok_errmsg($str,$is_format=true)
{
	if($str && $is_format) $str = phpok_ascii($str);
	exit($str);
}

function phpok_exit($str,$is_format = true)
{
	if($str && $is_format) $str = phpok_ascii($str);
	exit($str);
}

//计算执行的时间
function run_time($is_end=false)
{
	$time = explode(" ",microtime());
	if(!$is_end)
	{
		if(defined("SYS_TIME_START"))
		{
			return false;
		}
		define("SYS_TIME_START",($time[0] + $time[1]));
	}
	else
	{
		if(!defined("SYS_TIME_START"))
		{
			return false;
		}
		$time = $time[0] + $time[1] - SYS_TIME_START;
		return round($time,5);
	}
}

//登记内存
function run_memory($is_end=false)
{
	if(!$is_end)
	{
		if(defined("SYS_MEMORY_START") || !function_exists("memory_get_usage"))
		{
			return false;
		}
		define("SYS_MEMORY_START",memory_get_usage());
	}
	else
	{
		if(!defined("SYS_MEMORY_START"))
		{
			return false;
		}
		$memory = memory_get_usage() - SYS_MEMORY_START;
		//格式化大小
		if($memory <= 1024)
		{
			$memory = "1 KB";
		}
		elseif($memory>1024 && $memory<(1024*1024))
		{
			$memory = round(($memory/1024),2)." KB";
		}
		else
		{
			$memory = round(($memory/(1024*1024)),2)." MB";
		}
		return $memory;
	}
}

run_time();
run_memory();

function debug_time()
{
	global $app;
	$app->lib("file");
	$count = $app->file_lib->read_count;
	$count += $app->cache->count();
	$time = run_time(true);
	$memory = run_memory(true);
	$string = "运行 ".$time." 秒，用内存 ".$memory."，数据库 ".$app->db->query_count." 次，用时 ".$app->db->query_times." 秒";
	$string.= "，文件读写 ".$count." 次 ";
	return $string;
}

//PHPOK4最新框架，其他应用可直接通过该框架调用
class _init_phpok
{
	//应用ID
	var $app_id = "www";
	//网站根目录
	var $dir_root = "./";
	//框架目录
	var $dir_phpok = "phpok/";
	//引挈库
	var $engine;
	//Model库
	var $model;
	var $model_list;
	//通用类库
	var $lib;
	var $lib_list;
	//应用
	var $obj;
	var $obl_list;
	//配置信息
	var $config;
	//版本
	var $version = "4.0";
	//当前时间
	var $time;
	//网址
	var $url;
	//缓存信息（任意接口都可以通过获取该缓存信息）
	var $cache_data;
	//授权相关信息
	var $license = "LGPL";
	var $license_code = "ED988858BCB1903A529C762DBA51DD40";
	var $license_date = "2012-10-29";
	var $license_name = "phpok";
	var $license_site = ".phpok.com";
	var $license_powered = true;

	//是否是手机端，如果使用手机端可能会改写网址
	var $is_mobile = false;

	//定义插件
	var $plugin = '';

	function __construct()
	{
		//取消转义
		ini_set("magic_quotes_runtime",0);
		//读取常量配置
		$this->init_constant();
		//读取参数配置
		$this->init_config();
		//装配资源引挈
		$this->is_mobile = $this->config['is_mobile'];
		if(!$this->is_mobile)
		{
			$this->is_mobile = $this->is_mobile();
		}
		
		$this->init_engine();
	}

	function init_assign()
	{
		//取得当前页网址
		$url = $this->url;
		$afile = $this->config[$this->app_id.'_file'];
		if(!$afile) $afile = 'index.php';
		$url .= $afile;
		if($_SERVER['QUERY_STRING']) $url .= "?".$_SERVER['QUERY_STRING'];
		$this->site["url"] = $url;
		$this->config["url"] = $this->url;
		$this->config['app_id'] = $this->app_id;
		//核心变量赋值
		$this->assign("sys",$this->config);
		//针对网站进行SEO优化
		$this->phpok_seo($this->site);
		//针对站点信息赋值
		$this->assign("config",$this->site);
		//装载默认语言包
		if(is_file($this->dir_root."data/xml/lang_default.xml"))
		{
			$this->lang = xml_to_array(file_get_contents($this->dir_root."data/xml/lang_default.xml"));
		}
		//加载语言包，如果存在
		if($this->site['lang'] && is_file($this->dir_root."data/xml/langs/".$this->site['lang'].".xml"))
		{
			$lang = xml_to_array(file_get_contents($this->dir_root."data/xml/langs/".$this->site['lang'].".xml"));
		}
		//合并语言包
		if($lang && $this->lang)
		{
			$this->lang = array_merge($this->lang,$lang);
		}
		if($this->lang && is_array($this->lang))
		{
			foreach($this->lang AS $key=>$value)
			{
				foreach($value AS $k=>$v)
				{
					$this->lang[$key][$k] = $this->ubb($v);
				}
			}
			$this->assign("lang",$this->lang[$this->app_id]);
		}
	}

	//语言包变量格式化
	final public function lang_format($info,$var)
	{
		if(!$info) return false;
		if(!$var || !is_array($var)) return $info;
		foreach($var AS $key=>$value)
		{
			$info = str_replace('{'.$key.'}',$value,$info);
		}
		return $info;
	}

	//UBB代码格式化
	public function ubb($Text,$nl2br=true) 
	{
		if(is_array($Text))
		{
			foreach($Text AS $key=>$value)
			{
				$value = $this->ubb($value,$nl2br);
				$Text[$key] = $value;
			}
			return $Text;
		}
		$Text=trim($Text);
		//是否启用nl2br
		if($nl2br) $Text=str_replace("\n","<br />",$Text);
		$Text=preg_replace("/\\t/is","",$Text);
		$Text=preg_replace("/\[hr\]/is","<hr />",$Text);
		$Text=preg_replace("/\[separator\]/is","<br/>",$Text);
		$Text=preg_replace("/\[h1\](.+?)\[\/h1\]/is","<h1>\\1</h1>",$Text);
		$Text=preg_replace("/\[h2\](.+?)\[\/h2\]/is","<h2>\\1</h2>",$Text);
		$Text=preg_replace("/\[h3\](.+?)\[\/h3\]/is","<h3>\\1</h3>",$Text);
		$Text=preg_replace("/\[h4\](.+?)\[\/h4\]/is","<h4>\\1</h4>",$Text);
		$Text=preg_replace("/\[h5\](.+?)\[\/h5\]/is","<h5>\\1</h5>",$Text);
		$Text=preg_replace("/\[h6\](.+?)\[\/h6\]/is","<h6>\\1</h6>",$Text);
		$Text=preg_replace("/\[center\](.+?)\[\/center\]/is","<div style='text-align:center'>\\1</div>",$Text);
		$Text=preg_replace("/\[url\](.+?)\[\/url\]/is","<a href=\"\\1\" target='_blank'>\\1</a>",$Text);
		$Text=preg_replace("/\[url=(http:\/\/.+?)\](.+?)\[\/url\]/is","<a href='\\1' target='_blank' title='\\2'>\\2</a>",$Text);
		$Text=preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is","<a href='\\1' title='\\2'>\\2</a>",$Text);
		$Text=preg_replace("/\[img\](.+?)\[\/img\]/is","<img src='\\1'>",$Text);
		$Text=preg_replace("/\[img\s(.+?)\](.+?)\[\/img\]/is","<img \\1 src='\\2'>",$Text);
		$Text=preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is","<span style='color:\\1'>\\2</span>",$Text);
		$Text=preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is","<span style='font-size:\\1'>\\2</span>",$Text);
		$Text=preg_replace("/\[sup\](.+?)\[\/sup\]/is","<sup>\\1</sup>",$Text);
		$Text=preg_replace("/\[sub\](.+?)\[\/sub\]/is","<sub>\\1</sub>",$Text);
		$Text=preg_replace("/\[pre\](.+?)\[\/pre\]/is","<pre>\\1</pre>",$Text);
		$Text=preg_replace("/\[email\](.+?)\[\/email\]/is","<a href='mailto:\\1'>\\1</a>",$Text);
		$Text=preg_replace("/\[i\](.+?)\[\/i\]/is","<i>\\1</i>",$Text);
		$Text=preg_replace("/\[u\](.+?)\[\/u\]/is","<u>\\1</u>",$Text);
		$Text=preg_replace("/\[b\](.+?)\[\/b\]/is","<b>\\1</b>",$Text);
		$Text=preg_replace("/\[quote\](.+?)\[\/quote\]/is","<blockquote><div style='border:1px solid silver;background:#EFFFDF;color:#393939;padding:5px' >\\1</div></blockquote>", $Text);
		//UBB下载格式化
		preg_match_all("/\[download[:|：]*([0-9]*)\](.*)\[\/download\]/isU",$Text,$list);
		if($list && count($list)>0)
		{
			$dlist = '';
			foreach($list[0] AS $key=>$value)
			{
				$tmpid = $list[1][$key] ? $list[1][$key] : intval($list[2][$key]);
				if($tmpid)
				{
					$dlist[] = array('string'=>$value,'id'=>$tmpid,'title'=>$list[2][$key]);
				}
			}
			if($dlist)
			{
				foreach($dlist AS $key=>$value)
				{
					if(!$value['title'] || $value['title'] == $value['id'])
					{
						$rs = $this->model('res')->get_one($value['id']);
						$value['title'] = $rs['title'];
					}
					//格式化链接
					$string = '<a href="'.$this->url('download','','id='.$value['id']).'" title="'.$value['title'].'" target="_blank">'.$value['title'].'</a>';
					$Text = str_replace($value['string'],$string,$Text);
				}
			}
		}
		$list = '';
		//格式化旧版的UBB附件下载
		preg_match_all("/\[download[:|：]*([0-9]+)\]/isU",$Text,$list);
		if($list && count($list)>0)
		{
			foreach($list[0] AS $key=>$value)
			{
				$tmpid = $list[1][$key];
				if($tmpid)
				{
					$rs = $this->model('res')->get_one($tmpid);
					if($rs)
					{
						$string = '<a href="'.$this->url('download','','id='.$rs['id']).'" title="'.$rs['title'].'" target="_blank">'.$rs['title'].'</a>';
						$Text = str_replace($value,$string,$Text);
					}
				}
			}
		}
		//格式化主题列表
		$list = '';
		preg_match_all("/\[title[:|：]*([0-9a-zA-Z\_\-]*)\](.+)\[\/title\]/isU",$Text,$list);
		if($list && count($list)>0)
		{
			$dlist = '';
			foreach($list[0] AS $key=>$value)
			{
				if(!$list[1][$key] || !$list[2][$key]) continue;
				$string = '<a href="'.$this->url($list[1][$key]).'" title="'.$list[2][$key].'" target="_blank">'.$list[2][$key].'</a>';
				$Text = str_replace($value,$string,$Text);
			}
		}
		return $Text;
	}

	function init_autoload()
	{
		if($this->config["autoload_model"])
		{
			$list = explode(",",$this->config["autoload_model"]);
			foreach($list AS $key=>$value)
			{
				$this->load($value,"model");
			}
		}
		if($this->config["autoload_lib"])
		{
			$list = explode(",",$this->config["autoload_lib"]);
			foreach($list AS $key=>$value)
			{
				$this->load($value,"lib");
			}
		}
	}

	# 加载视图引挈
	function init_view()
	{
		$file = $this->dir_phpok."phpok_tpl.php";
		if(!file_exists($file))
		{
			$this->error("视图引挈文件：".$file." 不存在！");
		}
		include_once($file);
		if($this->app_id == "admin")
		{
			$tpl_rs = array();
			$tpl_rs["id"] = "1";
			$tpl_rs["dir_tpl"] = basename($this->dir_phpok)."/view/";
			$tpl_rs["dir_cache"] = $this->dir_root."data/tpl_admin/";
			$tpl_rs["dir_php"] = $this->dir_root;
			$tpl_rs["dir_root"] = $this->dir_root;
			$tpl_rs["refresh_auto"] = true;
			$tpl_rs["tpl_ext"] = "html";
			$this->tpl = new phpok_tpl($tpl_rs);
			return false;
		}
		else
		{
			if(!$this->site["tpl_id"] || ($this->site["tpl_id"] && !is_array($this->site["tpl_id"])))
			{
				$this->error("未指定模板文件");
			}
			$this->tpl = new phpok_tpl($this->site["tpl_id"]);
			include_once($this->dir_phpok."phpok_call.php");
			$this->call = new phpok_call();
			include_once($this->dir_phpok."phpok_tpl_helper.php");
		}
	}

	//手机判断
	function is_mobile()
	{
		$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
		$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
		$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
		$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
		$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
		$regex_match.=")/i";
		return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
	}

	function init_site()
	{
		if($this->app_id == "admin")
		{
			if($_SESSION['admin_site_id'])
			{
				$site_rs = $this->model('site')->get_one($_SESSION['admin_site_id']);
			}
			else
			{
				$site_rs = $this->model("site")->get_one_default();
			}
			if(!$site_rs) $site_rs = array('title'=>'PHPOK企业建站系统');
			$this->site = $site_rs;
			return true;
		}
		$siteId = $this->get("siteId","int");
		$domain = strtolower($_SERVER["SERVER_NAME"]);
		$site_rs = false;
		if($siteId)
		{
			$site_rs = $this->model('site')->get_one($siteId);
			if($site_rs)
			{
				$domain_rs = $this->model('site')->domain_one($site_rs['domain_id']);
				if($domain_rs && $domain_rs['domain'] != $domain)
				{
					header("Location:http://".$domain_rs['domain'].$site_rs['dir']);
					exit;
				}
			}
		}
		$cacheId = $this->cache->key($domain,"","site");
		if($this->is_mobile) $cacheId = $this->cache->key($domain,"","site,mobile");
		$site_rs = $this->cache->read($cacheId);
		if(!$site_rs)
		{
			$site_rs = $this->model("site")->get_one_from_domain($domain);
			if(!$site_rs) $site_rs = $this->site_model->get_one_default();
			if(!$site_rs) $this->error("无法获取网站信息，请检查！");
			$ext_list = $this->site_model->site_config($site_rs["id"]);
			if($ext_list) $site_rs = array_merge($ext_list,$site_rs);
			//读取模板扩展
			if($site_rs["tpl_id"])
			{
				$this->model("tpl");
				$rs = $this->tpl_model->get_one($site_rs["tpl_id"]);
				if($rs)
				{
					$tpl_rs = array();
					$tpl_rs["id"] = $rs["id"];
					$tpl_rs["dir_tpl"] = $rs["folder"] ? "tpl/".$rs["folder"]."/" : "tpl/www/";
					$tpl_rs["dir_cache"] = $this->dir_root."data/tpl_www/";
					$tpl_rs["dir_php"] = $this->dir_root;
					$tpl_rs["dir_root"] = $this->dir_root;
					if($rs["folder_change"])
					{
						$tpl_rs["path_change"] = $rs["folder_change"];
					}
					$tpl_rs["refresh_auto"] = $rs["refresh_auto"] ? true : false;
					$tpl_rs["refresh"] = $rs["refresh"] ? true : false;
					$tpl_rs["tpl_ext"] = $rs["ext"] ? $rs["ext"] : "html";
					//针对手机版的配置
					if($this->is_mobile)
					{
						$tpl_rs["id"] = $rs["id"]."_mobile";
						$tplfolder = $rs["folder"] ? $rs["folder"]."_mobile" : "www_mobile";
						if(!file_exists($this->dir_root."tpl/".$tplfolder))
						{
							$tplfolder = $rs["folder"] ? $rs["folder"] : "www";
						}
						$tpl_rs["dir_tpl"] = "tpl/".$tplfolder;
					}
					$site_rs["tpl_id"] = $tpl_rs;
				}
			}
			if($cacheId) $this->cache->write($cacheId,$site_rs);
		}
		//判断网站是否开放
		if(!$site_rs["status"])
		{
			$this->error($site_rs["content"]);
		}
		$this->site = $site_rs;
	}

	//装载插件
	function init_plugin()
	{
		$rslist = $this->model('plugin')->get_all(1);
		if(!$rslist) return $rslist;
		foreach($rslist AS $key=>$value)
		{
			if($value['param']) $value['param'] = unserialize($value['param']);
			if(is_file($this->dir_root.'plugins/'.$key.'/'.$this->app_id.'.php'))
			{
				include_once($this->dir_root.'plugins/'.$key.'/'.$this->app_id.'.php');
				$name = $this->app_id."_".$key;
				$cls = new $name();
				$mlist = get_class_methods($cls);
				$this->plugin[$key] = array("method"=>$mlist,"obj"=>$cls,'id'=>$key);
			}
		}
	}
	function lib($class,$ext_folder="")
	{
		return $this->load($class,"lib",$ext_folder);
	}

	function model($class,$ext_folder="")
	{
		return $this->load($class,"model",$ext_folder);
	}

	//运行插件
	function plugin($ap,$param="")
	{
		if(!$ap) return false;
		$ap = str_replace("-","_",$ap);//替换节点的中划线为下划线
		if(!$this->plugin || count($this->plugin)<1 || !is_array($this->plugin)) return false;
		foreach($this->plugin AS $key=>$value)
		{
			if(in_array($ap,$value['method']))
			{
				$value['obj']->$ap($param);
			}
		}
		return true;
	}

	function load($class,$type="lib",$ext_folder="")
	{
		if(!$class) return false;
		$folder = $this->dir_phpok;
		$folder.= $type == "model" ? "model/" : "libs/";
		if($ext_folder && $ext_folder != "/")
		{
			$folder .= $ext_folder;
			if(substr($folder,-1) != "/") $folder .= "/";
		}
		$class_name = $class."_".($type == "model" ? "model" : "lib");
		if($type == "model")
		{
			if($this->$class_name && is_object($this->$class_name))
			{
				return $this->$class_name;
			}
			if(!$this->model_list || !is_array($this->model_list) || !in_array($class_name,$this->model_list))
			{
				if(!file_exists($folder.$class.".php"))
				{
					$this->error("Model层异常，文件：".$folder.$class.".php 不存在！");
				}
				include_once($folder.$class.".php");
				$this->$class_name = new $class_name();
				$this->model_list[] = $class_name;
			}
			if(!$this->model_list || !is_array($this->model_list) || !in_array($class_name,$this->model_list))
			{
				$this->error("Model库文件加载异常，请联系管理员");
			}
		}
		else
		{
			if(!$this->lib_list || !is_array($this->lib_list) || !in_array($class_name,$this->lib_list))
			{
				if(!file_exists($folder.$class.".php"))
				{
					$this->error("LIB库文件异常，文件：".$folder.$class.".php 不存在！");
				}
				include_once($folder.$class.".php");
				$this->$class_name = new $class_name($this);
				$this->lib_list[] = $class_name;
			}
			if(!$this->lib_list || !is_array($this->lib_list) || !in_array($class_name,$this->lib_list))
			{
				$this->error("LIB库文件加载异常，请联系管理员");
			}
		}
		return $this->$class_name;
	}

	//装载资源引挈
	function init_engine()
	{
		if(!$this->config["db"] && !$this->config["engine"])
		{
			$this->error("资源引挈装载失败，请检查您的资源引挈配置，如数据库连接配置等");
		}
		if($this->config["db"] && !$this->config["engine"]["db"])
		{
			$this->config["engine"]["db"] = $this->config["db"];
			$this->config["db"] = "";
		}
		//$engine_file_list = "";
		foreach($this->config["engine"] AS $key=>$value)
		{
			$file = $this->dir_phpok."engine/".$key."/".$value["file"].".php";
			if(file_exists($file))
			{
				include($file);
				$var = $key."_".$value["file"];
				$this->$key = new $var($value);
			}
		}
	}

	//读取网站参数配置
	function init_config()
	{
		//根目录下的config，一般该文件是来用配置全局网站需要用到的信息
		$file = $this->dir_root."config.php";
		if(is_file($file)) include($file);
		//全站全局参数
		$file = $this->dir_phpok."config/config.global.php";
		if(is_file($file)) include($file);
		//配置文档下的config，这里就可以针对各个应用进行配置了
		$file = $this->dir_phpok."config/config_".$this->app_id.".php";
		if(is_file($file)) include($file);
		//判断是否有使用Debug
		$config["debug"] ? error_reporting(E_ALL ^ E_NOTICE) : error_reporting(0);
		//判断是否使用gzip功能
		if(ini_get('zlib.output_compression'))
		{
			ob_start();
		}
		else
		{
			($config["gzip"] && function_exists("ob_gzhandler")) ? ob_start("ob_gzhandler") : ob_start();
		}
		//调节时差
		if($config["timezone"] && function_exists("date_default_timezone_set"))
		{
			date_default_timezone_set($config["timezone"]);
		}
		//调节时间误差，支持到秒
		$this->time = time();
		if($config["timetuning"])
		{
			$this->time = $this->time + $config["timetuning"];
		}
		$this->system_time = $this->time;
		$this->config = $config;
		$this->url = $this->root_url();
	}

	function url($ctrl="",$func="",$ext="")
	{
		if($this->app_id  == "admin")
		{
			$url = $this->config["admin_file"];
			if(!$ctrl && !$func && !$ext) return $url;
			if($ctrl || $func || $ext) $url .= "?";
			if($ctrl) $url .= $this->config["ctrl_id"]."=".$ctrl."&";
			if($func) $url .= $this->config["func_id"]."=".$func."&";
			if($ext) $url .= $ext;
			if(substr($url,-1) == "&" || substr($url,-1) == "?") $url = substr($url,0,-1);
			return $url;
		}
		$url = $this->url;
		if($this->site["url_type"] == "rewrite")
		{
			$url .= $ctrl;
			if($func)
			{
				$url .= "/".$func;
			}
			$url .= ".html";
			if($ext)
			{
				$url .="?".$ext;
			}
			return $url;
		}
		else
		{
			if(!$ctrl) return $url;
			$ctrl_list = $this->config['reserved'] ? explode(',',$this->config['reserved']) : array("cart","order","cp","search","login","download","logout","post");
			$url .= $this->config["www_file"];
			if(in_array($ctrl,$ctrl_list))
			{
				$url .= "?".$this->config["ctrl_id"]."=".$ctrl;
				if($func)
				{
					$url .= "&".$this->config["func_id"]."=".$func;
				}
				if($ext && $ext != "&")
				{
					if(substr($ext,0,1) == "&") $ext = substr($ext,1);
					$url .= "&".$ext;
				}
				return $url;
			}
			else
			{
				$url .= "?id=".$ctrl;
				if($func && $func != "&")
				{
					if(substr($func,0,1) == "&") $func = substr($func,1);
					$url .= "&cate=".$func;
				}
				if($ext && $ext != "&")
				{
					if(substr($ext,0,1) == "&") $ext = substr($ext,1);
					$url .= "&".$ext;
				}
				return $url;
			}
		}
	}

	function root_url()
	{
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
		$port = $_SERVER["SERVER_PORT"];
		//$myurl = $http_type.$_SERVER["SERVER_NAME"];
		$myurl = $_SERVER["SERVER_NAME"];
		if($port != "80" && $port != "443")
		{
			$myurl .= ":".$port;
		}
		$docu = $_SERVER["PHP_SELF"];
		$array = explode("/",$docu);
		$count = count($array);
		if($count>1)
		{
			foreach($array AS $key=>$value)
			{
				$value = trim($value);
				if($value)
				{
					if(($key+1) < $count)
					{
						$myurl .= "/".$value;
					}
				}
			}
		}
		$myurl .= "/";
		$myurl = str_replace("//","/",$myurl);
		return $http_type.$myurl;
	}
	
	//配置网站全局常量
	function init_constant()
	{
		//配置网站根目录
		if(!defined("ROOT")) define("ROOT",str_replace("\\","/",dirname(__FILE__))."/../");
		$this->dir_root = ROOT;
		if(substr($this->dir_root,-1) != "/") $this->dir_root .= "/";
		//配置框架根目录
		if(!defined("FRAMEWORK")) defined("FRAMEWORK",$this->dir_root."phpok/");
		$this->dir_phpok = FRAMEWORK;
		if(substr($this->dir_phpok,-1) != "/") $this->dir_phpok .= "/";
		if(substr($this->dir_phpok,0,strlen($this->dir_root)) != $this->dir_root)
		{
			$this->dir_phpok = $this->dir_root.$this->dir_phpok;
		}
		//定义APP_ID
		if(!defined("APP_ID")) define("APP_ID","phpok");
		$this->app_id = APP_ID;
		# 判断加载的版本及授权方式
		if(is_file($this->dir_root."version.php"))
		{
			include($this->dir_root."version.php");
			$this->version = defined("VERSION") ? VERSION : "4.0";
		}
		if(is_file($this->dir_root."license.php"))
		{
			include($this->dir_root."license.php");
			$license_array = array("LGPL","PBIZ","CBIZ");
			$this->license = (defined("LICENSE") && in_array(LICENSE,$license_array)) ? LICENSE : "LGPL";
			if(defined("LICENSE_DATE")) $this->license_date = LICENSE_DATE;
			if(defined("LICENSE_SITE")) $this->license_site = LICENSE_SITE;
			if(defined("LICENSE_CODE")) $this->license_code = LICENSE_CODE;
			if(defined("LICENSE_NAME")) $this->license_name = LICENSE_NAME;
			if(defined("LICENSE_POWERED")) $this->license_powered = LICENSE_POWERED;
		}
	}

	//注销
	function __destruct(){}

	//通过post或get取得数据，并格式化成自己需要的
	function get($id,$type="safe",$ext="")
	{
		$val = isset($_POST[$id]) ? $_POST[$id] : (isset($_GET[$id]) ? $_GET[$id] : "");
		if($val == '') return false;
		//判断内容是否有转义，所有未转义的数据都直接转义
		$addslashes = false;
		if(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) $addslashes = true;
		if(!$addslashes) $val = $this->_addslashes($val);
		return $this->format($val,$type,$ext);
	}

	//格式化内容
	//msg，要格式化的内容，该内容已经addslashes了
	//type，类型，支持：safe，text，html，html_js，func，int，float，system
	//ext，扩展，当type为html时，ext存在表示支持js，不存在表示不支持js
	//当type为func属性时，表示ext直接执行函数
	function format($msg,$type="safe",$ext="")
	{
		if($msg == "") return false;
		if(is_array($msg))
		{
			foreach($msg AS $key=>$value)
			{
				$msg[$key] = $this->format($value,$type,$ext);
			}
			return $msg;
		}
		//当返回为HTML+JS时，不再进行任何过滤
		if($type == "html_js") return $msg;
		if($type == "html")
		{
			if($ext) return $msg;
			//清除危险字符
			$msg = stripslashes($msg);
			$array = array(
				"/<script(.*)<\/script>/isU"
				,"/<frame(.*)>/isU","/<\/fram(.*)>/isU","/<iframe(.*)>/isU","/<\/ifram(.*)>/isU"
				,"/<style(.*)<\/style>/isU","/<link(.*)>/isU","/<\/link>/isU"
			);
			$msg = preg_replace($array,"",$msg);
			return addslashes($msg);
		}
		//当为复选框时的判断
		if($type == "checkbox" && strtolower($msg) == "on")
		{
			return 1;
		}
		if($type == "int" || $type == "intval")
		{
			return intval($msg);
		}
		if($type == "float" || $type == "floatval")
		{
			return floatval($msg);
		}
		$msg = stripslashes($msg);
		if($type == "system")
		{
			$msg = strtolower($msg);
			if(preg_match('/^[a-z][a-z0-9\_\-]+$/u',$msg))
			{
				return $msg;
			}
			else
			{
				$this->error("参数不符合系统要求，仅限：字母、数字、下划线和中划线，且首字必须是字母！");
			}
			return $msg;
		}
		if($type == "func" && function_exists($ext))
		{
			return $ext($msg);
		}
		if($type == "time") return strtotime($msg);
		$danger_string = array("\\","'",'"',"<",">");
		$clear = array("&#92;","&#39;","&quot;","&lt;","&gt;");
		$msg = str_replace($danger_string,$clear,$msg);
		return addslashes($msg);
	}

	//转义字符串数据，此函数仅限get使用
	function _addslashes($val)
	{
		if(is_array($val))
		{
			foreach($val AS $key=>$value)
			{
				$val[$key] = $this->_addslashes($value);
			}
		}
		else
		{
			$val = addslashes($val);
		}
		return $val;
	}

	function assign($var,$val)
	{
		$this->tpl->assign($var,$val);
	}

	function unassign($var)
	{
		$this->tpl->unassign($var);
	}

	function view($file,$type="file",$path_format=true)
	{
		$this->tpl->display($file,$type,$path_format);
	}

	function fetch($file,$type="file",$path_format=true)
	{
		return $this->tpl->fetch($file,$type,$path_format);
	}

	function get_url()
	{
		return $this->url;
	}
	//导常抛出
	function error($content="")
	{
		if(!$content) $content = "异常请检查";
		$html = '<!DOCTYPE html>'."\n";
		$html.= '<html>'."\n";
		$html.= '<head>'."\n";
		$html.= '	<meta charset="utf-8" />'."\n";
		$html.= '	<title>错误</title>'."\n";
		$html.= '</head>'."\n";
		$html.= '<body style="padding:10px;font-size:14px;">'."\n";
		$html.= $content."\n";
		$html.= '</body>'."\n";
		$html.= '</html>';
		exit($html);
	}

	# 判断是否是UTF8
	function is_utf8($string)
	{
		return preg_match('/^(?:[x09x0Ax0Dx20-x7E]|[xC2-xDF][x80-xBF]|xE0[xA0-xBF][x80-xBF]|[xE1-xECxEExEF][x80-xBF]{2}|xED[x80-x9F][x80-xBF]|xF0[x90-xBF][x80-xBF]{2}|[xF1-xF3][x80-xBF]{3}| xF4[x80-x8F][x80-xBF]{2})*$/xs',$string);
	}

	//字符串转换
	function charset($msg,$from_charset="GBK",$to_charset="UTF-8")
	{
		if(!$msg)
		{
			return false;
		}
		if(is_array($msg))
		{
			foreach($msg AS $key=>$value)
			{
				$msg[$key] = $this->charset($value,$from_charset,$to_charset);
			}
		}
		else
		{
			if(function_exists("iconv"))
			{
				$msg = iconv($from_charset,$to_charset,$msg);
			}
		}
		return $msg;
	}

	//执行应用
	function action()
	{
		$this->init_assign();
		//装载插件
		$this->init_plugin();
		if($this->app_id == "admin")
		{
			$this->action_admin();
		}
		else
		{
			$this->action_www();
		}
	}

	function action_www()
	{
		$ctrl = $this->get($this->config["ctrl_id"],"system");
		$func = $this->get($this->config["func_id"],"system");
		//获取标识
		if(!$ctrl)
		{
			$id = $this->get("id");
			$ctrl = "index";
			$t = $this->config["reserved"] ? explode(",",$this->config["reserved"]) : array();
			if($id && $t && in_array($id,$t))
			{
				$ctrl = $id;
				$func = $this->get("cate","system");
			}
			else
			{
				if($id && intval($id) > 0)
				{
					$ctrl = 'content';
				}
				else
				{
					if($id && $id != "index")
					{
						$ctrl = $this->model('id')->get_ctrl($id,$this->site['id']);
					}
				}
			}
		}
		# 判断是否需要全局登录
		$no_check = array("login","register","ajax","js","inp","logout");
		if($ctrl && !in_array($ctrl,$no_check) && !$_SESSION["user_id"] && $this->config["is_login"])
		{
			$url = $this->url("login");
			header("Location:".$url);
			exit;
		}
		if(!$func) $func = "index";
		$file = $this->dir_phpok.$this->app_id."/".$ctrl."_control.php";
		if($ctrl == "ajax" || $ctrl == "js" || $ctrl == "inp")
		{
			$file = $this->dir_phpok.$ctrl."_control.php";
		}
		if($ctrl != 'js')
		{
			//禁用cache
			header("Expires:".date("D, d M Y H:i:s",$this->time)." GMT");
			header("Last-Modified:".date("D, d M Y H:i:s",$this->time)." GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		}
		if(!is_file($file)) $this->error("应用文件：".$file." 不存在！");
		include($file);
		# 加载常用的函数
		if(is_file($this->dir_phpok."global.func.php")) include($this->dir_phpok."global.func.php");
		if(is_file($this->dir_phpok.$this->app_id."/global.func.php")) include($this->dir_phpok.$this->app_id."/global.func.php");
		//执行应用
		$app_name = $ctrl."_control";
		$this->ctrl = $ctrl;
		$this->func = $func;
		$cls = new $app_name();
		$list = get_class_methods($cls);
		$func_name = $func."_f";
		if(!in_array($func_name,$list))
		{
			$this->error("文件：".$appfile." 不存在方法：".$func_name."！");
		}
		# 判断是否自动加载的函数
		if($this->config["autoload_func"])
		{
			$list = explode(",",$this->config["autoload_func"]);
			foreach($list AS $key=>$value)
			{
				if(function_exists($value))
				{
					$value();
				}
			}
		}
		$this->config['ctrl'] = $this->ctrl;
		$this->config['func'] = $this->func;
		$this->assign('sys',$this->config);
		$cls->$func_name();
	}

	//仅限管理员的操作
	function action_admin()
	{
		$ctrl = $this->get($this->config["ctrl_id"],"system");
		$func = $this->get($this->config["func_id"],"system");
		if(!$ctrl) $ctrl = "index";
		if(!$func) $func = "index";
		$no_check = array("login","ajax","js","inp","logout");
		# 这里判断管理员的登录
		if($this->config["is_admin"] && !$_SESSION["admin_id"] && !in_array($ctrl,$no_check))
		{
			$url = $this->url("login");
			header("Location:".$url);
			exit;
		}
		$appfile = $this->dir_phpok.$this->app_id."/".$ctrl."_control.php";
		if($ctrl == "ajax" || $ctrl == "js" || $ctrl == "inp")
		{
			$appfile = $this->dir_phpok.$ctrl."_control.php";
		}
		$this->ctrl = $ctrl;
		$this->func = $func;
		if(!is_file($appfile))
		{
			$this->error("应用文件：".$appfile." 不存在！");
		}
		include($appfile);
		if($ctrl != 'js')
		{
			//禁用cache
			header("Expires:".date("D, d M Y H:i:s",$this->time)." GMT");
			header("Last-Modified:".date("D, d M Y H:i:s",$this->time)." GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
		}
		# 加载常用的函数
		if(is_file($this->dir_phpok."global.func.php")) include($this->dir_phpok."global.func.php");
		if(is_file($this->dir_phpok.$this->app_id."/global.func.php")) include($this->dir_phpok.$this->app_id."/global.func.php");
		//执行应用
		$app_name = $ctrl."_control";
		$cls = new $app_name();
		$list = get_class_methods($cls);
		$func_name = $func."_f";
		if(!in_array($func_name,$list))
		{
			$this->error("文件：".$appfile." 不存在方法：".$func_name."！");
		}
		# 判断是否自动加载的函数
		if($this->config["autoload_func"])
		{
			$list = explode(",",$cls->config_app["autoload_func"]);
			foreach($list AS $key=>$value)
			{
				if(function_exists($value))
				{
					$value();
				}
			}
		}
		include($this->dir_phpok."phpok_tpl_helper.php");
		$this->config['ctrl'] = $this->ctrl;
		$this->config['func'] = $this->func;
		$this->assign('sys',$this->config);
		$cls->$func_name();
	}

	//JSON内容输出
	final public function json($content,$status=false,$exit=true,$format=true)
	{
		//不格式化内容
		//1、人工不指定格式化内容，2、内容为空，3、内容是数组
		$status_info = $status ? 'ok' : 'error';
		if(!$format || $content == '' || is_array($content))
		{
			$rs = array('status'=>$status_info);
			if($content != '') $rs['content'] = $content;
			$info = $this->lib('json')->encode($rs);
			if($exit) exit($info);
			return $info;
		}
		//判断要格式化的内容是否存在相应的数据
		$list = explode('.',$content);
		//当数据只有一条时，不循环返回
		if(count($list) == 1)
		{
			$info = $this->lang[$this->app_id][$content] ? $this->lang[$this->app_id][$content] : $this->lang['global'][$content];
			if($info == '') $info = $content;
			$rs = array('status'=>$status_info,"content"=>$info);
			$info = $this->lib('json')->encode($rs);
			if($exit) exit($info);
			return $info;
		}
		foreach($list AS $key=>$value)
		{
			if($key < 1)
			{
				$info = $this->lang[$this->app_id][$value] ? $this->lang[$this->app_id][$value] : $this->lang['global'][$value];
			}
			else
			{
				if($info && is_array($info) && $info[$value])
				{
					$info = $info[$value];
				}
			}
		}
		$rs = array('status'=>$status_info);
		if($info != '' && !is_array($info)) $rs['content'] = $info;
		$info = $this->lib('json')->encode($rs);
		if($exit) exit($info);
		return $info;
	}

	//针对PHPOK4前台执行SEO优化
	final public function phpok_seo($rs)
	{
		if(!$rs || !is_array($rs)) return false;
		$seo = $this->site['seo'] ? $this->site["seo"] : array();
		foreach($rs AS $key=>$value)
		{
			if(substr($key,0,3) == "seo" && $value && is_string($value))
			{
				$subkey = substr($key,4);
				if($subkey == "kw" || $subkey == "keywords" || $subkey == "keyword")
				{
					$seo["keywords"] = $value;
				}
				elseif($subkey == "desc" || $subkey == "description")
				{
					$seo["description"] = $value;
				}
				elseif($subkey == "title")
				{
					$seo["title"] = $value;
				}
				else
				{
					$seo[$subkey] = $value;
				}
			}
		}
		$this->site['seo'] = $seo;
		$this->assign("seo",$seo);
	}

}

//核心魔术方法，此项可实现类，方法的自动加载
class _init_auto
{
	//构造函数
	public function __construct()
	{
		//
	}

	//魔术方法之方法重载
	public function __call($method,$param)
	{
		if(method_exists($GLOBALS['app'],$method))
		{
			return call_user_func_array(array($GLOBALS['app'],$method),$param);
		}
		else
		{
			$lst = explode("_",$method);
			if($lst[1] == 'model')
			{
				$GLOBALS['app']->model($lst[0]);
				 call_user_func_array(array($GLOBALS['app'],$method),$param);
			}
			elseif($lst[1] == 'lib')
			{
				$GLOBALS['app']->lib($lst[0]);
				return call_user_func_array(array($GLOBALS['app'],$method),$param);
			}
		}
	}

	//5.3.0版以后的使用方法，这是调用静态方法重载的魔术方法
	public static function __callStatic($name, $arguments) 
    {
		return $this->__call($name,$arguments);
    }

	public function __get($id)
	{
		$lst = explode("_",$id);
		if($lst[1] == "model")
		{
			return $GLOBALS['app']->model($lst[0]);
		}
		elseif($lst[1] == "lib")
		{
			return $GLOBALS['app']->lib($lst[0]);
		}
		return $GLOBALS['app']->$id;
	}

	public function __isset($id)
	{
		return $this->__get($id);
	}
}

//PHPOK控制器，里面大部分函数将通过Global功能调用核心引挈
class phpok_control extends _init_auto
{
	function control()
	{
		parent::__construct();
	}
}


class phpok_model extends _init_auto
{
	# 继承control信息
	function model()
	{
		parent::__construct();
	}
}

class phpok_plugin extends _init_auto
{
	//默认父类，装载语言包
	function plugin()
	{
		parent::__construct();
		//读取语言ID
		$id = $this->plugin_id();
		$langs = "";
		if($this->site['lang'] && is_file($this->dir_root.'plugins/'.$id.'/langs/'.$this->site['lang'].'.xml'))
		{
			$langs = xml_to_array(file_get_contents($this->dir_root.'plugins/'.$id.'/langs/'.$this->site['lang'].'.xml'));
		}
		else
		{
			if(is_file($this->dir_root.'plugins/'.$id.'/lang_default.xml'))
			{
				$langs = xml_to_array(file_get_contents($this->dir_root.'plugins/'.$id.'/lang_default.xml'));
			}
		}
		if($langs && is_array($langs))
		{
			foreach($langs AS $key=>$value)
			{
				$langs[$key] = $this->ubb($value);
			}
			$GLOBALS['app']->lang = array_merge($langs,$this->lang);
			$this->assign('lang',$GLOBALS['app']->lang);
		}
	}

	final public function plugin_id()
	{
		$name = get_class($this);
		$lst = explode("_",$name);
		return $lst[1];
	}

	final public function plugin_info($id='')
	{
		if(!$id) $id = $this->plugin_id();
		$rs = $this->model('plugin')->get_one($id);
		if($rs['param'])
		{
			$rs['param'] = unserialize($rs['param']);
		}
		return $rs;
	}

	//存储插件配置
	final public function plugin_save($ext,$id="")
	{
		if(!$id) $id = $this->plugin_id();
		if(!$id) return false;
		$rs = $this->model('plugin')->get_one($id);
		if(!$rs) return false;
		$info = ($ext && is_array($ext)) ? serialize($ext) : '';
		$this->model('plugin')->update_param($id,$info);
	}
	
	//cf，控制接入点
	function load_after($cf,$param="")
	{
		//接入点不存在时，取消执行
		if(!$cf) return false;
		//接入点不符合要求时，取消执行
		if(!in_array($cf,$this->AP)) return false;
		//取得接入点
	}

	// 接入点列表
	function cf_list()
	{
		$list = array();
		$list["list-ok"] = "存储内容数据";
		$list["list-edit"] = "编辑内容数据";
		return $list;
	}
}

//安全注销全局变量
unset($_ENV, $_SERVER['MIBDIRS'],$_SERVER['MYSQL_HOME'],$_SERVER['OPENSSL_CONF'],$_SERVER['PHP_PEAR_SYSCONF_DIR'],$_SERVER['PHPRC'],$_SERVER['SystemRoot'],$_SERVER['COMSPEC'],$_SERVER['PATHEXT'], $_SERVER['WINDIR'],$_SERVER['PATH']);

$app = new _init_phpok();
include_once($app->dir_phpok."phpok_helper.php");
$app->init_autoload();
$app->init_site();
$app->init_view();
function init_app()
{
	return $GLOBALS['app'];
}
$app->action();