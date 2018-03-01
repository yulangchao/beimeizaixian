<?php
require(dirname(__FILE__)."/"."global.php");


if($do=='del'){
	$db->query("DELETE FROM {$_pre}collectionhy WHERE cid='$cid' AND uid='$lfjuid'");
}
if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

$showpage=getpage(" {$_pre}collectionhy B ","WHERE B.uid=$lfjuid","?job=$job",$rows);
$listdb=array();
$query = $db->query("SELECT B.cid,B.posttime AS ctime,A.* FROM {$_pre}company A LEFT JOIN {$_pre}collectionhy B ON A.id=B.id WHERE B.uid=$lfjuid ORDER BY B.cid DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	if($rs[yz]==2){
		$rs[state]="<A style='color:red;'>作废</A>";
	}elseif($rs[yz]==1){
		$rs[state]="已审";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;'>待审</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;'>已推荐</A>";
	}else{
		$rs[levels]="未推荐";
	}
	$rs[ctime]=date("Y-m-d",$rs[ctime]);
	$rs[title]=get_word($rs[full_title]=$rs[title],54);
	//$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);
	$listdb[]=$rs;
}

require(ROOT_PATH."member/head.php");
require(dirname(__FILE__)."/"."template/collectionhy.htm");
require(ROOT_PATH."member/foot.php");
?>