<?php
/***********************************************************
	Filename:  phpok_helper.php
	Note	: 通用函数
	Version : 4.0
	Web		: mirror.wicp.net
	Author  : qinggan <qinggan@188.com>
	Update  : 2012-10-17 14:49
***********************************************************/

// 字符串截取，一个汉字等同于两个字母，去除HTML版
function phpok_cut($string, $length = 255, $dot = ""){
  return $GLOBALS ['app']->lib("string")->cut($string, $length, $dot);
}


function G($id){
  return $GLOBALS ['app']->get($id);
}


function str_rand($length = 10)// 随机字符，参数是长度
{
  if (! $length)
    return false;
  $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
  $rand_str = '';
  for($i = 0; $i < $length; ++ $i) {
    $rand_str .= $a [rand(0, 61)];
  }
  return $rand_str;
}

// 后台网址格式化，此函数仅限后台使用，在前台请使用static_url，支持静态化
function admin_url($ctrl, $func = "", $ext = ""){
  global $app;
  return $GLOBALS ['app']->url($ctrl, $func, $ext);
}

// 创建API_url
function api_url($ctrl, $func = "", $ext = "", $root = false){
  $url = '';
  if ($root)
    $url .= $GLOBALS ['app']->url;
  $url .= $GLOBALS ['app']->config ['api_file'];
  $url .= '?' . $GLOBALS ['app']->config ['ctrl_id'] . "=" . rawurlencode($ctrl);
  if ($func && $func != 'index') {
    $url .= '&' . $GLOBALS ['app']->config ['func_id'] . '=' . rawurlencode($func);
  }
  if ($ext) {
    if (substr($ext, 0, 1) != '&')
      $ext = '&' . $ext;
    $url .= $ext;
  }
  $url = str_replace('&amp;', '&', $url);
  return $url;
}


function list_url($id, $cate = "", $ext = ""){
  return listurl($id, $cate, $ext);
}


function listurl($id, $cate = "", $ext = ""){
  return $GLOBALS ['app']->url($id, $cate, $ext);
}


function msg_url($id, $ext = ""){
  return msgurl($id, $ext);
}


function msgurl($id, $ext = ""){
  return $GLOBALS ['app']->url($id, "", $ext);
}


function web_url($ctrl, $func = "", $ext = ""){
  if ($ctrl == "list")
    $ctrl = "project";
  if ($ctrl == "msg")
    $ctrl = "content";
  return $GLOBALS ['app']->url($ctrl, $func, $ext);
}


function content_url($id, $ext){
  return $GLOBALS ['app']->url("content", $id, $ext);
}


function site_url($ctrl, $func = "", $ext = ""){
  if ($ctrl == "list")
    $ctrl = "project";
  if ($ctrl == "msg")
    $ctrl = "content";
  return web_url($ctrl, $func, $ext);
}


function jsurl($ext = ""){
  $url = $GLOBALS ['app']->url . $GLOBALS ['app']->config ['www_file'] . "?";
  $url .= $GLOBALS ['app']->config ['ctrl_id'] . "=js&";
  if ($ext)
    $url .= $ext;
  return $url;
}

// Ajax提示信息封装
function json_exit($content, $status = false){
  $rs = array ();
  if (! $status && intval($content) == $content && $content) {
    if ($GLOBALS ['app']->lang [$GLOBALS ['app']->app_id] [$content]) {
      $content = $GLOBALS ['app']->lang [$GLOBALS ['app']->app_id] [$content];
    }
  }
  $rs ["status"] = $status ? "ok" : "error";
  $rs ["content"] = $content;
  exit($GLOBALS ['app']->lib("json")->encode($rs));
}


function json_ok($content, $format = true){
  if ($format && $GLOBALS ['app']->lang [$GLOBALS ['app']->app_id] [$content]) {
    $content = $GLOBALS ['app']->lang [$GLOBALS ['app']->app_id] [$content];
  }
  $rs = array (
      'status' => 'ok',
      'content' => $content 
  );
  exit($GLOBALS ['app']->lib("json")->encode($rs));
}

// Ajax 返回提示封装
function array_return($content, $status = false){
  $rs = array ();
  $rs ["status"] = $status ? "ok" : "error";
  $rs ["content"] = $content;
  return $rs;
}

// 自定义跳转
// tips，提示信息
// url，网址
// type，类型，支持
// notice：通知类文件
// ok：状态为对
// error：状态为错误
function error($tips = "", $url = "", $type = "notice", $time = 2){
  if (! $tips && ! ob_get_contents()) {
    header("Location:" . $url);
    exit();
  }
  if (! $url && ! $tips) {
    // 操作异常，传递空参数！
    phpok_exit("操作异常，没有传递任何有效参数！");
  }
  $GLOBALS ['app']->tpl->assign("url", $url);
  $GLOBALS ['app']->tpl->assign("tips", $tips);
  $GLOBALS ['app']->tpl->assign("type", $type);
  $GLOBALS ['app']->tpl->assign("time", $time);
  $GLOBALS ['app']->tpl->display("tips");
  // $GLOBALS['app']->tpl->path_change("");//禁用解析CSS
  // $GLOBALS['app']->tpl->display("framework/view/tips.html","abs-file");
  exit();
}

