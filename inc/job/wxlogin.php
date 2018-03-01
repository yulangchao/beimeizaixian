<?php

if($type=='cklogin'){

	$rsdb = $db->get_one("SELECT * FROM {$pre}login_check WHERE usr='$usr_sid'");
	if($rsdb[uid]){

		$uc_login_code='';

		$rs=$userDB->get_info($rsdb[uid]);

		//if($type=='login'){
		$db->query("DELETE FROM {$pre}login_check WHERE uid='$rsdb[uid]'");
		
		$userDB->login($rs[username],'','3600',true);

		if($uc_login_code){	//整合DZ
			setcookie('ucLoginCode',$uc_login_code);
		}
		
		//}else{
		die("$rsdb[uid]");
		//}
	}else{
		die('0');
	}
	
}elseif($type=='jump'){
	
	//$rsdb=$db->get_one("SELECT * FROM `{$pre}hy_wap` WHERE uid='$lfjuid' ORDER BY id DESC LIMIT 1");
	
	//if($rsdb[id]){
	//	header("location:/do/pageset.php?id=$rsdb[id]");
	//	exit;
	//}
	
	$uc_login_code = '';
	if( $_COOKIE[ucLoginCode] ){
		$uc_login_code = stripslashes($_COOKIE[ucLoginCode]);
	}
	refreshto("$webdb[www_url]/member/","登录成功!!<div style='display:none;'><iframe src='crontab.php' width=0 height=0></iframe></div>{$uc_login_code}",1);
	//header("location:$webdb[www_url]/member/");
	exit;
	
	die("$lfjid,登录成功");
}

?>