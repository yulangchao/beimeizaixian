<?php
require("global.php");


require("rmb.inc.php");


if($type=='return'){
	if($ispay=='ok'){
		refreshto("$webdb[www_url]/member/waprmb.php?job=list","支付成功！查看帐户信息",3);
	}else{
		refreshto("$webdb[www_url]/member/waprmb.php?job=list","支付失败！查看帐户信息",3);
	}
	exit;
}

require(ROOT_PATH.'inc/wapolpay/weixin/notify.php');
?>