// 虚弹窗口提示用的
function error_open($tips, $type = "notice", $btn = ""){
  if (! $btn && ! $tips) {
    // 操作异常，传递空参数！
    phpok_exit("操作异常，没有传递任何有效参数！");
  }
  $GLOBALS ['app']->tpl->assign("tips", $tips);
  $GLOBALS ['app']->tpl->assign("type", $type);
  $GLOBALS ['app']->tpl->assign("btn", $btn);
  $GLOBALS ['app']->tpl->path_change(""); // 禁用解析CSS
  $GLOBALS ['app']->tpl->display($GLOBALS ['app']->dir_phpok . "view/tips_open.html", "abs-file");
  exit();
}

// 以下函数是考虑旧版本的应用
if (! function_exists("file_get_contents")) {


  function file_get_contents($file){
    if (! $file)
      return false;
    return implode("", file($file));
  }
}

if (! function_exists("file_put_contents")) {


  function file_put_contents($filename, $data = ""){
    if (! $filename)
      return false;
    $handle = fopen($filename, "wb");
    fwrite($handle, $data);
    fclose($handle);
    return true;
  }
}

// 加密，参数pass为明文密码
function password_create($pass){
  $password = md5($pass);
  $get_rand = substr($password, rand(0, 30), 2);
  $newpass = md5($pass . $get_rand) . ":" . $get_rand;
  return $newpass;
}

// 验证密码
// 参数pass是明文密码
// 参数password是加密后的密码
function password_check($pass, $password){
  if (! $password || ! $pass)
    return false;
  $list = explode(":", $password);
  if ($list [1]) {
    $chkpass = strlen($pass) != 32 ? md5($pass . $list [1]) : $pass;
    return $chkpass == $list [0] ? true : false;
  } else {
    $chkpass = strlen($pass) != 32 ? md5($pass) : $pass;
    return $chkpass == $password ? true : false;
  }
}

// 格式化获取扩展数据的内容
function ext_value($rs){
  if ($rs ["format"] == "int") {
    $val = $GLOBALS ['app']->get($rs ["identifier"], "int");
  } elseif ($rs ["format"] == "float") {
    $val = $GLOBALS ['app']->get($rs ["identifier"], "float");
  } elseif ($rs ["format"] == "html") {
    $val = $GLOBALS ['app']->get($rs ["identifier"], "html");
  } elseif ($rs ["format"] == "html_js") {
    $val = $GLOBALS ['app']->get($rs ["identifier"], "html_js");
  } elseif ($rs ["format"] == "time") {
    $val = $GLOBALS ['app']->get($rs ["identifier"]);
    if ($val)
      $val = strtotime($val);
  } elseif ($rs ["format"] == "text") {
    $val = $GLOBALS ['app']->get($rs ["identifier"], "html");
    if ($val)
      $val = strip_tags($val);
  } else {
    $val = $GLOBALS ['app']->get($rs ["identifier"]);
  }
  if (is_array($val)) {
    if ($rs ["form_type"] == "url") {
      $tmp = array (
          "default" => $val [0],
          "rewrite" => $val [1] 
      );
      $val = serialize($tmp);
    } else {
      $val = serialize($val);
    }
  }
  return $val;
}

