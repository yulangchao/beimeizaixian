<?php
!function_exists('html') && exit('ERR');

if($pay_code){	//POST与API返回时
	$pay_code = str_replace('QIBO','=',$pay_code);	//这个符号“=”容易出问题
	list(,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
}

//获取订单信息

$infodb = $db->get_one("SELECT A.title,A.uid,B.totalmoney,B.ifpay,B.cid,B.rmb FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id='$id'");

if(!$infodb){
	showerr('订单不存在!');
}elseif($infodb[ifpay]){
	showerr('此订单已经支付过了!');
}

if($webdb['daili_receive']){	//管理员代收货款
	//$banktype选择某种在线付款方式，$webdb['rmb_pay']余额付款开关，$from_rmbpay从余额付款页跳转过来的，$infodb[rmb]支付过部分余额的单，$lfjdb[rmb]有余额
	//当管理员代收货款$webdb['daili_receive']=1，并且后台启用了余额支付$webdb['rmb_pay']=1，并且会员有余额时$lfjdb[rmb]>0，才跳转到余额支付页面。
	//若会员强调选择了某种在线支付，或从余额付款页面返回来选择在线支付，又或者是该订单曾支付过部分余额时，任何一种情况成功，都不将使用余额支付。
	//to_url=olpay 定义将要返回到本页面， 不然的话。就不知道是不是返回到另一个olpay2.php页面
	if($webdb['rmb_pay'] && $lfjdb[rmb]>0 && !$banktype && !$from_rmbpay && !$infodb[rmb]){	//余额付款
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=rmb_pay.php?ids=$id&to_url=olpay'>";
		exit;
	}else{
		$array = $webdb;	//管理员代收货款时，上面若不能同时成立，则调用全局的网银相关参数，在线付款。
	}
}else{
	$rs = $db->get_one("SELECT * FROM `{$pre}purse` WHERE uid='$infodb[uid]'");
	$array = unserialize($rs[config]);	//没有启用管理员代收货款的话，就调用商家的网银参数
}




if(!$array[yeepay_id]&&!$array[tenpay_id]&&!$array[wapAlipay_id]&&!$array[pay99_id]&&!$array[chinabank_id]){
	refreshto("./","在线支付失败,网银帐号不存在!",10);
}

//易宝支付
$webdb[yeepay_id] = $array[yeepay_id];
$webdb[yeepay_key] = $array[yeepay_key];

//财付通
$webdb[tenpay_id] = $array[tenpay_id];
$webdb[tenpay_key] = $array[tenpay_key];

//网银在线
$webdb[chinabank_id] = $array[chinabank_id];
$webdb[chinabank_key] = $array[chinabank_key];

//贝宝
$webdb[paypal_id] = $array[paypal_id];
$webdb[paypal_key] = $array[paypal_key];
$webdb[paypal_type] = $array[paypal_type];

//支付宝
$webdb[wapAlipay_id] = $array[wapAlipay_id];
$webdb[alipay_key] = $array[alipay_key];
$webdb[wapAlipay_partner] = $array[wapAlipay_partner];
$webdb[alipay_service] = $array[alipay_service];
$webdb[alipay_transport] = $array[alipay_transport];



function olpay_end($numcode){
	global $db,$_pre,$webdb,$banktype,$pay_code,$lfjuid,$lfjdb,$infodb;

	if(!$pay_code){
		showerr("数据有误!!");
	}
	
	list($atc_moeny,$id)=explode("\t",mymd5($pay_code,'DE'));
	$id = intval($id);
	$rs = $db->get_one("SELECT * FROM `{$_pre}join` WHERE id='$id'");
	
	
	if($webdb['daili_receive'] && $rs[rmb]){//管理员代收货款 
		
		//启用了余额付款，并且延迟扣余额
		if($webdb['rmb_pay'] && $webdb[rmb_late_pay]){	//后台设置的支付成功后，才扣除之前的部分余额
			if($lfjdb[rmb]>=$rs[rmb]){
				add_rmb($rs[uid],-$rs[rmb],0,"购买商品，余额支付部分:{$rs[title]}...");
				add_rmb($rs[cuid],$rs[rmb],0,"销售商品，余额支付部分:{$rs[title]}...");
			}else{
				add_rmb($rs[uid],$rs[totalmoney],0,"购买商品失败，商品余款充进你的余额");
				refreshto("./","很抱歉，你的帐户余额低于你之前商品欲使用的余额，购买商品失败，本次在线付款的款项直接充入你的帐户余额里！",60);
			}
		}
		//以上步骤是处理延迟扣余额的情况，否则的话，就提前扣了购买者的余额，同时提前给商家增加了余额。这里只须要给商家增加实际的在线付款值那部分款项。
		add_rmb( $rs[cuid], ($rs[totalmoney]-$rs[rmb]) , 0 , "销售商品，在线付款部分款项:{$rs[title]}...");
	}
	
	$db->query("UPDATE {$_pre}join SET ifpay='1',banktype='$banktype' WHERE id='$id'");
	
	$d = explode(',',$rs['products']);
	foreach($d AS $v){
		list($pid,$pnum)=explode('=',$v);
		shop_storage_change($pid,$pnum);	//货存量的调整
		shop_give_money($pid,$lfjuid);	//买商品赠送积分
	}

	//付款后,短信或邮件通知商家
	paymoney_send_seller_msg($rs[cuid],$rs);
	
	$detail = explode(',',get_cookie('olpay_id'));
	if($id=$detail[0]){
		unset($detail[0]);
		set_cookie('olpay_id',implode(',',$detail));
		refreshto("olpay.php?id=$id","该笔订单付款成功,请继续支付下一笔",3);
	}

	refreshto("./","恭喜你订单付款成功!",60);
}

?>