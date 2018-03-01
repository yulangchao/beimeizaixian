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
}
$styleword[slideHeight]||$styleword[slideHeight]=$styleword1[slideHeight];

if($job=='editonlyone'){
	$styleword[$tag]=$vals;
	$config = addslashes(serialize($styleword));
	//修改当前样式参数
	edit_styles($config);
	exit;
}
if($job=='editslide'){
	if($types=='title'){
		$vals=$_GET[vals];
	}
	if($types=='img'){
		$oldpic=$styleword[$tag][$num][$types];
		delete_attachment($lfjuid,tempdir($oldpic));
		$picurl=tempdir($vals);
	}
	$styleword[$tag][$num][$types]=$vals;
	$config = addslashes(serialize($styleword));
	edit_styles($config);
	die($picurl);
}
if($job=='listmyshop'){
	$rows=8;
	$page||$page=1;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM `{$pre}shop_content` WHERE yz=1 AND uid='$uid' ORDER BY id DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$totalNum=$RS['FOUND_ROWS()'];
	$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
	$showlist='';
	while($rs = $db->fetch_array($query)){
		$rs[picurl]||$rs[picurl]="$webdb[www_url]/images/default/noimg.jpg";
		$showlist.="<div class='listshop' onClick=\"selectshopslide('$rs[picurl]','$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]','$rs[title]',$num)\">$rs[title]</div>";
	}
	$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:Show_My_Shops(\\2,$num)",$showpage);
	if($showpage){
		$showlist.="<div class='shoppage'>$showpage</div>";
	}
	$showlist||$showlist="<div class='NoShop'>您还没有任何商品！</div>";
	die($showlist);
}
if($job=='delslides'){
	$oldpic=$styleword[$tag][$num][img];
	unset($styleword[$tag][$num]);
	$config = addslashes(serialize($styleword));
	edit_styles($config);
	delete_attachment($lfjuid,tempdir($oldpic));
	exit;
}
require_once(WebStyleDir."/tpl/slide.htm");
?>