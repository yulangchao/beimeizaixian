<?php
require("global.php");

$rs=$db->get_one("SELECT * FROM `{$_pre}content` WHERE id='$cid'");
if($job=="guestlist" || $job=="mylist"){
	if(!$lfjuid){
		showerr("你无权限");
	}
}else{
	if(!$lfjuid||$rs[uid]!=$lfjuid){
	showerr("你无权限");
	}
}
if($action=='yz'){
	$db->query("UPDATE {$_pre}join SET yz='$yz' WHERE id='$id' AND cid='$cid'");
	refreshto($FROMURL,'操作成功',0);
}

$mid=2;
$field_db = $module_DB[$mid]['field'];
$Lrows=10;
unset($listdb);

if($page<1){
	$page=1;
}
$min=($page-1)*$Lrows;

if($job=="guestlist"){
	$showpage=getpage("{$_pre}join A","WHERE A.cuid=$lfjuid","?job=guestlist&",$Lrows);	
	$query = $db->query("SELECT A.*,C.*,A.id AS sid ,A.fid AS sfid FROM {$_pre}join A LEFT JOIN {$_pre}content_$mid C ON A.id=C.rid WHERE A.cuid='$lfjuid' ORDER BY A.posttime DESC LIMIT $min,$Lrows");
	while($rs = $db->fetch_array($query)){
		$ts=$db->get_one("SELECT title FROM `{$_pre}content` WHERE id='$rs[cid]'");
		$rs[username] || $rs[username] = $rs[ip];
		$rs[picurl] = tempdir($rs[icon]);
		$rs[title] = $ts[title];
		$rs[posttime] = date("Y-m-d H:i:s",$rs[posttime]);
	
		$rs[ifyz] = $rs[yz]?"<a href='?action=yz&yz=0&cid=$rs[cid]&id=$rs[sid]' style='color:red;'>已审核</a>":"<a href='?action=yz&yz=1&cid=$rs[cid]&id=$rs[sid]'>待审核</a>";
		$rs[del] = "<a href='join.php?action=del&id=$rs[sid]&cid=$rs[cid]&fid=$rs[sfid]'>踢除</a>";
		$listdb[]=$rs;
	}
	$listname ="客户的预约列表";
	require(ROOT_PATH."member/waphead.php");
	require(dirname(__FILE__)."/template/wapguestlist.htm");
	require(ROOT_PATH."member/wapfoot.php");
}elseif($job=="mylist"){
	$showpage=getpage("{$_pre}join A","WHERE A.uid=$lfjuid","?job=mylist&",$Lrows);
	$query = $db->query("SELECT A.*,C.*,A.id AS sid ,A.fid AS sfid FROM {$_pre}join A LEFT JOIN {$_pre}content_$mid C ON A.id=C.rid WHERE A.uid='$lfjuid' ORDER BY A.posttime DESC LIMIT $min,$Lrows");
	while($rs = $db->fetch_array($query)){
		$ts=$db->get_one("SELECT title FROM `{$_pre}content` WHERE id='$rs[cid]'");
		$rs[username] || $rs[username] = $rs[ip];
		$rs[picurl] = tempdir($rs[icon]);
		$rs[title] = $ts[title];
		$rs[posttime] = date("Y-m-d H:i:s",$rs[posttime]);
	
		$rs[ifyz] = $rs[yz]?"<a style='color:red'>已审核</a>":"<a>待审核</a>";
		$rs[del] = $rs[yz]?"":"<a href='join.php?action=del&id=$rs[sid]&cid=$rs[cid]&fid=$rs[sfid]'>踢除</a>";
	
		$listdb[]=$rs;
	}
	$listname ="我的预约列表";
	require(ROOT_PATH."member/waphead.php");
	require(dirname(__FILE__)."/template/wapguestlist.htm");
	require(ROOT_PATH."member/wapfoot.php");
}else{
	$showpage=getpage("{$_pre}join A","WHERE A.cid=$cid","?cid=$cid",$Lrows);		
	$query = $db->query("SELECT A.*,C.*,A.id AS sid ,A.fid AS sfid FROM {$_pre}join A LEFT JOIN {$_pre}content_$mid C ON A.id=C.rid WHERE A.cid='$cid' ORDER BY A.posttime DESC LIMIT $min,$Lrows");
	while($rs = $db->fetch_array($query)){
		$Module_db->showfield($field_db,$rs,'list');
		$rs[username] || $rs[username] = $rs[ip];
		$rs[picurl] = tempdir($rs[icon]);
		$rs[posttime] = date("Y-m-d H:i:s",$rs[posttime]);
	
		$rs[ifyz] = $rs[yz]?"<a href='?action=yz&yz=0&cid=$rs[cid]&id=$rs[sid]' style='color:red;'>已审核</a>":"<a href='?action=yz&yz=1&cid=$rs[cid]&id=$rs[sid]'>待审核</a>";
	
		$listdb[]=$rs;
	}
	require(ROOT_PATH."member/waphead.php");
	require(dirname(__FILE__)."/template/wapjoinlist.htm");
	require(ROOT_PATH."member/wapfoot.php");
}
?>