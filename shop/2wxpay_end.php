<?php
require("global.php");

require_once(Mpath."inc/olpay.inc.php");



if($type=='return'){
	if($ispay=='ok'){
		refreshto("$Murl/wapindex.php","支付成功！返回商城",3);
	}else{
		refreshto("$Murl/wapindex.php","支付失败！返回商城",3);
	}
	exit;
}


//下面的文件要执行 olpay_end 函数
require(ROOT_PATH.'inc/wapolpay/weixin/notify.php');


?>