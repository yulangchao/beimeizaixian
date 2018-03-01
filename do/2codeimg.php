<?php
error_reporting(0);
include(dirname(__FILE__)."/../inc/phpqrcode.php");
$_GET['url'] || $_GET['url']='http://www.baidu.com';
$size>0 || $size=6;
if($_GET['W']){
	QRcode::png($_GET['url'],false,$_GET['W'],$_GET['W'],true);
}else{
	QRcode::png($_GET['url'],false,8,8,true);
}
?>