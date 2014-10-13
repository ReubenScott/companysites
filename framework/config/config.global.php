<?php
/***********************************************************
	Filename: {phpok}config/config.global.php
	Note	: 全站全局参数
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年9月3日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
# 启用调试
$config["debug"] = true;

# 启用压缩
$config["gzip"] = true;


# 取得控制器的ID
$config["ctrl_id"] = "c";

# 取得应用方法的ID
$config["func_id"] = "f";

//后台安全入口
$config["admin_file"] = "admin.php";
//网站首页，这里一般不修改，除非首页要另外制作，才改名
$config["www_file"] = "index.php";
//API接口
$config["api_file"] = "api.php";

//是否需要全局验证登录
$config["is_login"] = false;

//是否需要管理员登录
$config["is_admin"] = true;

//自动加载
$config['is_mobile'] = false;

# 显示验证码
$config["is_vcode"] = true;

# 每页显示数量
$config["psize"] = 30;

# 分页ID
$config["pageid"] = "pageid";

# 时区调节，仅限PHP5以上支持
$config["timezone"] = "Asia/Shanghai";

# 时间调节
$config["timetuning"] = "0";

//自动加载的Model类信息
$config["autoload_model"] = "";
//自动加载的Lib信息
$config["autoload_lib"] = "trans,file";

//保留单词，配置此项后，这些id=参数将直接走app文件而不走数据库进行判断
$config["reserved"] = "index,project,content,download,login,register,cart,order,usercp,js,ajax,inp,search,post,payment";


//JS缓存时间，这里默认是1天，建议设置使用1天
$config['js_cache_time'] = 86400;

//JS-MD5加密类，ArtDialog弹出窗
$config['autoload_js']  = "jquery.md5.js,jquery.artdialog.js,jquery.artdialog_ext.js";
//PHPOK公共JS加载类
$config['autoload_js'] .= ',jquery.phpok.js,global.js';
//jQuery表单插件，支持ajaxSubmit提交
$config["autoload_js"] .= ",jquery.form.min.js";

//手工加载js
//admin表示app_id，global表示全局
//附件上传JS，日期控件
$config['load_js']['admin']['global'] = "swfupload/swfupload.js,dyndatetime/jquery.dyndatetime.pack.js,dyndatetime/lang/calendar-utf8.js";
//可视化编辑器
$config['load_js']['admin']['global'].= ",ueditor/ueditor.config.js,ueditor/ueditor.all.min.js,ueditor/lang/zh-cn/zh-cn.js";
//高亮代码
$config['load_js']['admin']['global'].= ",codemirror/codemirror.js";


# SESSION存储方式
$config["engine"]["session"]["file"] = "file";
$config["engine"]["session"]["id"] = "PHPSESSION";
$config["engine"]["session"]["timeout"] = 3600;
$config["engine"]["session"]["path"] = ROOT."data/session/";
//当SESSION存储方式为数据库时，执行此配置
$config["engine"]["session"]["db_user"] = $config["db"]["user"];
$config["engine"]["session"]["db_pass"] = $config["db"]["pass"];
$config["engine"]["session"]["db_data"] = $config["db"]["data"];
$config["engine"]["session"]["db_table"] = $config["db"]["prefix"]."session";

// Cache引挈
$config["engine"]["cache"]["file"] = "default";
$config["engine"]["cache"]["status"] = true;
$config["engine"]["cache"]["timeout"] = 3600;
$config["engine"]["cache"]["folder"] = ROOT."data/cache/";//在Memcache缓存中，此项用于存储KEY
$config["engine"]["cache"]["server"] = "localhost"; //Memcache缓存服务器
$config["engine"]["cache"]["port"] = "11211"; //Memcache缓存端口
$config["engine"]["cache"]["prefix"] = "phpok_";//缓存Key前缀，用于区分不同
