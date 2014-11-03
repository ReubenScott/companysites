<?php


define("APP_SET",true);
include_once 'includes/db_mysql.inc';

$db_mysql = new db_mysql();


$result = $db_mysql->get_one("select * from variable");

var_dump($result);

exit;

$db_url = 'mysqli://root:123456@localhost/phpok';
print_r(parse_url($db_url));


echo parse_url($db_url, PHP_URL_PATH);

exit;



$url = "http://www.baidu.com/";
include "includes/snoopy.inc";
$snoopy = new Snoopy;
$snoopy->fetch($url); //获取所有内容
// echo $snoopy->results; //显示结果

// $n=ereg_replace("background:url\(","background:url\(http://ip.aibx.net/",$n );
$html = str_replace("href=\"","href=\"".$url,$snoopy->results );

echo $html ;
// echo str_replace("src=\"","src=\"".$url,$html);
