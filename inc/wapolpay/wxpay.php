<?php
!function_exists('html') && exit('ERR');

if(!$webdb['wxpay_AppID'] || !$webdb['wxpay_AppSecret'] || !$webdb['wxpay_ID'] || !$webdb['wxpay_ApiKey']){
	showerr('ϵͳû�����ú�΢��֧���ӿ�,���Բ���ʹ��΢��֧��');
}

if($webdb[WXlogin_API]!=2){
	showerr('ϵͳû�����������ںŵ�¼������ʹ��΢��֧��');
}elseif($lfjdb[weixin_api]==''){
	showerr('�㵱ǰ���ʺŻ�û�а�΢�ŵ�¼');
}

 
$array=olpay_send();


WEB_LANG=='utf-8' ? $array['title'] : gbk2utf8($array['title']);
$array['title']=$array[numcode]?$array[numcode]:'shop';	//���Ļ����
//$array['money']='0.01';	//����ʹ��
//$array['other']='test';	//��������
require(dirname(__FILE__).'/weixin/jsapi.php');
exit;
?>