// 内容图片本地化操作
function phpok_img_local($content){
  if (! $content)
    return false;
  preg_match_all("/<img\s*.+\s*src\s*=\s*[\"|']?\s*([^>\"'\s]+?)[\"|'| ]?.*[\/]?>/isU", $content, $matches);
  $list = $matches [1];
  if (! $list || count($list) < 1)
    return $content;
  $list = array_unique($list);
  $url_list = array ();
  $local_url = $GLOBALS ['app']->get_url();
  $local_url_length = strlen($local_url);
  $local_url_parse = parse_url($local_url);
  if (! $local_url_parse ["port"]) {
    $local_url_parse ["port"] = $local_url_parse ["scheme"] == "http" ? "80" : "443";
  }
  $pic_type_list = array (
      "gif",
      "png",
      "jpg",
      "jpeg" 
  );
  // 取得附件配置
  $cate_rs = $GLOBALS ['app']->model("res")->cate_default();
  // /
  $folder = $cate_rs ["root"];
  if ($cate_rs ["folder"] && $cate_rs ["folder"] != "/") {
    $folder .= date($cate_rs ["folder"], $GLOBALS ['app']->system_time);
  }
  if (! file_exists($folder)) {
    $GLOBALS ['app']->lib("file")->make($folder);
  }
  if (substr($folder, - 1) != "/")
    $folder .= "/";
  if (substr($folder, 0, 1) == "/")
    $folder = substr($folder, 1);
  if ($folder) {
    $folder = str_replace("//", "/", $folder);
  }
  $save_folder = $GLOBALS ['app']->dir_root . $folder;
  foreach ( $list as $key => $value ) {
    $value = trim($value);
    if (! $value)
      continue;
    $tmp = substr($value, 0, 7);
    $tmp = strtolower($tmp);
    if ($tmp == "file://" && $tmp != "http://" && $tmp != "https:/")
      continue;
    $tmp = parse_url($value);
    if (! $tmp ["port"]) {
      $tmp ["port"] = $tmp ["scheme"] == "http" ? "80" : "443";
    }
    if ($tmp ["host"] == $local_url_parse ["host"]) {
      // 判断网址是否符合要求
      if (substr($value, 0, $local_url_length) == $local_url) {
        $new_url = substr($value, $local_url_length);
      } else {
        $new_url = $value;
        if ($tmp ["port"] == $local_url_parse ['port']) {
          $del_url = $tmp ["scheme"] . "://" . $tmp ["host"];
          if ($tmp ["port"] != "80" && $tmp ["port"] != "443") {
            $del_url .= ":" . $tmp ["port"];
          }
          $del_url_length = strlen($del_url);
          if (substr($value, 0, $del_url_length) == $del_url) {
            $new_url = substr($value, $del_url_length);
          }
        }
      }
      $url_list [] = array (
          "old_url" => $value,
          "new_url" => $new_url 
      );
    } else {
      $tmp = explode(".", $value);
      $ext_id = count($tmp) - 1;
      $ext = $tmp [$ext_id];
      if (! $ext)
        $ext = "png";
      $ext = strtolower($ext);
      if (! in_array($ext, $pic_type_list))
        $ext = "png";
      $content_img = $GLOBALS ['app']->lib("html")->get_content($value);
      if (! $content_img)
        continue;
        // 文件名
      $filename = $GLOBALS ['app']->system_time . "_" . $key . "." . $ext;
      $GLOBALS ['app']->lib("file")->save_pic($content_img, $save_folder . $filename);
      unset($content_img);
      // 生成记录
      $array = array ();
      $array ["cate_id"] = $cate_rs ["id"];
      $array ["folder"] = $folder;
      $array ["name"] = $filename;
      $array ["ext"] = $ext;
      $array ["filename"] = $folder . $filename;
      $array ["addtime"] = $GLOBALS ['app']->system_time;
      $array ["title"] = basename($value);
      if ($GLOBALS ['app']->is_utf8($array ["title"])) {
        $array ["title"] = $GLOBALS ['app']->charset($array ["title"], "GBK", "UTF-8");
      }
      $array ["title"] = str_replace("." . $ext, "", $array ["title"]);
      $img_ext = getimagesize($save_folder . $filename);
      $my_ext = array (
          "width" => $img_ext [0],
          "height" => $img_ext [1] 
      );
      if (function_exists("exif_read_data")) {
        $ext_note = exif_read_data($save_folder . $filename, 0, true);
        if ($ext_note) {
          foreach ( $ext_note as $key => $value ) {
            $my_ext [$key] = $value;
          }
        }
      }
      $array ["attr"] = serialize($my_ext);
      $array ["session_id"] = $GLOBALS ['app']->session->sessid();
      $insert_id = $GLOBALS ['app']->model("res")->save($array);
      $ico = $GLOBALS ['app']->lib("gd")->thumb($array ["filename"], $insert_id);
      if (! $ico) {
        $ico = "images/filetype-large/" . $ext . ".jpg";
        if (! file_exists($ico)) {
          $ico = "images/filetype-large/unknow.jpg";
        }
      } else {
        $ico = $folder . $ico;
      }
      $tmp = array ();
      $tmp ["ico"] = $ico;
      $GLOBALS ['app']->model("res")->save($tmp, $insert_id);
      $url_list [] = array (
          "old_url" => $value,
          "new_url" => $folder . $filename 
      );
    }
  }
  foreach ( $url_list as $key => $value ) {
    $content = str_replace($value ["old_url"], $value ["new_url"], $content);
  }
  return $content;
}

// Post 密码属性的设置
function ext_password_format($val, $content, $type){
  if (! $val || ! $type || $type == "default")
    return $val;
  if (! $content) {
    return $type == "md5" ? md5($val) : $val;
  }
  if ($type == "show") {
    if (strlen($val) == strlen($content) && substr($val, 0, 1) == substr($content, 0, 1) && substr($content, - 1) == substr($val, - 1)) {
      return $content;
    }
    return $val;
  }
  return $val == $content ? $val : md5($val);
}

// 存储扩展字段
function ext_save($myid, $is_add = false, $save_id = ""){
  $GLOBALS ['app']->model("ext");
  $GLOBALS ['app']->model("fields");
  if ($is_add) {
    $idstring = $_SESSION [$myid];
    if (! $idstring)
      return false;
    $tmplist = $GLOBALS ['app']->model("fields")->get_list($idstring);
    if (! $tmplist)
      return false;
    foreach ( $tmplist as $key => $value ) {
      $val = ext_value($value);
      $array = array ();
      $array ["module"] = $save_id ? $save_id : $myid;
      $array ["title"] = $value ['title'];
      $array ["identifier"] = $value ['identifier'];
      $array ["field_type"] = $value ['field_type'];
      $array ["note"] = $value ['note'];
      $array ["form_type"] = $value ['form_type'];
      $array ["form_style"] = $value ["form_style"];
      $array ["format"] = $value ["format"];
      $array ["content"] = $value ["content"];
      $array ["taxis"] = $value ["taxis"];
      $array ["ext"] = "";
      if ($value ["ext"] && $value ["content"] && $val) {
        $tmp = unserialize($value ["ext"]);
        if ($value ["form_type"] == "password") {
          $val = ext_password_format($val, $value ["content"], $tmp ["password_type"]);
        }
        $array ["ext"] = serialize($tmp);
      }
      $insert_id = $GLOBALS ['app']->model("ext")->ext_save($array);
      $GLOBALS ['app']->model("ext")->extc_save($val, $insert_id);
    }
    $_SESSION [$myid] = "";
  } else {
    $tmplist = $GLOBALS ['app']->model("ext")->ext_all($myid);
    if (! $tmplist)
      return false;
    foreach ( $tmplist as $key => $value ) {
      $val = ext_value($value);
      if ($value ["form_type"] == "password") {
        $tmp = $value ["ext"] ? unserialize($value ["ext"]) : "";
        if (! $tmp) {
          $tmp = array ();
          $tmp ["password_type"] = "default";
        }
        $val = ext_password_format($val, $value ["content"], $tmp ["password_type"]);
      }
      $GLOBALS ['app']->model("ext")->extc_save($val, $value ["id"]);
    }
  }
  return true;
}

