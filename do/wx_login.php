<?php
define('LOGIN_PAGE',true);
require(dirname(__FILE__)."/"."global.php");
//$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
unset($uc_login_code);

if(!$IsMob){
	showerr('��ǰҳ��ֻ�����ֻ�΢�ŷ��ʣ�');
}

if($lfjid){
 
	refreshto("$webdb[www_url]/","���Ѿ���¼��,�벻Ҫ�ظ���¼,Ҫ���µ�¼����<br><br><A HREF='$webdb[www_url]/do/login.php?action=quit'>��ȫ�˳�",0);
}

if($webdb[WXlogin_API]==1){	//ʹ�õ��������ںŵ�¼
	header("location:wapwxlogin.php?fromurl=".urlencode($fromurl));
	exit;
}elseif(!$webdb[WXlogin_API]){
	showerr('ϵͳû������΢�ŵ�¼��');
}
	
if($state==1){
	
	if(!$code){
		showerr('code ֵ�����ڣ�');
	}
	$string = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]&code=$code&grant_type=authorization_code");
	
	$array = json_decode($string,true);
	
	$openid = $array['openid'];
	if(!$openid){
		if($string == ''){
			showerr('��ȡ΢�Žӿ�����ʧ�ܣ���ȷ����ķ������Ѵ� extension=php_openssl.dll ');
		}
		showerr('openid ֵ�����ڣ�');
	}
	$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api = '$openid' ");
	
	if(!$rs){
		refreshto("wx_reg.php?openid=$openid","�㻹û��ע�ᣬ�����Զ�ע��һ���ʺ�!!<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>",0);
	}
	
	$userDB->login($rs[username],'',7200,true);
	
	//set_cookie('WeiXin_OpenId',$openId,12*3600);
	set_cookie('WeiXin_AccessToken',$array['access_token'],7200); //�����ں��� ��ȡ�����ջ���ַ

	
	
	$lfjid=$rs[username];
	$lfjuid=$rs[uid];
	set_user_log(2);	//�û�������־

		
	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/";
	}
	refreshto("$jumpto","��¼�ɹ�!!<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>",0);

}else{
	
	set_cookie('From_url',$fromurl);
	//��ת��΢�ŷ������ٷ�����������õ�һ����Ч��codeֵ��
	$url = urlencode($WEBURL);
	header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=$webdb[wxpay_AppID]&redirect_uri=$url&response_type=code&scope=snsapi_base&state=1#wechat_redirect");
	exit;
}

?>