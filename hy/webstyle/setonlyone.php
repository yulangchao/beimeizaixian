<?php
require(dirname(__FILE__)."/global.php");

if(!$lfjid){
	showerr("���ȵ�¼");
}
if($uid!=$lfjuid){
	showerr("ֻ�ܶ��Լ��ĵ��̽�������");
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

	//�޸ĵ�ǰ��ʽ����
	edit_styles($config);
	exit;
}

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

require_once(WebStyleDir."/tpl/onlyone.htm");
?>