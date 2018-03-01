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

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

if($job=='editwords'){
	$tag = filtrate($tag);	
	$word=$_POST[word];
	//$word = filtrate($word);
	$word = En_TruePath($word);
	$word = preg_replace('/javascript/i','java script',$word);	//过滤js代码
	$word = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$word);	//过滤框架代码

	$styleword[$tag][word]=$word;
	$config = addslashes(serialize($styleword));

	//修改当前样式参数
	edit_styles($config);
	
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?type=$type&stylename=$stylename&uid=$uid&tag=$tag'>";
	exit;
}

require_once(WebStyleDir."/tpl/imgword.htm");
?>