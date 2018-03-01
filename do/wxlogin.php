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
	showerr('���Ѿ��󶨹�΢���ˣ�');
}


if($webdb[WXlogin_API]==2){	//ʹ�������ں�
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
	showerr('ϵͳû������΢�ŵ�¼��');
}

//������ͨ�����������ںŵ�¼


if($action=='wxpost'){	//�û�ɨ���΢�Ŷ�POST�û����ݹ���

	$string = file_get_contents("http://wx.php168.com/do/ApiWxLogin.php?job=check&U_sid=$UsrId");
	
	if(!is_numeric($string) || $string<1){
		die("���ݲ����ڣ�������ɨ�裡$string");
	}
	
	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `sid`='$UsrId'");
	if($rsdb){
 
		$str = addslashes( serialize( $postdb ) );
		$db->query("UPDATE {$pre}wxlogin SET usrinfo='$str' WHERE `sid`='$UsrId'");
		
		$wx_appid = filtrate($_POST[openid]);
		
		$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");

		if($rs){
			$showerrMsg = "��ӭ�������$rs[username] .�뷵�ص��Զ�";
		}else{
			$showerrMsg = '��ӭ�㣡.�뷵�ص��Զ�';
		}
	}else{
		$showerrMsg = '���ϲ����ڣ�';
	}
	require(html('wxlogin_msg'));
	exit;
	
}elseif($job=='cklogin'){	//JSˢ���Ƿ����û�����

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = filtrate($cdb[openid]);
	
	if(!$wx_appid){	//΢�Ŷ˻�û��POST�û����Ϲ���
		die('0');
		
	}else{	//�Ѿ��������ˣ������ж��û�ע����񣬼��Ƿ�󶨹�΢��
		
		
		$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");
		
		if(!$step&&!$act){	//��֪JS�Ǳ߿��Ե�¼����û��ת����ִ�е�¼������
			die("1");
		}
		
		if($rs){	//	�Ѱ󶨹�΢�ŵģ�ֱ�ӵ�¼
		
			$db->query("DELETE FROM {$pre}wxlogin WHERE `usrid`='$usr_sid'");
			$userDB->login($rs[username],'',36000,true);
			
			if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
				$jumpto=$fromurl;
			}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
				$jumpto=$FROMURL;
			}else{
				$jumpto="$webdb[www_url]/";
			}			
			refreshto($jumpto,"��¼�ɹ�{$uc_login_code}",3);

		}else{	//��û���΢�ŵģ���Ҫ�Զ���ע��һ���ʺŻ��߰����о��ʺ�
			
			if($act){
				
				if($act==2){	//�Զ�ע�����ʺ�
					$array = reg_new_member($atc_username2,$cdb);
					if(is_array($array)){
						$userDB->login($array[username],'',36000,true);
					}
					$msg = '�Զ�ע��ɹ���';
				}elseif($act==1){	//�󶨾��ʺ�
					$login = $userDB->login($atc_username,$atc_password,36000);
					if($login==0){
						showerr("��ǰ�û�������,����������");
					}elseif($login==-1){
						showerr("���벻��ȷ,�����������");
					}
					$userDB->edit_user( array('username'=>$atc_username,'weixin_api'=>$wx_appid) );
					$msg = '�ʺŰ󶨳ɹ���';
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
			
				if($lfjuid){	//�Ѿ���¼�����û���ɨ���ά����ʺ�
				
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
					refreshto($jumpto,"�󶨳ɹ�",1);
					
				}else{
					/*
					echo '<FORM METHOD=POST ACTION="?job=cklogin">
							<INPUT TYPE="radio" NAME="act" value="2">�Զ�ע�����ʺ� 
							���ǳƣ�<INPUT TYPE="text" NAME="atc_username2" value="'.$cdb[nickname].'">
							<br>
							<INPUT TYPE="radio" NAME="act" checked value="1">�󶨾��ʺ�
								�ʺţ�<INPUT TYPE="text" NAME="atc_username">
								�ʺţ�<INPUT TYPE="password" NAME="atc_password"> <br><INPUT TYPE="submit" value="��һ��">
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

$msgsay=$lfjid?'����΢��ɨһɨ�������ʺ�':'΢��ɨһɨ���ٵ�¼��ע��';

require(html('wxlogin'));



?>