<?php
!function_exists('html') && exit('ERR');

if(!$lfjuid){
	showerr('���ȵ�¼!');
}elseif(!$webdb['daili_receive']){
	showerr('ϵͳδ���ð��̼Ҵ��ջ���!');
}


if($pay_code){	//POST��API����ʱ
	$pay_code = str_replace('QIBO','=',$pay_code);	//������š�=�����׳�����
	list(,$ids)=explode("\t",mymd5($pay_code,'DE'));
	$ids = intval($ids);
}


$infodb = $db->get_one("SELECT B.*,A.title FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id = '$ids' ");

if(!$infodb){
	showerr("��������!!!");
}elseif($infodb[ifpay]){
	showerr('�ö����Ѿ�֧������!');
}

if($infodb['rmb'] && $lfjdb['rmb']>=$infodb['rmb']){
	$rmb=$infodb['rmb'];
}else{
	$rmb=0;
	//������0������û���;�������,��ֹ��οۿ�,��������������ٷ���
	$db->query("UPDATE {$_pre}join SET banktype='',rmb='0' WHERE id='$ids'");
}
$totalemoney = $infodb['totalmoney']-$rmb;	//��ȥ$infodb['rmb']���֧������.
$title = $infodb['title'];


if($totalemoney<=0){
	showerr('�����ܼ�Ϊ0,�޷�����֧��!');
}

if(!$webdb[yeepay_id]&&!$webdb[tenpay_id]&&!$webdb[wapAlipay_id]&&!$webdb[pay99_id]&&!$webdb[chinabank_id]&&!$webdb[paypal_id]){
	showerr("����֧��ʧ��,����Աû�����������ʺ�!");
}

function olpay_end($numcode){
	global $db,$_pre,$webdb,$banktype,$lfjuid,$lfjdb,$ids,$infodb;

	$ifpay = 1;		

	if($infodb[rmb] && $lfjdb[rmb]<$infodb[rmb]){	//��ֹ�ͻ���;��������ѹ�����

		$ifpay = 0;
		add_rmb($lfjuid,$infodb[totalmoney]-$infodb[rmb],0,"������Ʒʧ��,����:{$infodb[title]}...");
		$db->query("UPDATE {$_pre}join SET ifpay='0',banktype='$banktype' WHERE id='$ids'");

	}else{

		if($infodb[ifquit]){	//��Կ��˿�����,��ʹ�����֧��ʱ,$infodb[rmb]Ϊ0
			add_rmb($lfjuid,-$infodb[rmb],$infodb[totalmoney],"����� ����:$infodb[title]");
		}else{
			add_rmb($infodb[cuid],$infodb[totalmoney],0,"����:$infodb[title]");
			if($infodb[rmb]){	//ʹ�����֧�����ִ���ʱ
				add_rmb($lfjuid,-$infodb[rmb],0,"����:$infodb[title]");
				add_rmb($infodb[cuid],$infodb[rmb],0,"����:$infodb[title]");
			}
		}
	}
	
	$db->query("UPDATE {$_pre}join SET ifpay='$ifpay',banktype='$banktype' WHERE id='$ids'");

	count_join($infodb[cid]);	//ͳ�Ʊ�������
 

	//�����,���Ż��ʼ�֪ͨ�����������
	paymoney_send_msg($lfjuid,$infodb);
	
	//�����,���Ż��ʼ�֪ͨ�̼�
	paymoney_send_seller_msg($infodb[cuid],$infodb);
 
	refreshto("./","��ϲ�㶩������ɹ�!",60);
}

?>