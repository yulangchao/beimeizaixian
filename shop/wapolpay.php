<?php
require("global.php");

//���ļ��Ǵ�������Ʒ�ĸ�������Ʒͬʱ����Ĵ����ļ���olpay2.php


require("olpay.inc.php");
 

if(in_array($banktype,array('alipay','tenpay'))){	//POST ʱ
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
	exit;
}


//վ�������˴��տ����������������Ҹö�����ѡ���˲�������
if($webdb['daili_receive'] && $webdb['rmb_pay'] && $infodb['rmb'] ){
	//$webdb[rmb_late_pay]!=1ʱ��Ԥ�ȿۿ�����жϵ�ǰ��Ա�ʺ�����������ʺ�����ʱ����ȡ�����ֱ�����߸�ȫ��
	if(!$webdb[rmb_late_pay] || $lfjdb[rmb]>=$infodb['rmb']){
		$infodb[totalmoney] = $infodb[totalmoney] - $infodb['rmb'];	//��ȥ$infodb['rmb']���֧������.
	}	
}

$pay_code = mymd5("$infodb[totalmoney]\t$id");



require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapolpay"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $webdb,$banktype,$infodb,$timestamp,$lfjuid,$lfjid,$pay_code,$Murl;

	if(!$pay_code){
		showerr("��������!");
	}
	list($atc_moeny,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
	if($atc_moeny<0.01){
		showerr("�ܶ��С��1��Ǯ!");
	}
	
	$numcode=$id;
	while(strlen($numcode)<10){
		$numcode="0$numcode";
	}
	
	$array[notify_url]=$Murl.'/alipay_notify.php';
	$array[return_url]=$Murl.'/alipay_return.php';

	$array[money]=$atc_moeny;
	$pay_code = str_replace('=','QIBO',$pay_code);	//������š�=�����׳�����
	//$array[return_url]="$Murl/olpay.php?banktype=$banktype&pay_code=$pay_code&";
	$array[title]="������Ʒ:$infodb[title] ...";
	$array[content]=$pay_code;
	$array[numcode]=$numcode;
	return $array;
}



?>