<?php
require("global.php");

//当前程序是站长代商家收贷款,而买家直接付款给商家的程序是olpay.php
require("2olpay.inc.php");

if(in_array($banktype,array('alipay','wxpay'))){	//POST与API返回时
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
	exit;
}



$pay_code = mymd5("$totalemoney\t$ids");

//以下两项主要是模板那里要用到
$infodb = array('totalmoney'=>$totalemoney);
$array = array(
		'yeepay_id'=>$webdb['yeepay_id'],
		'tenpay_id'=>$webdb['tenpay_id'],
		'wapAlipay_id'=>$webdb['wapAlipay_id'],
		'paypal_id'=>$webdb['paypal_id'],
		'chinabank_id'=>$webdb['chinabank_id']);

require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapolpay"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $webdb,$banktype,$timestamp,$lfjuid,$lfjid,$pay_code,$Murl,$title;

	if(!$pay_code){
		showerr("数据有误!");
	}
	list($atc_moeny,$ids)=explode("\t",mymd5($pay_code,'DE'));
	$ids = intval($ids);
	
	if($atc_moeny<0.01){
		showerr("总额不能小于1分钱!");
	}
	
	$numcode=rand(1,10000);
	while(strlen($numcode)<10){
		$numcode="0$numcode";
	}
	$array[notify_url]=$Murl.'/2alipay_notify.php';
	$array[return_url]=$Murl.'/2alipay_return.php';
	$array[money]=$atc_moeny;
	$pay_code = str_replace('=','QIBO',$pay_code);	//这个符号“=”容易出问题
	//$array[return_url]="$Murl/olpay2.php?banktype=$banktype&pay_code=$pay_code&";
	$array[title]="购买商品:".get_word($title,30);
	$array[content]=$pay_code;
	$array[numcode]=$numcode;
	return $array;
}


?>