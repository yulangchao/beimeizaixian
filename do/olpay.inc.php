<?php


function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('系统中没有您的充值订单，无法完成充值！');
	}
	if($rt['ifpay'] == 1){
		showerr('该订单已经充值成功！');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$floor = floor($rt[money]/10);

	$num=$rt[money]*$webdb[alipay_scale] + $floor*$webdb[alipay_give_scale];
	
	add_user($rt[uid],$num,'在线充值');

	refreshto("$webdb[www_url]/","恭喜你充值成功",10);
}

?>