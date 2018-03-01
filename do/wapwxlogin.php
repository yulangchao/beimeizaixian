<?php
require(dirname(__FILE__)."/global.php");

if(!$IsMob){
	showerr('当前页面只能用手机微信访问！');
}

if($lfjuid){
	showerr('你已经登录了！');
}

if($webdb[WXlogin_API]==2){	//使用自身公众号
	header("location:wx_login.php?fromurl=".urlencode($fromurl));
	exit;
}elseif(!$webdb[WXlogin_API]){
	showerr('系统没有启用微信登录！');
}

//以下是使用第三方公众号
			
if($action=='wxpost'){	//第三方公众号微信端POST用户数据过来

	$string = file_get_contents("http://wx.php168.com/do/ApiWxLogin.php?job=check&U_sid=$UsrId");
	
	if(!is_numeric($string) || $string<1){
		showerr("数据不存在!");
	}
	
	$wx_appid = $postdb[openid];
	
	if(WEB_LANG=='utf-8'){
		$postdb[nickname] = gbk2utf8($postdb[nickname]);
		$postdb[address] = gbk2utf8($postdb[address]);
	}
	
	$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");
		
	if($rs){	//	已绑定过微信的，直接登录

		$fromurl = get_cookie('From_url');
		if( $fromurl ){
			set_cookie('From_url','');
			$jumpto=$fromurl;
		}else{
			$jumpto="$webdb[www_url]/member/wapindex.php";
		}
	
		$userDB->login($rs[username],'',36000,true);

		refreshto($jumpto,"登录成功{$uc_login_code}",1);
		
	}else{	//还没绑过微信的，需要自动新注册一个帐号或者绑定已有旧帐号
		
		$str = addslashes( serialize( $postdb ) );
		$db->query("REPLACE INTO `{$pre}wxlogin` ( `usrid` , `sid` ,`posttime` ,`usrinfo` ) values ( '$usr_sid', '$UsrId', '$timestamp', '$str')");
		
		header("location:wapwxlogin.php?job=reg");
		//require(html('wapwxlogin'));	//跳转多一步为的是处理检查用户名雷同时，点击返回而不报错
		exit;
	}

}elseif($job=='reg'){

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = $cdb[openid];
	
	if(!$wx_appid){
		showerr('资料有误！');
	}

	$postdb[nickname] = $cdb[nickname];
	require(html('wapwxlogin'));
	exit;

}elseif($action=='reg'){	//新用户处理自动注册还是绑定旧帐号

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = $cdb[openid];
	
	if(!$wx_appid){
		showerr('资料有误！');
	}
	
	if($act==2){	//自动注册新帐号
			$array = reg_new_member($atc_username2,$cdb);
			if(is_array($array)){
				$userDB->login($array[username],'',36000,true);
			}
			$msg = '自动注册成功！';
	}elseif($act==1){	//绑定旧帐号
			$login = $userDB->login($atc_username,$atc_password,36000);
			if($login==0){
				showerr("当前用户不存在,请重新输入");
			}elseif($login==-1){
				showerr("密码不正确,点击重新输入");
			}
			$userDB->edit_user( array('username'=>$atc_username,'weixin_api'=>$wx_appid) );
			$msg = '帐号绑定成功！';
	}
	
	$db->query("DELETE FROM {$pre}wxlogin WHERE `usrid`='$usr_sid'");


	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/wapindex.php";
	}
	
	refreshto($jumpto,"$msg<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>{$uc_login_code}",1);
	
}else{	//跳转到第三方网站获取用户的微信资料

	$sid = substr(md5($usr_sid),-15);
	$url = urlencode("$webdb[www_url]/do/wapwxlogin.php?action=wxpost");
	$jumpUrl = "http://wx.php168.com/do/ApiWxLogin.php?job=wap_getuserinfo&U_sid=$sid&U_url=$url";
	header("location:$jumpUrl");
	exit;

}


?>