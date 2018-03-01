<?php
require("global.php");

//本文件是处理单个商品的付款，多个商品同时付款的处理文件是olpay2.php


require("olpay.inc.php");
 

if(in_array($banktype,array('alipay','tenpay'))){	//POST 时
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
	exit;
}


//站长启用了代收款，并且启用了余额付款，并且该订单曾选择了部分余额付款
if($webdb['daili_receive'] && $webdb['rmb_pay'] && $infodb['rmb'] ){
	//$webdb[rmb_late_pay]!=1时，预先扣款，则不需判断当前会员帐号里的余额。否则帐号余额不足时，就取消余额付款，直接在线付全款
	if(!$webdb[rmb_late_pay] || $lfjdb[rmb]>=$infodb['rmb']){
		$infodb[totalmoney] = $infodb[totalmoney] - $infodb['rmb'];	//除去$infodb['rmb']余额支付部分.
	}	
}

$pay_code = mymd5("$infodb[totalmoney]\t$id");



require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapolpay"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $webdb,$banktype,$infodb,$timestamp,$lfjuid,$lfjid,$pay_code,$Murl;

	if(!$pay_code){
		showerr("数据有误!");
	}
	list($atc_moeny,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
	if($atc_moeny<0.01){
		showerr("总额不能小于1分钱!");
	}
	
	$numcode=$id;
	while(strlen($numcode)<10){
		$numcode="0$numcode";
	}
	
	$array[notify_url]=$Murl.'/alipay_notify.php';
	$array[return_url]=$Murl.'/alipay_return.php';

	$array[money]=$atc_moeny;
	$pay_code = str_replace('=','QIBO',$pay_code);	//这个符号“=”容易出问题
	//$array[return_url]="$Murl/olpay.php?banktype=$banktype&pay_code=$pay_code&";
	$array[title]="购买商品:$infodb[title] ...";
	$array[content]=$pay_code;
	$array[numcode]=$numcode;
	return $array;
}



?>