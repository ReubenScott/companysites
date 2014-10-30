<?php

$url = "http://ip.aibx.net/";

include "includes/snoopy.inc";

$snoopy = new Snoopy;

$snoopy->fetch($url); //获取所有内容

// echo $snoopy->results; //显示结果


// $n=ereg_replace(“href=”",”href=”http://www.rjkfw.com/”,$snoopy->results );
// echo ereg_replace(“src=”",”src=”http://www.rjkfw.com/”,$n);

$n= str_replace("href=\"","href=\"http://ip.aibx.net/",$snoopy->results );
// $n=ereg_replace("background:url\(","background:url\(http://ip.aibx.net/",$n );


echo str_replace("src=\"","src=\"http://ip.aibx.net/",$n);
