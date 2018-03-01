<?php
require_once(dirname(__FILE__)."/global.php");
header('Content-Type: text/html; charset='.WEB_LANG);

if($action=='refresh_comment_num'){
	@extract($db->get_one("SELECT comments FROM `{$_pre}content` WHERE id='$id'"));
	die($comments);
}

if($action=="post"){
	if(!$content){	
		die("内容不能为空");
	}	
	$yz=1;
	if(!$web_admin){
		if($webdb[Info_PostCommentType]==2){
			die('管理员设置不可以发表评论');
		}elseif($webdb[Info_PostCommentType]==1&&!$lfjuid){
			die('管理员设置游客不可以发表评论');
		}		
		if($webdb[Info_PassCommentType]==2){
			$yz=0;
		}elseif($webdb[Info_PassCommentType]==1&&!$lfjuid){
			$yz=0;
		}
	}

	if(!table_field("{$_pre}comments","quoteid")){
		$db->query("ALTER TABLE  `{$_pre}comments` ADD  `quoteid` INT( 10 ) NOT NULL ;");		
	}
	$content=filtrate($content);
	$content=replace_bad_word($content);
	$rss=$db->get_one(" SELECT * FROM {$_pre}content WHERE id='$id' ");
	if(!$rss){
		die("原数据不存在");
	}
	$username || $username=$lfjid;
	$db->query("INSERT INTO `{$_pre}comments` (`cuid`,  `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`,`quoteid`) VALUES ('$rss[uid]','$id','$rss[fid]','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz','$cid')");
	$db->query(" UPDATE {$_pre}content SET comments=comments+1 WHERE id='$id' ");
}

$rows=6;
$page||$page=1;
$min=($page-1)*$rows;
$query=$db->query("SELECT A.*,B.icon FROM `{$_pre}comments` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.id=$id $SQL ORDER BY A.cid DESC LIMIT $min,$rows");
$showcomment='';
while($rs=$db->fetch_array($query)){
	$rs[content]=preg_replace("/iconstarsss([\d]+)iconendsss/is","<img src='./images/wap/icon1/\\1.png'/>",$rs[content]);
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	if($rs[icon]){
		$rs[icon]=tempdir($rs[icon]);
	}
	$showcomment.="<dl class='dlcomments'>\r\n";
	$showcomment.="<dt><A HREF='$webdb[www_url]/member/userinfo.php?uid=$rs[uid]'><img src='$rs[icon]' onerror=\"this.src='$webdb[www_url]/images/default/nobody.gif'\"/></A><span>{$rs[username]}</span><em>$rs[posttime]</em></dt>\r\n";
	$showcomment.="<dd>$rs[content]</dd>\r\n";
	$showcomment.="</dl>\r\n";
}
$showpage=getpage("`{$_pre}comments` A"," WHERE A.id='$id' $SQL","?fid=$fid&id=$id",$rows);
$showpage=preg_replace("/\?fid=([\d]+)&id=([\d]+)&page=([\d]+)/is","javascript:Get_This_comments('get_comment.php?fid=\\1&id=\\2&page=\\3')",$showpage);
if($showpage){
	$showcomment.="<div class='commentpage'>".$showpage."</div>";
}
if($showcomment!=''){
	echo $showcomment;
}else{
	echo '<div class="NoComment">目前没有任何回复！</div>';
}	
?>