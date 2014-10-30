<?php


define("APP_SET",true);
include_once 'framework/phpok_tpl_helper.php';

echo client_ip();  exit;


$url = "http://www.baidu.com/";

include "includes/snoopy.inc";

$snoopy = new Snoopy;

$snoopy->fetch($url); //获取所有内容

// echo $snoopy->results; //显示结果

// $n=ereg_replace("background:url\(","background:url\(http://ip.aibx.net/",$n );

$html = str_replace("href=\"","href=\"".$url,$snoopy->results );

echo $html ;
// echo str_replace("src=\"","src=\"".$url,$html);
