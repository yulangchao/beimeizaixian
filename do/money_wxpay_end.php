<?php
require("global.php");


require("olpay.inc.php");


if($type=='return'){
	if($ispay=='ok'){
		refreshto("$webdb[www_url]/member/wapindex.php","֧���ɹ����鿴�ʻ���Ϣ",3);
	}else{
		refreshto("$webdb[www_url]/member/wapindex.php","֧��ʧ�ܣ��鿴�ʻ���Ϣ",3);
	}
	exit;
}

require(ROOT_PATH.'inc/wapolpay/weixin/notify.php');
?>