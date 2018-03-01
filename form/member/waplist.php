<?php
require(dirname(__FILE__).'/global.php');

 
$showpage=getpage("{$_pre}content","WHERE uid='$lfjuid'",'',$rows);
$query = $db->query("SELECT A.*,B.name FROM {$_pre}content A LEFT JOIN {$_pre}module B ON A.mid=B.id WHERE A.uid='$lfjuid' ORDER BY A.list DESC");
while($rs = $db->fetch_array($query)){
	$rs[posttime] = date('Y-m-d H:i:s',$rs[posttime]);
	if($rs[yz]){
		$rs[state]="<a style='color:#f90;'>“—…Û∫À</a>";
	}else{
		$rs[state]="<a style='color:#1b9ee9;'>Œ¥…Û∫À</a>";
	}
	$listdb[]=$rs;
}

require(ROOT_PATH."/member/waphead.php");
require(dirname(__FILE__).'/template/waplist.htm');
require(ROOT_PATH."/member/wapfoot.php");

?>