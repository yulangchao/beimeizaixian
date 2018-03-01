<?php
!function_exists('html') && exit('ERR');



if( $webdb['daili_receive'] ){	//管理员代收货款
	if($webdb['rmb_pay'] && $lfjdb['rmb']>0){	//余额付款
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=waprmb_pay.php?id=$id'>";		
	}else{	//没启用余额付款或余额为0时,在线支付.
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=wapolpay2.php?ids=$id'>";
	}
	exit;
}


if($pay_code){	//POST与API返回时
	$pay_code = str_replace('QIBO','=',$pay_code);	//这个符号“=”容易出问题
	list(,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
}

//获取订单信息

$infodb = $db->get_one("SELECT A.title,A.price,A.uid,B.totalmoney,B.ifpay,B.fid,B.cid FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id='$id'");

if(!$infodb){
	showerr('资料不存在!');
}elseif($infodb[ifpay]){
	showerr('此订单已经支付过了!');
}



$rs = $db->get_one("SELECT * FROM `{$pre}purse` WHERE uid='$infodb[uid]'");
$array = unserialize($rs[config]);


if(!$array[yeepay_id]&&!$array[tenpay_id]&&!$array[wapAlipay_id]&&!$array[pay99_id]&&!$array[chinabank_id]){
	refreshto("./","在线支付失败,商户没有设置网银帐号!",10);
}

//易宝支付
$webdb[yeepay_id] = $array[yeepay_id];
$webdb[yeepay_key] = $array[yeepay_key];

//财付通
$webdb[tenpay_id] = $array[tenpay_id];
$webdb[tenpay_key] = $array[tenpay_key];

//网银在线
$webdb[chinabank_id] = $array[chinabank_id];
$webdb[chinabank_key] = $array[chinabank_key];

//支付宝
$webdb[wapAlipay_id] = $array[wapAlipay_id];
$webdb[alipay_key] = $array[alipay_key];
$webdb[wapAlipay_partner] = $array[wapAlipay_partner];
$webdb[alipay_service] = $array[alipay_service];
$webdb[alipay_transport] = $array[alipay_transport];

//快钱
$webdb[pay99_id] = $array[pay99_id];
$webdb[pay99_key] = $array[pay99_key];

//贝宝
$webdb[paypal_id] = $array[paypal_id];
$webdb[paypal_key] = $array[paypal_key];
$webdb[paypal_type] = $array[paypal_type];


function olpay_end($numcode){
	global $db,$_pre,$webdb,$banktype,$pay_code,$lfjuid,$infodb;

	if(!$pay_code){
		showerr("数据有误!!");
	}
	
	list($atc_moeny,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);

	$db->query("UPDATE {$_pre}join SET ifpay='1',banktype='$banktype' WHERE id='$id'");
	
	
	$rs = $db->get_one("SELECT * FROM `{$_pre}join` WHERE id='$id'");

	count_join($rs[cid]);	//统计报名人数

	//付款后,短信或邮件通知买家消费密码
	paymoney_send_msg($lfjuid,$rs);
	
	//付款后,短信或邮件通知商家
	paymoney_send_seller_msg($rs[cuid],$rs);

	refreshto("./","恭喜你订单付款成功!",60);
}

?>