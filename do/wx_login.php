<?php
define('LOGIN_PAGE',true);
require(dirname(__FILE__)."/"."global.php");
//$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
unset($uc_login_code);

if(!$IsMob){
	showerr('当前页面只能用手机微信访问！');
}

if($lfjid){
 
	refreshto("$webdb[www_url]/","你已经登录了,请不要重复登录,要重新登录请点击<br><br><A HREF='$webdb[www_url]/do/login.php?action=quit'>安全退出",0);
}

if($webdb[WXlogin_API]==1){	//使用第三方公众号登录
	header("location:wapwxlogin.php?fromurl=".urlencode($fromurl));
	exit;
}elseif(!$webdb[WXlogin_API]){
	showerr('系统没有启用微信登录！');
}
	
if($state==1){
	
	if(!$code){
		showerr('code 值不存在！');
	}
	$string = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]&code=$code&grant_type=authorization_code");
	
	$array = json_decode($string,true);
	
	$openid = $array['openid'];
	if(!$openid){
		if($string == ''){
			showerr('获取微信接口内容失败，请确认你的服务器已打开 extension=php_openssl.dll ');
		}
		showerr('openid 值不存在！');
	}
	$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api = '$openid' ");
	
	if(!$rs){
		refreshto("wx_reg.php?openid=$openid","你还没有注册，现在自动注册一个帐号!!<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>",0);
	}
	
	$userDB->login($rs[username],'',7200,true);
	
	//set_cookie('WeiXin_OpenId',$openId,12*3600);
	set_cookie('WeiXin_AccessToken',$array['access_token'],7200); //可用于后续 获取共享收货地址

	
	
	$lfjid=$rs[username];
	$lfjuid=$rs[uid];
	set_user_log(2);	//用户访问日志

		
	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/";
	}
	refreshto("$jumpto","登录成功!!<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>",0);

}else{
	
	set_cookie('From_url',$fromurl);
	//跳转到微信服务器再返回来，将会得到一个有效的code值。
	$url = urlencode($WEBURL);
	header("location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=$webdb[wxpay_AppID]&redirect_uri=$url&response_type=code&scope=snsapi_base&state=1#wechat_redirect");
	exit;
}

?>