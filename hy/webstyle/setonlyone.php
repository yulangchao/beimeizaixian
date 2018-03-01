<?php
require(dirname(__FILE__)."/global.php");

if(!$lfjid){
	showerr("请先登录");
}
if($uid!=$lfjuid){
	showerr("只能对自己的店铺进行设置");
}

$styledb=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
$styleword = unserialize(stripslashes($styledb[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='$type' AND stylename='$stylename'");
$styleword1 = unserialize(stripslashes($styledb1[config]));

if($job=='editonlyone'){
	$tag = filtrate($tag);	
	$vals=$_GET[vals];
	$vals = filtrate($vals);
	$styleword[$tag]=$vals;	
	$config = addslashes(serialize($styleword));

	//修改当前样式参数
	edit_styles($config);
	exit;
}

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

require_once(WebStyleDir."/tpl/onlyone.htm");
?>