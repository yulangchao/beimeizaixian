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

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

if($job=='editwords'){
	$tag = filtrate($tag);	
	$word=$_POST[word];
	//$word = filtrate($word);
	$word = En_TruePath($word);
	$word = preg_replace('/javascript/i','java script',$word);	//����js����
	$word = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$word);	//���˿�ܴ���

	$styleword[$tag][word]=$word;
	$config = addslashes(serialize($styleword));

	//�޸ĵ�ǰ��ʽ����
	edit_styles($config);
	
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?type=$type&stylename=$stylename&uid=$uid&tag=$tag'>";
	exit;
}

require_once(WebStyleDir."/tpl/imgword.htm");
?>