<?php
require("global.php");

require("olpay.inc.php");

if(in_array($banktype,array('alipay','wxpay'))){	//POST 
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
	exit;
}

//$infodb[totalmoney]=number_format($infodb[totalmoney],2);

$pay_code = mymd5("$infodb[totalmoney]\t$id");

require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapolpay"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$infodb,$timestamp,$lfjuid,$lfjid,$pay_code,$Murl;

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
	$array[title]="购买:$infodb[title]";
	$array[content]=$pay_code;
	$array[numcode]=$numcode;
	return $array;
}


?>