<?php
require(dirname(__FILE__)."/global.php");

$styledb=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
$styleword = unserialize(stripslashes($styledb[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='$type' AND stylename='$stylename'");
$styleword1 = unserialize(stripslashes($styledb1[config]));

if($job=='editlist'){
	$styleword[$tag][$types]=$vals;
	$config = addslashes(serialize($styleword));
	edit_styles();
	exit;
}

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
}

require_once(TheSetDir."/tpl/lists.htm");

function edit_styles(){
	global $db,$_pre,$uid,$type,$stylename,$config;
	$db->query("UPDATE `{$_pre}style` SET config='$config' WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
}
?>