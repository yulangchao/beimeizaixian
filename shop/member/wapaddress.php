<?php
require_once("global.php");

if($action=='del'){
	checkrid($rid);
	$db->query("DELETE FROM {$_pre}address WHERE rid=$rid");
	refreshto($FROMURL,'',0);
	
}elseif($job=='edit'){
	checkrid($rid);
	$rsdb=$db->get_one("SELECT * FROM {$_pre}address WHERE rid='$rid' AND uid='$lfjuid'");

}elseif($job=='list'){
	$rows=15;
	if(!$page){
		$page=1;
	}
	$min=($page-1)*$rows;
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}address WHERE uid=$lfjuid ORDER BY rid DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$totalNum=$RS['FOUND_ROWS()'];
	$showpage=getpage("","","?job=$job",$rows,$totalNum);
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;	
	}	
}elseif($action=="postnew"){
	$order_address = filtrate($order_address);
	$order_username = filtrate($order_username);
	$order_phone = filtrate($order_phone);
	$order_mobphone = filtrate($order_mobphone);
	$order_email = filtrate($order_email);
	$order_qq = filtrate($order_qq);
	$order_postcode = filtrate($order_postcode);
	$db->query("INSERT INTO `{$_pre}address` (`uid` ,`order_username` ,`order_phone` ,`order_mobphone` ,`order_email` ,`order_qq` ,`order_postcode` ,`order_address` ) VALUES ('$lfjuid', '$order_username', '$order_phone', '$order_mobphone', '$order_email', '$order_qq', '$order_postcode', '$order_address')");
	refreshto("?job=list",'',0);
}elseif($action=="edit"){
	checkrid($rid);
	$order_address = filtrate($order_address);
	$order_username = filtrate($order_username);
	$order_phone = filtrate($order_phone);
	$order_mobphone = filtrate($order_mobphone);
	$order_email = filtrate($order_email);
	$order_qq = filtrate($order_qq);
	$order_postcode = filtrate($order_postcode);
	$db->query("UPDATE `{$_pre}address` SET order_username='$order_username',order_phone='$order_phone',order_email='$order_email',order_qq='$order_qq',order_postcode='$order_postcode',order_address='$order_address',order_mobphone='$order_mobphone' WHERE rid='$rid'");
	refreshto("?job=list",'',0);
}

require(ROOT_PATH."member/waphead.php");
require(dirname(__FILE__)."/"."template/wapaddress.htm");
require(ROOT_PATH."member/wapfoot.php");

function checkrid($rid){
	global $lfjuid,$_pre,$db;
	if(!$rid){
		showerr("操作有误!不存在被操作的收货地址");
	}else{
		$rsdb=$db->get_one("SELECT * FROM {$_pre}address WHERE rid='$rid' AND uid='$lfjuid'");
		if(!$rsdb){
			showerr("操作有误!不存在被操作的收货地址");
		}
	}
}
?>