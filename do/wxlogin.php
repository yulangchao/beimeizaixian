<?php
require(dirname(__FILE__)."/global.php");



if( !table_field("{$pre}memberdata",'weixin_api') ){
	$db->query("ALTER TABLE  `{$pre}memberdata` ADD  `weixin_api` VARCHAR( 32 ) NOT NULL");
	$db->query("ALTER TABLE  `{$pre}memberdata` ADD INDEX (  `weixin_api` )");
}

if(!is_table("{$pre}wxlogin")){
	$db->query("CREATE TABLE `{$pre}wxlogin` (
  `usrid` varchar(15) NOT NULL default '',
  `sid` varchar(15) NOT NULL default '',
  `usrinfo` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  UNIQUE KEY `usrid` (`usrid`),
  KEY `sid` (`sid`)
	) ENGINE=MyISAM;");
}

if($action!='wxpost' && $IsMob){
	header("location:wapwxlogin.php");
	exit;
}
if($action!='wxpost' && $lfjuid && $lfjdb[weixin_api] ){
	showerr('你已经绑定过微信了！');
}


if($webdb[WXlogin_API]==2){	//使用自身公众号
	//wx_pc_login();
	
	$md5code = mymd5("$timestamp\t$usr_sid",'EN');
	$db->query("REPLACE INTO  `{$pre}login_check` ( `usr` ,`posttime` ) values ( '$usr_sid', '$timestamp')");
	$WXID || $WXID='NO';
	$URL = urlencode("$webdb[www_url]/do/2codelogin.php?WXID=$WXID&md5code=$md5code");
	
	require(ROOT_PATH."inc/head.php");
	require(html("pcwxlogin"));
	require(ROOT_PATH."inc/foot.php");
	exit;
	
}elseif(!$webdb[WXlogin_API]){
	showerr('系统没有启用微信登录！');
}

//下面是通过第三方公众号登录


if($action=='wxpost'){	//用户扫描后，微信端POST用户数据过来

	$string = file_get_contents("http://wx.php168.com/do/ApiWxLogin.php?job=check&U_sid=$UsrId");
	
	if(!is_numeric($string) || $string<1){
		die("数据不存在，请重新扫描！$string");
	}
	
	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `sid`='$UsrId'");
	if($rsdb){
 
		$str = addslashes( serialize( $postdb ) );
		$db->query("UPDATE {$pre}wxlogin SET usrinfo='$str' WHERE `sid`='$UsrId'");
		
		$wx_appid = filtrate($_POST[openid]);
		
		$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");

		if($rs){
			$showerrMsg = "欢迎你回来：$rs[username] .请返回电脑端";
		}else{
			$showerrMsg = '欢迎你！.请返回电脑端';
		}
	}else{
		$showerrMsg = '资料不存在！';
	}
	require(html('wxlogin_msg'));
	exit;
	
}elseif($job=='cklogin'){	//JS刷新是否有用户数据

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = filtrate($cdb[openid]);
	
	if(!$wx_appid){	//微信端还没有POST用户资料过来
		die('0');
		
	}else{	//已经有数据了，继续判断用户注册与否，及是否绑定过微信
		
		
		$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");
		
		if(!$step&&!$act){	//告知JS那边可以登录，还没跳转过来执行登录操作，
			die("1");
		}
		
		if($rs){	//	已绑定过微信的，直接登录
		
			$db->query("DELETE FROM {$pre}wxlogin WHERE `usrid`='$usr_sid'");
			$userDB->login($rs[username],'',36000,true);
			
			if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
				$jumpto=$fromurl;
			}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
				$jumpto=$FROMURL;
			}else{
				$jumpto="$webdb[www_url]/";
			}			
			refreshto($jumpto,"登录成功{$uc_login_code}",3);

		}else{	//还没绑过微信的，需要自动新注册一个帐号或者绑定已有旧帐号
			
			if($act){
				
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

				if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
					$jumpto=$fromurl;
				}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
					$jumpto=$FROMURL;
				}else{
					$jumpto="$webdb[www_url]/";
				}
				refreshto($jumpto,"$msg<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>{$uc_login_code}",1);
				
			}else{
			
				if($lfjuid){	//已经登录过的用户，扫描二维码绑定帐号
				
					$db->query("DELETE FROM {$pre}wxlogin WHERE `usrid`='$usr_sid'");
					
					$userDB->edit_user( array('username'=>$lfjid,'weixin_api'=>$wx_appid) );
					//$db->query("UPDATE pw_memberdata SET weixin_api='$wx_appid' WHERE uid='$lfjuid'");
					
					if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
						$jumpto=$fromurl;
					}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
						$jumpto=$FROMURL;
					}else{
						$jumpto="$webdb[www_url]/";
					}
					refreshto($jumpto,"绑定成功",1);
					
				}else{
					/*
					echo '<FORM METHOD=POST ACTION="?job=cklogin">
							<INPUT TYPE="radio" NAME="act" value="2">自动注册新帐号 
							新昵称：<INPUT TYPE="text" NAME="atc_username2" value="'.$cdb[nickname].'">
							<br>
							<INPUT TYPE="radio" NAME="act" checked value="1">绑定旧帐号
								帐号：<INPUT TYPE="text" NAME="atc_username">
								帐号：<INPUT TYPE="password" NAME="atc_password"> <br><INPUT TYPE="submit" value="下一步">
							</FORM>';
						*/
					require(ROOT_PATH."template/default/wxlogin.htm");
					exit;
				}
			}
			
		}
	}
}



$time = $timestamp-600;
$db->query("DELETE FROM `{$pre}wxlogin` WHERE posttime<$time");
	
$sid = substr(md5($usr_sid),-15);
$db->query("REPLACE INTO  `{$pre}wxlogin` ( `usrid` , `sid` ,`posttime` ) values ( '$usr_sid', '$sid', '$timestamp')");
$url = urlencode("$webdb[www_url]/do/wxlogin.php?action=wxpost");
$imgurl = "http://wx.php168.com/do/ApiWxLogin.php?job=getimg&U_sid=$sid&U_url=$url";

$msgsay=$lfjid?'请用微信扫一扫绑定现有帐号':'微信扫一扫快速登录或注册';

require(html('wxlogin'));



?>