<?php
require(dirname(__FILE__)."/"."global.php");


if(!$lfjuid){
	$fromurl=urlencode($WEBURL);
	header("location:wx_login.php?fromurl=$fromurl");
	exit;
}

if($md5code){
	list($time,$usrID) = explode("\t",mymd5($md5code,'DE'));
	if(!$usrID){
		showerr("��������");
	}elseif( ($timestamp-$time)>300 ){
		showerr("��ʱ�ˣ�5��������Ч�����ٴ�ˢ��һ�µ���ҳ����ɨ�裡");
	}
	$usrID = filtrate($usrID);
	$rsdb = $db->get_one("SELECT * FROM {$pre}login_check WHERE usr='$usrID'");
	if($rsdb[posttime]!=$time){
		showerr("������ƥ�䣬������ˢҳ����ɨ�裡");
	}
	$db->query("UPDATE {$pre}login_check SET uid='$lfjuid' WHERE usr='$usrID'");
	refreshto("/","���Զ˵�¼�ɹ�������Թرձ�ҳ��",20);
}

?>