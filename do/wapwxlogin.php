<?php
require(dirname(__FILE__)."/global.php");

if(!$IsMob){
	showerr('��ǰҳ��ֻ�����ֻ�΢�ŷ��ʣ�');
}

if($lfjuid){
	showerr('���Ѿ���¼�ˣ�');
}

if($webdb[WXlogin_API]==2){	//ʹ�������ں�
	header("location:wx_login.php?fromurl=".urlencode($fromurl));
	exit;
}elseif(!$webdb[WXlogin_API]){
	showerr('ϵͳû������΢�ŵ�¼��');
}

//������ʹ�õ��������ں�
			
if($action=='wxpost'){	//���������ں�΢�Ŷ�POST�û����ݹ���

	$string = file_get_contents("http://wx.php168.com/do/ApiWxLogin.php?job=check&U_sid=$UsrId");
	
	if(!is_numeric($string) || $string<1){
		showerr("���ݲ�����!");
	}
	
	$wx_appid = $postdb[openid];
	
	if(WEB_LANG=='utf-8'){
		$postdb[nickname] = gbk2utf8($postdb[nickname]);
		$postdb[address] = gbk2utf8($postdb[address]);
	}
	
	$rs = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$wx_appid'");
		
	if($rs){	//	�Ѱ󶨹�΢�ŵģ�ֱ�ӵ�¼

		$fromurl = get_cookie('From_url');
		if( $fromurl ){
			set_cookie('From_url','');
			$jumpto=$fromurl;
		}else{
			$jumpto="$webdb[www_url]/member/wapindex.php";
		}
	
		$userDB->login($rs[username],'',36000,true);

		refreshto($jumpto,"��¼�ɹ�{$uc_login_code}",1);
		
	}else{	//��û���΢�ŵģ���Ҫ�Զ���ע��һ���ʺŻ��߰����о��ʺ�
		
		$str = addslashes( serialize( $postdb ) );
		$db->query("REPLACE INTO `{$pre}wxlogin` ( `usrid` , `sid` ,`posttime` ,`usrinfo` ) values ( '$usr_sid', '$UsrId', '$timestamp', '$str')");
		
		header("location:wapwxlogin.php?job=reg");
		//require(html('wapwxlogin'));	//��ת��һ��Ϊ���Ǵ������û�����ͬʱ��������ض�������
		exit;
	}

}elseif($job=='reg'){

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = $cdb[openid];
	
	if(!$wx_appid){
		showerr('��������');
	}

	$postdb[nickname] = $cdb[nickname];
	require(html('wapwxlogin'));
	exit;

}elseif($action=='reg'){	//���û������Զ�ע�ỹ�ǰ󶨾��ʺ�

	$rsdb = $db->get_one("SELECT * FROM `{$pre}wxlogin` WHERE `usrid`='$usr_sid'");
	$cdb = unserialize($rsdb[usrinfo]);
	
	$wx_appid = $cdb[openid];
	
	if(!$wx_appid){
		showerr('��������');
	}
	
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


	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/wapindex.php";
	}
	
	refreshto($jumpto,"$msg<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div></div>{$uc_login_code}",1);
	
}else{	//��ת����������վ��ȡ�û���΢������

	$sid = substr(md5($usr_sid),-15);
	$url = urlencode("$webdb[www_url]/do/wapwxlogin.php?action=wxpost");
	$jumpUrl = "http://wx.php168.com/do/ApiWxLogin.php?job=wap_getuserinfo&U_sid=$sid&U_url=$url";
	header("location:$jumpUrl");
	exit;

}


?>