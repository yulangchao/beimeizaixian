<?php
require(dirname(__FILE__)."/global.php");

if($action=="post"){
	if(!$lfjid){	
		die("��¼����ܷ�������");
	}
	if(!$content){	
		die("���ݲ���Ϊ��");
	}
	$content=filtrate($content);
	//���˲���������
	$username=replace_bad_word($lfjid);
	$content=replace_bad_word($content);
	$rss=$db->get_one(" SELECT * FROM {$_pre}home WHERE uid='$uid' ");
	if(!$rss){
		die("ԭ���ݲ�����");
	}
	if(is_utf8($content)||is_utf8($username)){
		$content=utf82gbk($content);
		$username=utf82gbk($username);
	}
	if(WEB_LANG=='utf-8'){
		$content=gbk2utf8($content);
		$username=gbk2utf8($username);
	}elseif(WEB_LANG=='big5'){
		require_once(ROOT_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("GB2312","BIG5",$content,ROOT_PATH."./inc/gbkcode/");
		$content = $cnvert->ConvertIT();

		$cnvert = new Chinese("GB2312","BIG5",$username,ROOT_PATH."./inc/gbkcode/");
		$username = $cnvert->ConvertIT();
	}
	$yz=1;//Ĭ��ͨ�����
	$db->query("INSERT INTO `{$_pre}dianping` (`cuid`, `type`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `fen1`, `fen2`, `fen3`, `fen4`, `fen5`, `price`, `keywords`, `keywords2`, `fen6`) VALUES ('$uid','0','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz','$fen1','$fen2','$fen3','$fen4','$fen5','$c_price','$c_keywords','$c_keywords2','$fen6')");

	$db->query(" UPDATE {$_pre}company SET dianping=dianping+1,`dianpingtime`='$timestamp' WHERE uid='$uid' ");

	set_user_log(6);	//�û�������־
}

if($job=='ActGood'){
	if(get_cookie("flowers_$cid")){
		die("err");
	}else{
		set_cookie("flowers_$cid",1,3600);
		$db->query("UPDATE `{$_pre}dianping` SET `flowers`=`flowers`+1 WHERE cid='$cid'");		
	}
	@extract($db->get_one("SELECT flowers FROM {$_pre}dianping  WHERE cid='$cid'" ));
	die($flowers);
}

?>