// 删除扩展字段
function ext_delete($myid){
  return $GLOBALS ['app']->model("ext")->del($myid);
}

// 上传按钮
// input_id，表单ID，用于存储内容
// upload_type，上传类型，支持：picture file video all 空值时调用 picture
// js，回调函数
// injs，是否加载swfupload的JS，如果一个页面有多个上传按钮，此项请设为false
// multi，值为true表示多文件上传，为false表示单文件上传
function phpok_upload_btn($input_id, $upload_type = "", $js = "", $injs = false, $cateid = 0, $multi = false){
  if (! $input_id)
    return false;
  if (! $upload_type)
    $upload_type = "picture";
  $config = $GLOBALS ['app']->model("res")->type_list();
  $type = $config [$upload_type] ["swfupload"];
  $type_name = $config [$upload_type] ["name"];
  unset($config);
  $html = '';
  if ($injs) {
    $html = '<script type="text/javascript" src="js/swfupload/swfupload.js"></script>';
  }
  $html .= '<table cellpadding="0" cellspacing="0"><tr>';
  $html .= '<td><div id="' . $input_id . '_spanButtonPlaceHolder"></div></td>';
  $html .= '<td style="display:none;"><input id="' . $input_id . '_btnCancel" type="button" value="取消上传" onclick="' . $input_id . '_swfu.cancelQueue();" disabled="disabled" /></td>';
  $html .= '</tr>';
  $html .= '<tr><td><div id="' . $input_id . '_progress"></div></td></tr>';
  $html .= '</table>';
  $html .= '' . "\n";
  $html .= '<script type="text/javascript">' . "\n";
  $html .= 'function phpok_swfupload_success_' . $input_id . '(file,serverData)' . "\n";
  $html .= '{' . "\n";
  $html .= '	var rs = $.parseJSON(serverData);' . "\n";
  $html .= '	var progress = new FileProgress(file, this.customSettings.progressTarget);' . "\n";
  $html .= '	progress.setComplete();' . "\n";
  $html .= '	progress.setStatus("上传完成！");' . "\n";
  $html .= '	progress.toggleCancel(false);' . "\n";
  if ($js) {
    $html .= '	' . $js . '(rs);' . "\n";
  } else {
    $html .= '	$("#' . $input_id . '").val(rs.filename);' . "\n";
  }
  $html .= '}' . "\n";
  $html .= 'function phpok_swfupload_complete_' . $input_id . '(file)' . "\n";
  $html .= '{' . "\n";
  $html .= '	if (this.getStats().files_queued === 0) {' . "\n";
  $html .= '		document.getElementById(this.customSettings.cancelButtonId).disabled = true;' . "\n";
  $html .= '		$("#' . $input_id . '_progress").hide().html("");' . "\n";
  $html .= '	}' . "\n";
  $html .= '}' . "\n";
  $html .= 'var settings_' . $input_id . ' = {' . "\n";
  $html .= 'flash_url : "js/swfupload/swfupload.swf",' . "\n";
  $res_url = $GLOBALS ['app']->url("upload");
  if ($cateid) {
    $res_url .= "&cateid=" . $cateid;
  }
  $html .= 'upload_url: "' . $res_url . '",' . "\n";
  $html .= 'post_params: {"' . $GLOBALS ['app']->session->config ["session_id"] . '" : "' . $GLOBALS ['app']->session->sessid() . '"},' . "\n";
  $html .= 'file_size_limit : "200 MB",' . "\n";
  $html .= 'file_types : "' . $type . '",' . "\n";
  $html .= 'file_types_description : "' . $type_name . '",' . "\n";
  $html .= 'file_upload_limit : 100,' . "\n";
  $html .= 'file_queue_limit : 0,' . "\n";
  $html .= 'button_window_mode: "transparent",' . "\n";
  $html .= 'custom_settings : {' . "\n";
  $html .= '	progressTarget : "' . $input_id . '_progress",' . "\n";
  $html .= '	cancelButtonId : "' . $input_id . '_btnCancel"' . "\n";
  $html .= '},' . "\n";
  $html .= 'debug: false,' . "\n";
  $html .= 'button_image_url: "images/swfupload.png",' . "\n";
  $html .= 'button_placeholder_id : "' . $input_id . '_spanButtonPlaceHolder",' . "\n";
  $html .= 'button_width: "92",' . "\n";
  $html .= 'button_height: "23",' . "\n";
  if ($multi) {
    $html .= 'button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,' . "\n";
  } else {
    $html .= 'button_action: SWFUpload.BUTTON_ACTION.SELECT_FILE,' . "\n";
  }
  $html .= 'file_queued_handler : fileQueued,' . "\n";
  $html .= 'file_queue_error_handler : fileQueueError,' . "\n";
  $html .= 'file_dialog_complete_handler : fileDialogComplete,' . "\n";
  $html .= 'upload_start_handler : uploadStart,' . "\n";
  $html .= 'upload_progress_handler : uploadProgress,' . "\n";
  $html .= 'upload_error_handler : uploadError,' . "\n";
  $html .= 'upload_success_handler : phpok_swfupload_success_' . $input_id . ',' . "\n";
  $html .= 'upload_complete_handler : phpok_swfupload_complete_' . $input_id . ',' . "\n";
  $html .= 'queue_complete_handler : queueComplete' . "\n";
  $html .= '};' . "\n";
  $html .= $input_id . '_swfu = new SWFUpload(settings_' . $input_id . ');' . "\n";
  $html .= '</script>' . "\n";
  return $html;
}

