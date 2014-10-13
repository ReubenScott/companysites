<?php
/***********************************************************
	Filename: ajax/www_cart_add.php
	Note	: 添加到购物车中，这里仅限session
	Version : 4.0
	Web		: www.phpok.com
	Author  : qinggan <qinggan@188.com>
	Update  : 2013年7月9日
***********************************************************/
if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");}
/*if(!$_SESSION["user_id"])
{
	exit("请先登录再购买");
}*/
$id = $this->get("id");
if(!$id)
{
	exit("error");
}
$cartlist = $_SESSION["cart"];
if(!$cartlist) $cartlist = array();
$is_add = true;
$cart_total = 0;
foreach($cartlist AS $key=>$value)
{
	if($value["id"] == $id)
	{
		$value["num"]++;
		$is_add = false;
	}
	$cartlist[$key] = $value;
	$cart_total += $value["num"];
}
if($is_add)
{
	$this->model("list");
	$rs = $this->list_model->get_one($id);
	$cartlist[] = array("id"=>$id,"num"=>1,"title"=>$rs["title"],"identifier"=>$rs["identifier"]);
	$cart_total++;
}
$_SESSION["cart"] = $cartlist;
$_SESSION["cart_total"] = $cart_total;
exit("ok");
?>