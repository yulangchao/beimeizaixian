<?php



function olpay_end($numcode,$_pay_code=''){
	global $db,$_pre,$webdb,$banktype,$pay_code,$lfjuid,$lfjdb;
	
	if($_pay_code){	//��һ��΢��֧�����õ�
		$pay_code=$_pay_code;
		$pay_code = str_replace('QIBO','=',$pay_code);	//������š�=�����׳�����
	}

	if(!$pay_code){
		showerr("��������!!!");
	}
	
	list($atc_moeny,$ids)=explode("\t",mymd5($pay_code,'DE'));

	$array = explode(',',$ids);
	foreach($array AS $value){
		$value = intval($value);
		$rs = $db->get_one("SELECT * FROM `{$_pre}join` WHERE id='$value'");
		if($rs[ifpay]==1){
			continue;
		}
		
		//�������������ѡ���ӳٸ�����
		if($webdb['rmb_pay'] && $webdb[rmb_late_pay] && $rs[rmb]){
			if($lfjdb[rmb]>=$rs[rmb]){
				add_rmb($lfjuid,-$rs[rmb],0,"������Ʒ���֧��:{$rs[title]}...");
				add_rmb($rs[cuid],$rs[rmb],0,"������Ʒ�����֧������:{$rs[title]}...");
				$lfjdb[rmb] -= $rs[rmb];
			}else{	//��ֹ�ͻ���;��������ѹ���������������ۿ�
				add_rmb($lfjuid,$rs[totalmoney]-$rs[rmb],0,"������Ʒʧ��,����:{$rs[title]}...");
				$db->query("UPDATE {$_pre}join SET ifpay='0',banktype='$banktype' WHERE id='$value'");
				continue;
			}
		}
		
		$db->query("UPDATE {$_pre}join SET ifpay='1',banktype='$banktype' WHERE id='$value'");
		
		$d = explode(',',$rs['products']);
		foreach($d AS $v){
			list($pid,$pnum)=explode('=',$v);
			shop_storage_change($pid,$pnum);	//�������ĵ���
			shop_give_money($pid,$lfjuid);	//����Ʒ���ͻ���
		}

		add_rmb($rs[cuid],$rs[totalmoney],0,"������Ʒ�����߸���:{$rs[title]}...");
		
		//�����,���Ż��ʼ�֪ͨ�̼�
		paymoney_send_seller_msg($rs[cuid],$rs);

	}
	refreshto("./wapindex.php","��ϲ�㶩������ɹ�!",60);
}

?>