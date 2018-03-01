<?php
require("global.php");

$linkdb=array();

$rows=20;
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;
$showpage=getpage("{$_pre}content","WHERE uid='$lfjuid' AND yz!=2","?job=$job",$rows);
$query = $db->query("SELECT * FROM {$_pre}content WHERE uid='$lfjuid' AND yz!=2 ORDER BY id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	if($rs[title]==''){
		$ts = $db->get_one("SELECT * FROM {$_pre}content_1 WHERE id='$rs[id]'");
		$rs[title] = get_txt_word( $ts[content] , 60 );
	}
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	//$rs[levels]=$rs[levels]?"<font color=red>已推荐</font>":"未推荐";
	if($rs[yz]==1){
		$rs[state]="<a href='javascript:;'><font color='blue'>已审核</font></a>";
	}elseif($rs[yz]==2){
		$rs[state]="<a href='javascript:;'><font color='#cccccc'>回收站</font></a>";
	}else{
		$rs[state]="<a href='javascript:;'>未审核</a>";
	}
	
	$listdb[]=$rs;
}
require(ROOT_PATH."member/waphead.php");
require("template/waplist.htm");
require(ROOT_PATH."member/wapfoot.php");

?>