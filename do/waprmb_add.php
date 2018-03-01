<?php
require("global.php");

if(in_array($banktype,array('alipay','wxpay'))){
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
}elseif($banktype){
	showerr("֧����������!");	
}elseif(!$lfjuid){
	showerr('���ȵ�¼!');
}

$lfjdb[money]=get_money($lfjuid);

require(ROOT_PATH."inc/waphead.php");
require(html("waprmb_add"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid,$webdb;
	
	$atc_moeny = intval($atc_moeny);
	if($atc_moeny<1){
		showerr("������ĳ�ֵ����С��1Ԫ");
	}
	
	//֧�����Ľӿڣ���β�Ӳ�����Ч
	$array[notify_url]=$webdb[www_url].'/do/rmb_alipay_notify.php';
	$array[return_url]=$webdb[www_url].'/do/rmb_alipay_return.php';
	
	//΢�Žӿڣ���β�Ӳ�����Ч
	$array[wx_notify_url]=$webdb[www_url].'/do/rmb_wxpay_end.php';
	$array[wx_return_url]=$webdb[www_url].'/do/rmb_wxpay_end.php?type=return';
	
	$array[money]=$atc_moeny;
	//$array[return_url]="$webdb[www]/do/rmb_add.php?banktype=$banktype&";
	$array[title]="Ϊ{$lfjid}���߳�ֵ";
	//$array[content]="Ϊ�ʺ�:$lfjid,���߳�ֵ";
	$array[numcode]=strtolower(rands(10));

	switch($banktype){
		case 'alipay':
			$_banktype = '֧����';
			$bank1 = $webdb['wapAlipay_id'];
			break;
		case 'tenpay':
			$_banktype = '�Ƹ�ͨ';
			$bank1 = $webdb['tenpay_id'];
			break;
		case 'wxpay':
			$_banktype = '΢��֧��';
			$bank1 = $webdb['wxpay_ID'];
			break;
	}

	$db->query("INSERT INTO `{$pre}rmb_infull` (`numcode` ,`money` ,  `posttime` , `uid` , `username` , `banktype` , `bank1` ) VALUES ( '$array[numcode]', '$array[money]', '$timestamp', '$lfjuid', '$lfjid', '$_banktype', '$bank1')");

	return $array;
}

?>