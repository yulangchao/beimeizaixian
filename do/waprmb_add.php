<?php
require("global.php");

if(in_array($banktype,array('alipay','wxpay'))){
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
}elseif($banktype){
	showerr("支付类型有误!");	
}elseif(!$lfjuid){
	showerr('请先登录!');
}

$lfjdb[money]=get_money($lfjuid);

require(ROOT_PATH."inc/waphead.php");
require(html("waprmb_add"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid,$webdb;
	
	$atc_moeny = intval($atc_moeny);
	if($atc_moeny<1){
		showerr("你输入的充值金额不能小于1元");
	}
	
	//支付宝的接口，结尾加参数无效
	$array[notify_url]=$webdb[www_url].'/do/rmb_alipay_notify.php';
	$array[return_url]=$webdb[www_url].'/do/rmb_alipay_return.php';
	
	//微信接口，结尾加参数无效
	$array[wx_notify_url]=$webdb[www_url].'/do/rmb_wxpay_end.php';
	$array[wx_return_url]=$webdb[www_url].'/do/rmb_wxpay_end.php?type=return';
	
	$array[money]=$atc_moeny;
	//$array[return_url]="$webdb[www]/do/rmb_add.php?banktype=$banktype&";
	$array[title]="为{$lfjid}在线充值";
	//$array[content]="为帐号:$lfjid,在线充值";
	$array[numcode]=strtolower(rands(10));

	switch($banktype){
		case 'alipay':
			$_banktype = '支付宝';
			$bank1 = $webdb['wapAlipay_id'];
			break;
		case 'tenpay':
			$_banktype = '财付通';
			$bank1 = $webdb['tenpay_id'];
			break;
		case 'wxpay':
			$_banktype = '微信支付';
			$bank1 = $webdb['wxpay_ID'];
			break;
	}

	$db->query("INSERT INTO `{$pre}rmb_infull` (`numcode` ,`money` ,  `posttime` , `uid` , `username` , `banktype` , `bank1` ) VALUES ( '$array[numcode]', '$array[money]', '$timestamp', '$lfjuid', '$lfjid', '$_banktype', '$bank1')");

	return $array;
}

?>