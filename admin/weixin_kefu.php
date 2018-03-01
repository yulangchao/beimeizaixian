<?php
!function_exists('html') && exit('ERR');

if(!$Apower['weixin_kefu']){
	showmsg('你没权限!');
}

if($job=="list")
{
	!$page&&$page=1;
	$rows=200;
	$min=($page-1)*$rows;
	
	$showpage=getpage("{$pre}weixinword","","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query(" SELECT * FROM {$pre}weixinword ORDER BY list DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[answer]=get_word($rs[answer],80);
		$listdb[]=$rs;
	}
	$kill_badword[intval($webdb[kill_badword])]=' checked ';
	
	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/weixin_kefu/list.htm");
	require(dirname(__FILE__).'/foot.php');
	
}
elseif($action=="set")
{
	write_config_cache($webdbs);
	jump("设置成功","index.php?lfj=$lfj&job=list&",1);
}

?>