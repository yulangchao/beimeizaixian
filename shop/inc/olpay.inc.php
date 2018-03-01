<?php



function olpay_end($numcode,$_pay_code=''){
	global $db,$_pre,$webdb,$banktype,$pay_code,$lfjuid,$lfjdb;
	
	if($_pay_code){	//这一步微信支付会用到
		$pay_code=$_pay_code;
		$pay_code = str_replace('QIBO','=',$pay_code);	//这个符号“=”容易出问题
	}

	if(!$pay_code){
		showerr("数据有误!!!");
	}
	
	list($atc_moeny,$ids)=explode("\t",mymd5($pay_code,'DE'));

	$array = explode(',',$ids);
	foreach($array AS $value){
		$value = intval($value);
		$rs = $db->get_one("SELECT * FROM `{$_pre}join` WHERE id='$value'");
		if($rs[ifpay]==1){
			continue;
		}
		
		//开启了余额付款，并且选择延迟付余额的
		if($webdb['rmb_pay'] && $webdb[rmb_late_pay] && $rs[rmb]){
			if($lfjdb[rmb]>=$rs[rmb]){
				add_rmb($lfjuid,-$rs[rmb],0,"购买商品余额支付:{$rs[title]}...");
				add_rmb($rs[cuid],$rs[rmb],0,"销售商品，余额支付部分:{$rs[title]}...");
				$lfjdb[rmb] -= $rs[rmb];
			}else{	//防止客户中途把余额消费光的情况，导致余额不足扣款
				add_rmb($lfjuid,$rs[totalmoney]-$rs[rmb],0,"购买商品失败,返款:{$rs[title]}...");
				$db->query("UPDATE {$_pre}join SET ifpay='0',banktype='$banktype' WHERE id='$value'");
				continue;
			}
		}
		
		$db->query("UPDATE {$_pre}join SET ifpay='1',banktype='$banktype' WHERE id='$value'");
		
		$d = explode(',',$rs['products']);
		foreach($d AS $v){
			list($pid,$pnum)=explode('=',$v);
			shop_storage_change($pid,$pnum);	//货存量的调整
			shop_give_money($pid,$lfjuid);	//买商品赠送积分
		}

		add_rmb($rs[cuid],$rs[totalmoney],0,"销售商品，在线付款:{$rs[title]}...");
		
		//付款后,短信或邮件通知商家
		paymoney_send_seller_msg($rs[cuid],$rs);

	}
	refreshto("./wapindex.php","恭喜你订单付款成功!",60);
}

?>