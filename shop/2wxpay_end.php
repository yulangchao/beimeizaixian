<?php
require("global.php");

require_once(Mpath."inc/olpay.inc.php");



if($type=='return'){
	if($ispay=='ok'){
		refreshto("$Murl/wapindex.php","֧���ɹ��������̳�",3);
	}else{
		refreshto("$Murl/wapindex.php","֧��ʧ�ܣ������̳�",3);
	}
	exit;
}


//������ļ�Ҫִ�� olpay_end ����
require(ROOT_PATH.'inc/wapolpay/weixin/notify.php');


?>