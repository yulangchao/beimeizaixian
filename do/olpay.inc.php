<?php


function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('ϵͳ��û�����ĳ�ֵ�������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ���ֵ�ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$floor = floor($rt[money]/10);

	$num=$rt[money]*$webdb[alipay_scale] + $floor*$webdb[alipay_give_scale];
	
	add_user($rt[uid],$num,'���߳�ֵ');

	refreshto("$webdb[www_url]/","��ϲ���ֵ�ɹ�",10);
}

?>