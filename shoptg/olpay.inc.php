<?php
!function_exists('html') && exit('ERR');



if( $webdb['daili_receive'] ){	//����Ա���ջ���
	if($webdb['rmb_pay'] && $lfjdb['rmb']>0){	//����
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=waprmb_pay.php?id=$id'>";		
	}else{	//û������������Ϊ0ʱ,����֧��.
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=wapolpay2.php?ids=$id'>";
	}
	exit;
}


if($pay_code){	//POST��API����ʱ
	$pay_code = str_replace('QIBO','=',$pay_code);	//������š�=�����׳�����
	list(,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
}

//��ȡ������Ϣ

$infodb = $db->get_one("SELECT A.title,A.price,A.uid,B.totalmoney,B.ifpay,B.fid,B.cid FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id='$id'");

if(!$infodb){
	showerr('���ϲ�����!');
}elseif($infodb[ifpay]){
	showerr('�˶����Ѿ�֧������!');
}



$rs = $db->get_one("SELECT * FROM `{$pre}purse` WHERE uid='$infodb[uid]'");
$array = unserialize($rs[config]);


if(!$array[yeepay_id]&&!$array[tenpay_id]&&!$array[wapAlipay_id]&&!$array[pay99_id]&&!$array[chinabank_id]){
	refreshto("./","����֧��ʧ��,�̻�û�����������ʺ�!",10);
}

//�ױ�֧��
$webdb[yeepay_id] = $array[yeepay_id];
$webdb[yeepay_key] = $array[yeepay_key];

//�Ƹ�ͨ
$webdb[tenpay_id] = $array[tenpay_id];
$webdb[tenpay_key] = $array[tenpay_key];

//��������
$webdb[chinabank_id] = $array[chinabank_id];
$webdb[chinabank_key] = $array[chinabank_key];

//֧����
$webdb[wapAlipay_id] = $array[wapAlipay_id];
$webdb[alipay_key] = $array[alipay_key];
$webdb[wapAlipay_partner] = $array[wapAlipay_partner];
$webdb[alipay_service] = $array[alipay_service];
$webdb[alipay_transport] = $array[alipay_transport];

//��Ǯ
$webdb[pay99_id] = $array[pay99_id];
$webdb[pay99_key] = $array[pay99_key];

//����
$webdb[paypal_id] = $array[paypal_id];
$webdb[paypal_key] = $array[paypal_key];
$webdb[paypal_type] = $array[paypal_type];


function olpay_end($numcode){
	global $db,$_pre,$webdb,$banktype,$pay_code,$lfjuid,$infodb;

	if(!$pay_code){
		showerr("��������!!");
	}
	
	list($atc_moeny,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);

	$db->query("UPDATE {$_pre}join SET ifpay='1',banktype='$banktype' WHERE id='$id'");
	
	
	$rs = $db->get_one("SELECT * FROM `{$_pre}join` WHERE id='$id'");

	count_join($rs[cid]);	//ͳ�Ʊ�������

	//�����,���Ż��ʼ�֪ͨ�����������
	paymoney_send_msg($lfjuid,$rs);
	
	//�����,���Ż��ʼ�֪ͨ�̼�
	paymoney_send_seller_msg($rs[cuid],$rs);

	refreshto("./","��ϲ�㶩������ɹ�!",60);
}

?>