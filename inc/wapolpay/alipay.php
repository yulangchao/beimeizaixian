<?php
!function_exists('html') && exit('ERR');

if(!$webdb['wapAlipay_partner']){
	showerr('ϵͳû������֧�����տ��ʺ�,���Բ���ʹ��֧��������֧��');
}

 
$array=olpay_send();

	//֧������һЩСBUG,Ҫ�ر�������
	//if(eregi("^0",$array[numcode])){
	//	$array[numcode]="$array[numcode]code";
	//}
	
	//write_file(ROOT_PATH."cache/notify_0_{$lfjuid}.txt",$array['return_url']);
/*
	$para = array(
			'notify_url'	=> $webdb['www_url'].'/do/notify_url.php',
			'service'		 => $webdb['alipay_service'],	//��������
			'partner'		 => $webdb['wapAlipay_partner'],		//�����̻���
			'return_url'	 => $array['return_url'],		//ͬ������

			'subject'		 => $array['title'],			//��Ʒ���ƣ�����
			'body'			 => $array['content'],			//��Ʒ����������
			'out_trade_no'	 => $array['numcode'],			//��Ʒ�ⲿ���׺ţ������֤Ψһ�ԣ�
			'price'		 => $array['money'],				//�ܶ�
			'seller_email'	 => $webdb['wapAlipay_id'],		//�������䣬����
		);
*/
WEB_LANG=='utf-8' ? $array['title'] : gbk2utf8($array['title']);
$array['title']='shop';	//���Ļ����
//$array['money']='0.01';	//����ʹ��

if(!function_exists('openssl_get_privatekey')){
	die('���޸�php.ini����extension=php_openssl.dll����ǰ��ķֺ�;ɾ��,Ȼ��������������');
}

require(ROOT_PATH.'inc/wapolpay/alipay/alipayapi.php');
exit;
?>