// 加载可视化编辑器
function phpok_editor($rs){
  $default_rs = array (
      "identifier" => "content",
      "content" => "",
      "width" => "96%",
      "height" => "300",
      "is_code" => false,
      "is_read" => false,
      "is_float" => false 
  );
  if ($rs && is_string($rs)) {
    $rs = array (
        "content" => $rs 
    );
  }
  $rs ["width"] = 800;
  $rs = ($rs && is_array($rs)) ? array_merge($default_rs, $rs) : $default_rs;
  $rs ["height"] = intval($rs ["height"]);
  if (! $rs ["height"])
    $rs ["height"] = "320";
  $GLOBALS ['app']->assign("edit_rs", $rs);
  $GLOBALS ['app']->assign("edit_baseurl", $GLOBALS ['app']->get_url());
  // 读取附件类型
  $config_file = $GLOBALS ['app']->dir_root . "data/xml/filetype.xml";
  $config = array ();
  if (file_exists($config_file)) {
    $config = xml_to_array(file_get_contents($config_file));
    $btn_file_list = array ();
    if (! $config)
      $config = array ();
    foreach ( $config as $key => $value ) {
      if ($key != "picture" && $key != "video") {
        $btn_file_list [$key] = $value;
      }
    }
    $GLOBALS ['app']->assign("btn_file_list", $btn_file_list);
  }
  $file = $GLOBALS ['app']->dir_phpok . "form/html/editor_from_admin.html";
  $content = $GLOBALS ['app']->fetch($file, 'abs-file');
  return $content;
}

// 产品价格格式化
// val，值
// currency_id，当前值对应的货币ID
// show_id，要显示的货币ID
function price_format($val, $currency_id, $show_id = 0){
  // 当显示为后台时
  if ($GLOBALS ['app']->app_id == 'admin' && ! $show_id) {
    $show_id = $currency_id;
  } else {
    if (! $show_id)
      $show_id = $GLOBALS ['app']->site ['currency_id'];
    if (! $show_id)
      $show_id = $currency_id;
  }
  if (! $GLOBALS ['app']->cache_data ['currency']) {
    $GLOBALS ['app']->cache_data ['currency'] = $GLOBALS ['app']->model('currency')->get_list('id');
  }
  if (! $GLOBALS ['app']->cache_data ['currency'] || ! $GLOBALS ['app']->cache_data ['currency'] [$currency_id] || ! $GLOBALS ['app']->cache_data ['currency'] [$show_id]) {
    return false;
  }
  if ($val == '')
    $val = '0';
  $rs = $GLOBALS ['app']->cache_data ['currency'] [$show_id];
  if ($show_id != $currency_id) {
    $old_rs = $GLOBALS ['app']->cache_data ['currency'] [$currency_id];
    $val = ($val / $old_rs ['val']) * $rs ['val'];
  }
  $val = number_format($val, 2, ".", "");
  $string = $rs ['symbol_left'] . ' ' . $val . ' ' . $rs ['symbol_right'];
  return $string;
}


function price_format_val($val, $currency_id, $show_id = 0){
  if ($GLOBALS ['app']->app_id == 'admin' && ! $show_id) {
    $show_id = $currency_id;
  } else {
    if (! $show_id)
      $show_id == $GLOBALS ['app']->site ['currency_id'];
    if (! $show_id)
      $show_id = $currency_id;
  }
  if (! $GLOBALS ['app']->cache_data ['currency']) {
    $GLOBALS ['app']->cache_data ['currency'] = $GLOBALS ['app']->model('currency')->get_list('id');
  }
  if (! $GLOBALS ['app']->cache_data ['currency'] || ! $GLOBALS ['app']->cache_data ['currency'] [$currency_id] || ! $GLOBALS ['app']->cache_data ['currency'] [$show_id]) {
    return false;
  }
  if ($val == '')
    $val = '0';
  $rs = $GLOBALS ['app']->cache_data ['currency'] [$show_id];
  if ($show_id != $currency_id) {
    $old_rs = $GLOBALS ['app']->cache_data ['currency'] [$currency_id];
    $val = ($val / $old_rs ['val']) * $rs ['val'];
  }
  $val = number_format($val, 2, ".", "");
  return $val;
}


function content_format($value, $type = "ext"){
  if ($value ['form_type'] == "cate" && $value ["content"]) {
    $tmplist = $GLOBALS ['app']->model("list")->catelist($value ["content"]);
    $value ["content"] = $tmplist [$value ["content"]];
  } elseif ($value ["form_type"] == "upload" && $value ["content"]) {
    if (is_array($value ["content"])) {
      if ($value ["content"] ["id"]) {
        $tmp = $value ["content"] ["id"];
      } else {
        $tmp = array ();
        foreach ( $value ["content"] as $k => $v ) {
          $tmp [] = $v ["id"];
        }
        $tmp = implode(",", $tmp);
      }
      $value ["content"] = $tmp;
    }
    $tmplist = $GLOBALS ['app']->model("res")->reslist($value ["content"]);
    $ext = $value ["ext"] ? unserialize($value ["ext"]) : array (
        "is_multiple" => false 
    );
    if ($ext ["is_multiple"]) {
      $tmp = explode(",", $value ["content"]);
      foreach ( $tmp as $kk => $vv ) {
        $tmp [$kk] = $tmplist [$vv];
      }
      $value ["content"] = $tmp;
    } else {
      $value ["content"] = $tmplist [$value ["content"]];
    }
  }
  return $value ["content"];
}


