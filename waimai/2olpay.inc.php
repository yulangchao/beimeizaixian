<?php
!function_exists('html') && exit('ERR');

require_once(Mpath."inc/olpay.inc.php");

if(!$lfjuid){
	showerr('���ȵ�¼!');
}elseif(!$webdb['daili_receive']){
	showerr('ϵͳδ���ð��̼Ҵ��ջ���!');
}



if($pay_code){	//POST��API����ʱ
	$pay_code = str_replace('QIBO','=',$pay_code);	//������š�=�����׳�����
	list(,$ids)=explode("\t",mymd5($pay_code,'DE'));
}

$array = explode(',',$ids);
foreach($array  AS $key=>$value){
	$array[$key] = intval($value);
}
if(count($array)<1){
	showerr('URL��������!');
}
$totalemoney=0;
$title = '';
$ck_rmb = $lfjdb['rmb'];
$query = $db->query("SELECT A.title,A.uid,B.totalmoney,B.ifpay,B.cid,B.rmb,B.id FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id IN (".implode(',',$array).")");
while($rs = $db->fetch_array($query)){
	if($rs[ifpay]){	//���ɹ�֧������
		continue;
	}
	
	$rmb=0;
	//������������֧��
	if($webdb['rmb_pay'] && $rs['rmb']){
		//�����ʱ֧��������ֲ���Ļ�����ȡ�������ֿ��ʹ������֧����ȫ�
		if($webdb[rmb_late_pay]  && $ck_rmb<$rs['rmb']){
			$db->query("UPDATE {$_pre}join SET banktype='',rmb='0' WHERE id='$rs[id]'");
		}else{
			$rmb=$rs['rmb'];
			$ck_rmb -= $rs['rmb'];
		}
	}
	
	$totalemoney += $rs['totalmoney']-$rmb;	//��ȥ$rs['rmb']���֧������.
	$title .="$rs[title],";
}

if($totalemoney<=0){
	showerr('�����ܼ�Ϊ0,�޷�����֧��!');
}

if(!$webdb[yeepay_id]&&!$webdb[tenpay_id]&&!$webdb[wapAlipay_id]&&!$webdb[pay99_id]&&!$webdb[chinabank_id]&&!$webdb[paypal_id]){
	showerr("����֧��ʧ��,����Աû�����������ʺ�!");
}






?>