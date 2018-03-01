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

if($job=='editimgs'){
	if($types=='img'){
		$oldpic=$styleword[$tag][$types];
	}
	$styleword[$tag][$types]=$vals;
	$config = addslashes(serialize($styleword));

	//修改当前样式参数
	edit_styles($config);
	if($types=='img'){
		delete_attachment($lfjuid,tempdir($oldpic));
		$picurl=tempdir($vals);
		die($picurl);
	}
	exit;
}
if($job=='listmyshop'){
	$rows=20;
	$page||$page=1;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM `{$pre}shop_content` WHERE yz=1 AND uid='$uid' ORDER BY id DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$totalNum=$RS['FOUND_ROWS()'];
	$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
	$showlist='';
	while($rs = $db->fetch_array($query)){
		//$showurl = "$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]###id=$rs[id]";
		$showlist.="<div class='listshop' onClick=\"SelectShopurl($rs[fid],$rs[id])\">$rs[title]</div>";
	}
	$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:Show_My_Shops(\\2)",$showpage);
	if($showpage){
		$showlist.="<div class='shoppage'>$showpage</div>";
	}
	$showlist||$showlist="<div class='NoShop'>您还没有任何商品！</div>";
	die($showlist);
}

if(!$styleword[$tag]){
	$styleword[$tag]=$styleword1[$tag];
	$checkmyno=1;
}

require_once(WebStyleDir."/tpl/imgs.htm");
?>