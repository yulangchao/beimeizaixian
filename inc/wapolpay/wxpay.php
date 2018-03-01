<?php
!function_exists('html') && exit('ERR');

if(!$webdb['wxpay_AppID'] || !$webdb['wxpay_AppSecret'] || !$webdb['wxpay_ID'] || !$webdb['wxpay_ApiKey']){
	showerr('系统没有设置好微信支付接口,所以不能使用微信支付');
}

if($webdb[WXlogin_API]!=2){
	showerr('系统没有启用自身公众号登录，不能使用微信支付');
}elseif($lfjdb[weixin_api]==''){
	showerr('你当前的帐号还没有绑定微信登录');
}

 
$array=olpay_send();


WEB_LANG=='utf-8' ? $array['title'] : gbk2utf8($array['title']);
$array['title']=$array[numcode]?$array[numcode]:'shop';	//中文会出错
//$array['money']='0.01';	//调试使用
//$array['other']='test';	//附带参数
require(dirname(__FILE__).'/weixin/jsapi.php');
exit;
?>