function phpok_filesize($size, $is_file = true){
  if ($is_file)
    $size = file_exists($size) ? filesize($size) : 0;
  if (! $size)
    return "0 KB";
  return $GLOBALS ['app']->lib("trans")->num_format($size);
}


function phpok_res_type($type = ""){
  $config_file = $GLOBALS ['app']->dir_root . "data/xml/filetype.xml";
  $config = array ();
  if (file_exists($config_file)) {
    $config = xml_to_array(file_get_contents($config_file));
  }
  if ($type && $config [$type]) {
    return $config [$type];
  } else {
    $config;
  }
}

// 删除指定的缓存文件
function delete_cache($ext = ""){
  $GLOBALS ['app']->cache->status(true);
  $GLOBALS ['app']->cache->delete_keywords(0, $ext);
  return true;
}

// 会员登录
function user_login($id, $pass = ""){
  global $app;
  $GLOBALS ['app']->model("user");
  $rs = $GLOBALS ['app']->user_model->get_one($id);
  if (! $rs || ! $rs ["status"])
    return $GLOBALS ['app']->lang ['global'] [5001];
  if ($pass && ! password_check($pass, $rs ["pass"]))
    return $GLOBALS ['app']->lang ['global'] [5002];
  $_SESSION ["user_id"] = $id;
  $_SESSION ["user_rs"] = $rs;
  $_SESSION ["user_name"] = $rs ["user"];
  // 更新购物车属性
  // $GLOBALS['app']->model('cart')->cart_id(session_id(),$id);
  return "ok";
}

// 取得扩展表里的信息
function phpok_ext_info($module, $extc = true){
  global $app;
  $GLOBALS ['app']->model("ext");
  $rslist = $GLOBALS ['app']->ext_model->ext_all($module, true);
  if (! $rslist)
    return false;
  $rs = array ();
  foreach ( $rslist as $key => $value ) {
    $rs [$value ["identifier"]] = content_format($value);
  }
  return $rs;
}

// 取得主题扩展内容信息
function phpok_ext_list($mid, $tid = 0){
  if (! $mid || ! $tid)
    return false;
  global $app;
  $GLOBALS ['app']->model("module");
  $rslist = $GLOBALS ['app']->module_model->fields_all($mid, "identifier");
  if (! $rslist)
    return false;
  $idlist = array_keys($rslist);
  $GLOBALS ['app']->model("list");
  $infolist = $GLOBALS ['app']->list_model->get_ext_list($mid, $tid);
  if (! $infolist)
    $infolist = array ();
  foreach ( $idlist as $key => $value ) {
    foreach ( $infolist as $k => $v ) {
      unset($v ["site_id"], $v ["project_id"], $v ["cate_id"], $v ["id"]);
      if ($v [$value]) {
        $tmp = $rslist [$value];
        $tmp ["content"] = $v [$value];
        $v [$value] = content_format($tmp);
      }
      $infolist [$k] = $v;
    }
  }
  return $infolist;
}

// 取得表单选项信息
function phpok_opt($id, $ext = ""){
  global $app;
  $GLOBALS ['app']->model("opt");
  $cacheId = $GLOBALS ['app']->cache->key($id . "_" . $ext, $GLOBALS ['app']->config_site ["id"], "opt");
  $content = $GLOBALS ['app']->cache->read($cacheId);
  if ($content) {
    return $content;
  }
  $group_rs = $GLOBALS ['app']->opt_model->group_one($id);
  if (! $group_rs) {
    return false;
  }
  $condition = "group_id='" . $group_rs ["id"] . "'";
  if ($ext) {
    $ext_condition = "group_id='" . $group_rs ["id"] . "' AND val='" . $ext . "'";
    $ext_rs = $GLOBALS ['app']->opt_model->opt_one_condition($ext_condition);
    if ($ext_rs) {
      $condition .= " AND parent_id='" . $ext_rs ["id"] . "'";
    }
  } else {
    $condition .= " AND parent_id='0'";
  }
  $all = $GLOBALS ['app']->opt_model->opt_all($condition);
  if (! $all)
    return false;
  $GLOBALS ['app']->cache->write($cacheId, $all);
  return $all;
}

// 删除核心缓存文件
function syscache_delete($id){
  global $app ;
  $GLOBALS ['app']->lib("file");
  $file = $GLOBALS ['app']->dir_root . "data/cache/" . $id . ".php";
  $GLOBALS ['app']->file_lib->rm($file);
  return true;
}


function phpok_decode($string, $id = ""){
  if (! $string)
    return false;
  $string = str_replace("'", '"', $string);
  $t = unserialize($string);
  if (! $id) {
    return $t;
  }
  if ($id == "url") {
    global $app ;
    return $t [$GLOBALS ['app']->config ["url_type"]];
  } else {
    return $t [$id];
  }
}

