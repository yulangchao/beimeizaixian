<?php
require("global.php");

//������Ǵ��ջ���Ļ�������ʹ�ñ�����
//��ǰ�����ǽ��ܶ����Ʒ��ͬʱ���������Ʒ�ĸ����ļ���olpay.php

require("2olpay.inc.php");

if(in_array($banktype,array('alipay','wxpay'))){	//POST��API����ʱ
	include(ROOT_PATH."inc/wapolpay/{$banktype}.php");
	exit;
}



$pay_code = mymd5("$totalemoney\t$ids");

//����������Ҫ��ģ������Ҫ�õ�
$infodb = array('totalmoney'=>$totalemoney);
$array = array(
		//'yeepay_id'=>$webdb['yeepay_id'],
		'wxpay_id'=>$webdb['wxpay_ID'],
		'wapAlipay_id'=>$webdb['wapAlipay_id'],
		//'paypal_id'=>$webdb['paypal_id'],
		//'chinabank_id'=>$webdb['chinabank_id']
		);

require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapolpay"));
require(ROOT_PATH."inc/wapfoot.php");


function olpay_send(){
	global $webdb,$banktype,$timestamp,$lfjuid,$lfjid,$pay_code,$Murl,$title;

	if(!$pay_code){
		showerr("��������!");
	}
	list($atc_moeny,$ids)=explode("\t",mymd5($pay_code,'DE'));
	
	if($atc_moeny<0.01){
		showerr("�ܶ��С��1��Ǯ!");
	}
	
	$numcode=rand(1,10000);
	while(strlen($numcode)<10){
		$numcode="0$numcode";
	}
	
	//֧�����ӿ�URL���ܼӲ���
	$array[notify_url]=$Murl.'/2alipay_notify.php';
	$array[return_url]=$Murl.'/2alipay_return.php';

	$array[wx_notify_url]=$Murl.'/2wxpay_end.php'; //΢��֧���Ľӿڣ���β�Ӳ�����Ч
	$array[wx_return_url]=$Murl.'/2wxpay_end.php?type=return';
	
	$array[money]=$atc_moeny;
	$pay_code = str_replace('=','QIBO',$pay_code);	//������š�=�����׳�����
	//$array[return_url]="$Murl/olpay2.php?banktype=$banktype&pay_code=$pay_code&";
	$array[title]="������Ʒ:".get_word($title,30);
	$array[content]=$pay_code;	//֧������
	$array[other]=$pay_code;	//΢��֧����
	$array[numcode]=$numcode;
	return $array;
}


?>