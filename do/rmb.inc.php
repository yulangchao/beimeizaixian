<?php


function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}rmb_infull WHERE numcode='$numcode'");
	if(!$rt){
		showerr('ϵͳ��û�����ĳ�ֵ�������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ���ֵ�ɹ���');
	}
	$db->query("UPDATE {$pre}rmb_infull SET ifpay='1' WHERE id='$rt[id]'");

	
	add_rmb($rt['uid'],$rt['money'],0,date('m��d��H:i ').'���߳�ֵ');

	refreshto("$webdb[www_url]/member/waprmb.php?job=list","��ϲ���ֵ�ɹ�",10);
}

?>