// WEB前台通用模板，如果您的程序比较复杂，请自己写Head
function tpl_head($array = array()){
  if ($array ['html5'] == 'false') {
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
    $html .= '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
    $html .= '<head>' . "\n\t" . '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n\t";
  } else {
    $html = '<!DOCTYPE html>' . "\n";
    $html .= '<html>' . "\n";
    $html .= '<head>' . "\n\t" . '<meta charset="utf-8" />' . "\n\t";
  }
  $html .= '<meta http-equiv="expires" content="' . gmdate('D, j M Y H:i:s', $GLOBALS ['app']->time) . ' gmt" />' . "\n\t";
  $html .= '<title>';
  if ($array ['title'])
    $html .= $array ['title'] . ' - ';
  $html .= $GLOBALS ['app']->site ['title'];
  if ($GLOBALS ['app']->site ['seo'] ['title'])
    $html .= ' - ' . $GLOBALS ['app']->site ['seo'] ['title'];
  $html .= '</title>' . "\n\t";
  $html .= '<base href="' . $GLOBALS ['app']->url . '" />' . "\n\t";
  $html .= '<meta http-equiv="X-UA-Compatible" content="edge" />' . "\n\t";
  if ($GLOBALS ['app']->license == 'LGPL') {
    $html .= '<meta name="author" content="phpok.com" />' . "\n\t";
    $html .= '<meta http-equiv="phpok-license" content="LGPL" />' . "\n\t";
  } else {
    $html .= '<meta http-equiv="license" content="' . $GLOBALS ['app']->license . '" />' . "\n\t";
    $html .= '<meta http-equiv="license-date" content="' . $GLOBALS ['app']->license_date . '" />' . "\n\t";
    $html .= '<meta http-equiv="license-domain" content="' . $GLOBALS ['app']->license_site . '" />' . "\n\t";
  }
  if ($GLOBALS ['app']->site ['seo'] ['keywords']) {
    $html .= '<meta name="keywords" content="' . $GLOBALS ['app']->site ['seo'] ['keywords'] . '" />';
    $html .= "\n\t";
  }
  if ($GLOBALS ['app']->site ['seo'] ['description']) {
    $html .= '<meta name="description" content="' . $GLOBALS ['app']->site ['seo'] ['description'] . '" />';
    $html .= "\n\t";
  }
  if ($GLOBALS ['app']->site ['meta']) {
    $t = explode("\n", $GLOBALS ['app']->site ['meta']);
    foreach ( $t as $key => $value ) {
      $html .= $value . "\n\t";
    }
  }
  $jsurl = $GLOBALS ['app']->url . $GLOBALS ['app']->config ["www_file"] . "?" . $GLOBALS ['app']->config ['ctrl_id'] . "=js";
  $html .= '<script type="text/javascript" src="' . $jsurl . '" charset="utf-8"></script>';
  // 加载css
  if ($array ["css"]) {
    $array ['css'] = explode(",", $array ['css']);
    $cssfile = "";
    foreach ( (is_array($array ['css']) ? $array ['css'] : array ()) as $key => $value ) {
      $value = trim($value);
      if (! $value)
        continue;
      if (is_file($GLOBALS ['app']->dir_root . $value)) {
        $html .= "\n\t" . '<link rel="stylesheet" type="text/css" href="' . $GLOBALS ['app']->url . $value . '" />';
      } else {
        $value = basename($value);
        if (is_file($GLOBALS ['app']->dir_root . "css/" . $value)) {
          $html .= "\n\t" . '<link rel="stylesheet" type="text/css" href="' . $GLOBALS ['app']->url . "css/" . $value . '" />';
        }
      }
    }
  }
  // 加载js
  if ($array ['js']) {
    $array ['js'] = explode(",", $array ['js']);
    $cssfile = "";
    foreach ( (is_array($array ['js']) ? $array ['js'] : array ()) as $key => $value ) {
      $value = trim($value);
      if (! $value)
        continue;
      if (is_file($GLOBALS ['app']->dir_root . $value)) {
        $html .= "\n\t" . '<script type="text/javascript" src="' . $GLOBALS ['app']->url . $value . '" charset="UTF-8"></script>';
      } else {
        if (is_file($GLOBALS ['app']->dir_root . "css/" . $value)) {
          $html .= "\n\t" . '<script type="text/javascript" src="' . $GLOBALS ['app']->url . "js/" . $value . '" charset="UTF-8"></script>';
        }
      }
    }
  }
  // 加载其他参数
  $html .= $GLOBALS ['app']->site ['meta'];
  if ($array ["ext"])
    $html .= $array ["ext"];
  if (! $array ['close'] || $array ["close"] != 'false') {
    $html .= $array ['symbol'] . "\n" . '</head>';
  }
  $html .= "\n";
  return $html;
}

// 表单生成器
function form_edit($id, $content = "", $type = "text", $attr = "", $return = 'echo'){
  if (! $id)
    return false;
  $array = array (
      "identifier" => $id,
      "form_type" => $type,
      "content" => $content 
  );
  if ($attr) {
    parse_str($attr, $list);
    if ($list)
      $array = array_merge($list, $array);
  }
  $rs = $GLOBALS ['app']->lib('form')->format($array);
  if ($return == 'array')
    return $rs;
  return $rs ['html'];
}

// 取得授权时间
function license_date(){
  if ($GLOBALS ['app']->license_site == '.phpok.com')
    return '2005-' . date("Y", $GLOBALS ['app']->time);
  $date_start = substr($GLOBALS ['app']->license_date, 0, 4);
  $date_end = date("Y", $GLOBALS ['app']->time);
  if ($date_start >= $date_end)
    return $date_end;
  return $date_start . "-" . $date_end;
}

