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
		showerr("参数有误！");
	}elseif( ($timestamp-$time)>300 ){
		showerr("超时了，5分钟内有效，请再次刷新一下电脑页面再扫描！");
	}
	$usrID = filtrate($usrID);
	$rsdb = $db->get_one("SELECT * FROM {$pre}login_check WHERE usr='$usrID'");
	if($rsdb[posttime]!=$time){
		showerr("参数不匹配，请重新刷页面再扫描！");
	}
	$db->query("UPDATE {$pre}login_check SET uid='$lfjuid' WHERE usr='$usrID'");
	refreshto("/","电脑端登录成功，你可以关闭本页面",20);
}

?>