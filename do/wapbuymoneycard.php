<?php
require("global.php");

if(in_array($banktype,array('alipay','wxpay'))){
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
}elseif($banktype){
	showerr("支付类型有误!");	
}

$lfjdb[money]=get_money($lfjuid);

$webdb[alipay_give_scale] = intval($webdb[alipay_give_scale]);

require(ROOT_PATH."inc/waphead.php");
require(html("wapbuymoneycard"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid,$webdb;
	
	$atc_moeny = intval($atc_moeny);
	if($atc_moeny<1){
		showerr("你输入的充值金额不能小于1");
	}
	
	//支付宝的返回地址不能带参数
	$array[notify_url]=$webdb[www_url].'/do/alipay_notify.php';
	$array[return_url]=$webdb[www_url].'/do/alipay_return.php';

    //微信支付的返回地址也是不能带参数，第二个是支付成功后的跳转地址可以带
	$array[wx_notify_url]=$webdb[www_url].'/do/money_wxpay_end.php';
	$array[wx_return_url]=$webdb[www_url].'/do/money_wxpay_end.php?type=return';
	
	$array[money]=$atc_moeny;
	//$array[return_url]="$webdb[www]/do/buymoneycard.php?banktype=$banktype&";
	$array[title]="购买{$webdb[MoneyName]},为{$lfjid}在线充值";
	//$array[content]="为帐号:$lfjid,在线付款购买{$webdb[MoneyName]}";
	$array[numcode]=strtolower(rands(10));

	$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	return $array;
}



?>