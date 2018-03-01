<?php
require(dirname(__FILE__)."/global.php");

$styledb=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
$styleword = unserialize(stripslashes($styledb[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='$type' AND stylename='$stylename'");
$styleword1 = unserialize(stripslashes($styledb1[config]));

if($job=='editcont'){
	$vals = filtrate($vals);
	$styleword[$tag][$types]=$vals;
	$config = addslashes(serialize($styleword));
	edit_styles();
	exit;
}

if($job=='editwords'){
	$words=$_POST[words];
	$words = filtrate($words);
	$styleword[$tag][words]=$words;
	$config = addslashes(serialize($styleword));
	edit_styles();
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$Murl/waphome.php?styles=$stylename&job=set'>";
	exit;
}

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

require_once(TheSetDir."/tpl/content.htm");

function edit_styles(){
	global $db,$_pre,$uid,$type,$stylename,$config;
	$db->query("UPDATE `{$_pre}style` SET config='$config' WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
}
?>