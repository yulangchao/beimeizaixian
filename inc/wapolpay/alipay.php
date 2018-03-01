<?php
!function_exists('html') && exit('ERR');

if(!$webdb['wapAlipay_partner']){
	showerr('系统没有设置支付宝收款帐号,所以不能使用支付宝在线支付');
}

 
$array=olpay_send();

	//支付宝的一些小BUG,要特别处理订单号
	//if(eregi("^0",$array[numcode])){
	//	$array[numcode]="$array[numcode]code";
	//}
	
	//write_file(ROOT_PATH."cache/notify_0_{$lfjuid}.txt",$array['return_url']);
/*
	$para = array(
			'notify_url'	=> $webdb['www_url'].'/do/notify_url.php',
			'service'		 => $webdb['alipay_service'],	//交易类型
			'partner'		 => $webdb['wapAlipay_partner'],		//合作商户号
			'return_url'	 => $array['return_url'],		//同步返回

			'subject'		 => $array['title'],			//商品名称，必填
			'body'			 => $array['content'],			//商品描述，必填
			'out_trade_no'	 => $array['numcode'],			//商品外部交易号，必填（保证唯一性）
			'price'		 => $array['money'],				//总额
			'seller_email'	 => $webdb['wapAlipay_id'],		//卖家邮箱，必填
		);
*/
WEB_LANG=='utf-8' ? $array['title'] : gbk2utf8($array['title']);
$array['title']='shop';	//中文会出错
//$array['money']='0.01';	//调试使用

if(!function_exists('openssl_get_privatekey')){
	die('请修改php.ini查找extension=php_openssl.dll把他前面的分号;删除,然后再重启服务器');
}

require(ROOT_PATH.'inc/wapolpay/alipay/alipayapi.php');
exit;
?>