<?php
require("global.php");

if(in_array($banktype,array('alipay','wxpay'))){
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
}elseif($banktype){
	showerr("֧����������!");	
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
		showerr("������ĳ�ֵ����С��1");
	}
	
	//֧�����ķ��ص�ַ���ܴ�����
	$array[notify_url]=$webdb[www_url].'/do/alipay_notify.php';
	$array[return_url]=$webdb[www_url].'/do/alipay_return.php';

    //΢��֧���ķ��ص�ַҲ�ǲ��ܴ��������ڶ�����֧���ɹ������ת��ַ���Դ�
	$array[wx_notify_url]=$webdb[www_url].'/do/money_wxpay_end.php';
	$array[wx_return_url]=$webdb[www_url].'/do/money_wxpay_end.php?type=return';
	
	$array[money]=$atc_moeny;
	//$array[return_url]="$webdb[www]/do/buymoneycard.php?banktype=$banktype&";
	$array[title]="����{$webdb[MoneyName]},Ϊ{$lfjid}���߳�ֵ";
	//$array[content]="Ϊ�ʺ�:$lfjid,���߸����{$webdb[MoneyName]}";
	$array[numcode]=strtolower(rands(10));

	$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	return $array;
}



?>