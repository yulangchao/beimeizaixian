<?php


function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}rmb_infull WHERE numcode='$numcode'");
	if(!$rt){
		showerr('系统中没有您的充值订单，无法完成充值！');
	}
	if($rt['ifpay'] == 1){
		showerr('该订单已经充值成功！');
	}
	$db->query("UPDATE {$pre}rmb_infull SET ifpay='1' WHERE id='$rt[id]'");

	
	add_rmb($rt['uid'],$rt['money'],0,date('m月d日H:i ').'在线充值');

	refreshto("$webdb[www_url]/member/waprmb.php?job=list","恭喜你充值成功",10);
}

?>