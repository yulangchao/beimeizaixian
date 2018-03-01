<?php
require_once("global.php");

if($atc=='List_repaly'&&$cid){
	$page||$page=1;
	$rows=5;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}comments WHERE cids='$cid' ORDER BY cid DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$totalNum=$RS['FOUND_ROWS()'];
	$showpage=getpage("","","?cid=$cid",$rows,$totalNum);
	$showpage=preg_replace("/\?cid=([\d]+)&page=([\d]+)/is","javascript:ShowMoreRepaly(\\1,\\2)",$showpage);

	while($rs = $db->fetch_array($query)){
		$rs[posttime]=format_showtime($rs[posttime]);
		echo "<div class='lists'><A HREF='$webdb[www_url]/member/userinfo.php?uid=$rs[uid]'>$rs[username]</A> <span>$rs[content]</span> <em>$rs[posttime]</em></div>\r\n";
	}
	if($showpage){
		echo '<div class="ShowPage">'.$showpage.'</div>';
	}
	exit;
}

if(!$lfjid){
	die("还没有登录！");
}

if($atc=='comment'&&$id&&$content){
	$username=$lfjid;
	$username=filtrate($username);
	$content=filtrate($content);
	//过滤不健康的字
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);
	$rss=$db->get_one(" SELECT * FROM {$_pre}content WHERE id='$id' ");
	if(!$rss){
		die("原数据不存在");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;

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
	$db->query("INSERT INTO `{$_pre}comments` (`cuid`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `cids`) VALUES ('$rss[uid]','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz','$cid')");

	$db->query(" UPDATE {$_pre}content SET comments=comments+1 WHERE id='$id' ");
	$listnum||$listnum=0;
	if($cid>0){
		$show=get_comment_repeat($cid,$listnum);
		$SQL="SELECT M.* FROM {$_pre}comments C LEFT JOIN {$pre}memberdata M ON C.uid=M.uid WHERE C.cid='$cid'";
	}else{
		$show=get_this_comments($id,$listnum);
		$SQL="SELECT M.* FROM {$_pre}content C LEFT JOIN {$pre}memberdata M ON C.uid=M.uid WHERE C.id='$id'";
	}
	
	$mdb = $db->get_one($SQL);
	if($mdb[uid]!=$lfjuid && $mdb[weixin_api] && wx_check_attention($mdb[weixin_api]) ){
		$content = get_word(str_replace(array(' ','　',"\t","\n","\r"),array(''),preg_replace('/<([^<]*)>/is',"",$content)),200);
		send_wx_msg($mdb[weixin_api],"你发表的《{$rss[title]}》文章，有人回复了：“{$content}”，<a href=\"$Murl/wapbencandy.php?fid=$fid&id=$id\">点击查看详情</a>");
	}


	die($show);
}
if($atc=='delcomment'&&$id&&$cid&&$uid){
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}comments` WHERE cid='$cid' AND id='$id' AND uid='$uid'");
	delete_attachment($rsdb[uid],$rsdb[content]);
	$db->query(" DELETE FROM `{$_pre}comments` WHERE cid='$cid' AND id='$id' AND uid='$uid' ");
	$db->query(" DELETE FROM `{$_pre}comments` WHERE cids='$cid' AND id='$id' AND uid='$uid' ");
	$show=get_this_comments($id,$listnum);
	//$SQL="SELECT M.* FROM {$_pre}content C LEFT JOIN {$pre}memberdata M ON C.uid=M.uid WHERE C.id='$id'";
	die($show);
}
if($atc=='delcomment1'&&$cids&&$cid&&$uid){
	$db->query(" DELETE FROM `{$_pre}comments` WHERE cids='$cids' AND cid='$cid' AND uid='$uid' ");
	$show=get_comment_repeat($cids,$listnum);
	//$SQL="SELECT M.* FROM {$_pre}content C LEFT JOIN {$pre}memberdata M ON C.uid=M.uid WHERE C.id='$id'";
	die($show);
}
if($atc=='get_num'&&$id){
	@extract($db->get_one("SELECT COUNT(id) AS NUM FROM {$_pre}comments WHERE id='$id' AND cids='0'"));
	$totalcomments=intval($NUM);
	die("$totalcomments");
}
if($atc=='add_dig'&&$id){
	$rss=$db->get_one(" SELECT * FROM {$_pre}content WHERE id='$id' ");
	if(!$rss){
		die("原数据不存在");
	}else{
		$rs=$db->get_one(" SELECT * FROM {$_pre}digguser WHERE id='$id' AND uid='$lfjuid'");
		if($rs){
			die('NO');
		}else{
			$db->query("INSERT INTO `{$_pre}digguser` (`id`,`uid`,`posttime`) VALUES ('$id','$lfjuid','$timestamp')");
			if($types=='web'){
				$show=get_this_digguser1($id);
			}else{
				$show=get_this_digguser($id);
			}
			die($show);
		}
	}
}
if($atc=='get_dig'&&$id){
	@extract($db->get_one("SELECT COUNT(id) AS NUM FROM {$_pre}digguser WHERE id='$id'"));
	$totalcomments=intval($NUM);
	die("$totalcomments");
}

?>