<?php
!function_exists('html') && exit('ERR');

if(!$Apower['weixin_msg']){
	showmsg('��ûȨ��!');
}

$access_token = wx_getAccessToken();

if($job=='list'&&$Apower[weixin_msg]){
	$page||$page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$SQL="WHERE fid=0 ";
	$showpage=getpage("{$pre}weixinmsg","$SQL","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query("SELECT * FROM {$pre}weixinmsg $SQL ORDER BY id DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		if($rs[type]==1){
			$rs[content]="<a href=\"https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$rs[url]\"><em class='pic'>ͼƬ</em></a>";
		}elseif($rs[type]==2){
			$rs[content]="<a href=\"https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$rs[url]\"><em class='sound'>����</em></a>";
		}elseif($rs[type]==3){
			$rs[content]="<a href=\"https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$rs[url]\"><em class='video'>��Ƶ</em></a>";
		}else{
			$rs[content]=get_word($rs[content],120);
		}
		$rs[reply]=List_replyMsg($rs[id]);
		$listdb[]=$rs;
	}	
}
elseif($action=="delete"&&$Apower[weixin_msg]){
	if(!$id_db&&$id){
		$id_db[]=$id;
	}
	foreach($id_db AS $id){
		$db->query("DELETE FROM `{$pre}weixinmsg` WHERE id='$id'");
		$db->query("DELETE FROM `{$pre}weixinmsg` WHERE fid='$id'");
	}
	jump("ɾ���ɹ�","index.php?lfj=$lfj&job=list");
}
elseif($action=='reply'){
	if($id&&$content){
		$id = filtrate($id);
		$content = filtrate($content);
		$db->query("INSERT INTO `{$pre}weixinmsg` ( `fid` , `uid` ,`username` ,`posttime` , `content` ) VALUES ('$id','$lfjuid','$lfjid','$timestamp','$content')");
		
		$rs = $db->get_one("SELECT D.weixin_api FROM `{$pre}weixinmsg` G LEFT JOIN `{$pre}memberdata` D ON G.uid=D.uid WHERE G.id='$id' ");
		send_wx_msg($rs[weixin_api],$content);
	}
	jump("�ظ��ɹ�","index.php?lfj=$lfj&job=list",0);
}
require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/weixin_msg/list.htm");
require(dirname(__FILE__)."/"."foot.php");

function List_replyMsg($id){
	global $db,$pre;
	$query = $db->query("SELECT * FROM `{$pre}weixinmsg` WHERE fid='$id' ORDER BY id DESC");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$listdb[]=$rs;
	}
	return $listdb;
}
?>