// 日志存储，可用于调试
function phpok_log($info = ''){
  if (! $info)
    $info = '执行 {phpok}/' . $GLOBALS ['app']->app_id . '/' . $GLOBALS ['app']->ctrl . '_control.php 方法：' . $GLOBALS ['app']->func . '_f';
  $info = addslashes($info);
  $time = date("Y-m-d H:i:s", $GLOBALS ['app']->time);
  $sql = "INSERT INTO " . $GLOBALS ['app']->db->prefix . "log(`title`,`addtime`,`app`,`action`,`app_id`) VALUES('" . $info . "','" . $time . "','" . $GLOBALS ['app']->ctrl . "','" . $GLOBALS ['app']->func . "','" . $GLOBALS ['app']->app_id . "')";
  $GLOBALS ['app']->db->query($sql);
}

// 邮箱合法性验证
// 参考代码写法：http://www.oschina.net/code/snippet_6981_5438
function phpok_check_email($email){
  $atIndex = strrpos($email, "@");
  if (is_bool($atIndex) && ! $atIndex)
    return false;
  $domain = substr($email, $atIndex + 1);
  $local = substr($email, 0, $atIndex);
  $localLen = strlen($local);
  $domainLen = strlen($domain);
  if ($localLen < 1 || $localLen > 64)
    return false;
  if ($domainLen < 1 || $domainLen > 255)
    return false;
  if ($local [0] == '.' || $local [$localLen - 1] == '.')
    return false;
  if (preg_match('/\\.\\./', $local))
    return false;
  if (! preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
    return false;
  if (preg_match('/\\.\\./', $domain))
    return false;
  if (! preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
    if (! preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
      return false;
  }
  // if (!(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) return false;
  return true;
}

// 汉字转拼音
function chinese2pingyin($title){
  if (! $title)
    return false;
  $GLOBALS ['app']->lib("pingyin")->path = $GLOBALS ['app']->dir_phpok . "libs/pingyin.qdb";
  $py = iconv("UTF-8", "GBK", $title);
  $py = $GLOBALS ['app']->lib("pingyin")->ChineseToPinyin($py);
  if (! $py)
    return false;
  $py = strtolower($py);
  $safe_string = "abcdefghijklmnopqrstuvwxyz0123456789-_";
  $str_array = str_split($py);
  $safe_array = str_split($safe_string);
  $string = "";
  foreach ( $str_array as $key => $value ) {
    if (in_array($value, $safe_array)) {
      $string .= $value;
    } else {
      $string .= "-";
    }
  }
  return $string;
}


function user_group($gid){
  return $GLOBALS ['app']->model('usergroup')->get_one($gid);
}

// 详细页分页
function pageurl($pageurl, $pageid = 1){
  if ($pageid < 2)
    return $pageurl;
  $page = $GLOBALS ['app']->config ['pageid'] ? $GLOBALS ['app']->config ['pageid'] : 'pageid';
  $pageurl .= strpos($pageurl, '?') === false ? '?' . $page . '=' . $pageid : '&' . $page . '=' . $pageid;
  return $pageurl;
}

// 自定义表单中涉及到的内容获取
function opt_rslist($type = 'default', $group_id = 0, $info = ''){
  // 当类型为默认时
  if ($type == 'default' && $info) {
    $list = explode("\n", $info);
    $rslist = "";
    $i = 0;
    foreach ( $list as $key => $value ) {
      if ($value && trim($value)) {
        $value = trim($value);
        $rslist [$i] ['val'] = $value;
        $rslist [$i] ['title'] = $value;
        $i ++;
      }
    }
    return $rslist;
  }
  
  // 表单选项
  if ($type == "opt") {
    return $GLOBALS ['app']->model('opt')->opt_all("group_id=" . $group_id);
  }
  // 读子项目信息
  if ($type == 'project') {
    $tmplist = $GLOBALS ['app']->model('project')->project_sonlist($group_id);
    if (! $tmplist)
      return false;
    $rslist = '';
    foreach ( $tmplist as $key => $value ) {
      $tmp = array (
          "val" => $value ['id'],
          "title" => $value ['title'] 
      );
      $rslist [] = $tmp;
    }
    return $rslist;
  }
  // 读主题列表信息
  if ($type == 'title') {
    $tmplist = $GLOBALS ['app']->model("list")->title_list($group_id);
    if (! $tmplist)
      return false;
    $rslist = '';
    foreach ( $tmplist as $key => $value ) {
      $tmp = array (
          "val" => $value ['id'],
          "title" => $value ['title'] 
      );
      $rslist [] = $tmp;
    }
    return $rslist;
  }
  // 读子分类信息
  if ($type == 'cate') {
    $tmplist = $GLOBALS ['app']->model('cate')->catelist_sonlist($group_id, false, 0);
    if (! $tmplist)
      return false;
    $rslist = '';
    foreach ( $tmplist as $key => $value ) {
      $tmp = array (
          "val" => $value ['id'],
          "title" => $value ['title'] 
      );
      $rslist [] = $tmp;
    }
    return $rslist;
  }
  return false;
}

// 兼容老版本写法，下面的这些写法将会在5.x版本中消除
function sys_cutstring($string, $length = 255, $dot = ""){
  return phpok_cut($string, $length, $dot);
}


