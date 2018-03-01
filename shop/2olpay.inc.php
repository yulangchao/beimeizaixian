<?php
!function_exists('html') && exit('ERR');

require_once(Mpath."inc/olpay.inc.php");

if(!$lfjuid){
	showerr('请先登录!');
}elseif(!$webdb['daili_receive']){
	showerr('系统未启用帮商家代收货款!');
}



if($pay_code){	//POST与API返回时
	$pay_code = str_replace('QIBO','=',$pay_code);	//这个符号“=”容易出问题
	list(,$ids)=explode("\t",mymd5($pay_code,'DE'));
}

$array = explode(',',$ids);
foreach($array  AS $key=>$value){
	$array[$key] = intval($value);
}
if(count($array)<1){
	showerr('URL数据有误!');
}
$totalemoney=0;
$title = '';
$ck_rmb = $lfjdb['rmb'];
$query = $db->query("SELECT A.title,A.uid,B.totalmoney,B.ifpay,B.cid,B.rmb,B.id FROM `{$_pre}join` B LEFT JOIN `{$_pre}content` A ON A.id=B.cid WHERE B.id IN (".implode(',',$array).")");
while($rs = $db->fetch_array($query)){
	if($rs[ifpay]){	//余额成功支付过了
		continue;
	}
	
	$rmb=0;
	//如果启用了余额支付
	if($webdb['rmb_pay'] && $rs['rmb']){
		//如果延时支付但余额又不足的话，就取消余额付部分款，而使用在线支付给全款。
		if($webdb[rmb_late_pay]  && $ck_rmb<$rs['rmb']){
			$db->query("UPDATE {$_pre}join SET banktype='',rmb='0' WHERE id='$rs[id]'");
		}else{
			$rmb=$rs['rmb'];
			$ck_rmb -= $rs['rmb'];
		}
	}
	
	$totalemoney += $rs['totalmoney']-$rmb;	//除去$rs['rmb']余额支付部分.
	$title .="$rs[title],";
}

if($totalemoney<=0){
	showerr('款项总计为0,无法在线支付!');
}

if(!$webdb[yeepay_id]&&!$webdb[tenpay_id]&&!$webdb[wapAlipay_id]&&!$webdb[pay99_id]&&!$webdb[chinabank_id]&&!$webdb[paypal_id]){
	showerr("在线支付失败,管理员没有设置网银帐号!");
}






?>