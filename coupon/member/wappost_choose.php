<?php
require_once("global.php");

if($job=="showlist"){
	echo showthissorts($fup,$list);
	exit;
}
$listsorts0=showthissorts(0,0);

//require(ROOT_PATH."inc/head.php");
//require(getTpl("pub"));
//require(ROOT_PATH."inc/foot.php");
require(ROOT_PATH."member/waphead.php");
require(dirname(__FILE__)."/"."template/wappost_choose.htm");
require(ROOT_PATH."member/wapfoot.php");

function showthissorts($fup,$list){
	global $db,$_pre;
	$list++;
	$show="<ul>\n";
	$query = $db->query("SELECT * FROM `{$_pre}sort` WHERE fup='$fup' ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if($rs['type']==1){
			$show .= "<li onClick=\"showmore(".$rs['fid'].",".$list.",$(this))\"><div class=\"more\">".$rs['name']."</div></li>\n";
		}else{
			$show .= "<li><div><a href=\"wappost.php?fid=".$rs['fid']."\">".$rs['name']."</a></div></li>\n";
		}
	}
	$show .= "</ul>";
	return $show;
}
?>