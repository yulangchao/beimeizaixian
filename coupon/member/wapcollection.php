<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("�㻹û��¼");
}
if($do=='del'){
	$db->query("DELETE FROM {$_pre}collection WHERE cid='$cid' AND uid='$lfjuid'");
}

if(!is_table("{$_pre}collection")){
	$SQL="CREATE TABLE `{$_pre}collection` (
			`cid` mediumint( 7 ) NOT NULL AUTO_INCREMENT ,
			`id` mediumint( 7 ) NOT NULL default '0',
			`uid` mediumint( 7 ) NOT NULL default '0',
			`posttime` int( 10 ) NOT NULL default '0',
			PRIMARY KEY ( `cid` ) 
			) TYPE = MYISAM ;";
	$db->insert_file('',$SQL);
}


if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

$showpage=getpage(" {$_pre}collection B ","WHERE B.uid=$lfjuid","?job=$job",$rows);
$listdb=array();
$query = $db->query("SELECT A.*,B.posttime AS ctime,B.cid FROM {$_pre}collection B LEFT JOIN {$_pre}content A ON B.id=A.id WHERE B.uid=$lfjuid ORDER BY B.cid DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$listdb[]=$rs;
}
foreach( $listdb AS $key=>$rs){
	if($rs[yz]==2){
		$rs[state]="<A style='color:red;'>����</A>";
	}elseif($rs[yz]==1){
		$rs[state]="����";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;'>����</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;'>���Ƽ�</A>";
	}else{
		$rs[levels]="δ�Ƽ�";
	}
	$rs[ctime]=date("Y-m-d",$rs[ctime]);
	$rs[title]=get_word($rs[full_title]=$rs[title],54);
	//$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);
	$listdb[$key]=$rs;
}

require(ROOT_PATH."member/waphead.php");
require(dirname(__FILE__)."/"."template/wapcollection.htm");
require(ROOT_PATH."member/wapfoot.php");
?>