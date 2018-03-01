<?php
require_once("global.php");

if(!$lfjuid){
	die('没有登录！');
}

if($job=='changenum'){
	$joins=intval($joins);
	$id=intval($id);
	if($joins<1){
		$db->query(" DELETE FROM `{$_pre}car` WHERE id='$id' ");
	}else{
		$db->query("UPDATE {$_pre}car SET joins='$joins' WHERE id='$id'");
	}
}

if($act=='joincar'){
	$checkdb=$db->get_one("SELECT * FROM {$_pre}car WHERE uid=$lfjuid AND cid='$cid'");
	if(!$checkdb){
		$db->query("INSERT INTO `{$_pre}car` (`cid`, `joins`, `uid`, `type`) VALUES ('$cid','1','$lfjuid','1')");
	}
}

$query = $db->query("SELECT A.*,C.fid,C.title,C.price,C.uid AS shopuid FROM {$_pre}car A LEFT JOIN {$_pre}content C ON A.cid=C.id WHERE A.uid=$lfjuid AND C.uid='$cuid'");
$show="";
while($rs = $db->fetch_array($query)){
	if($act=='clearShopCar' && $rs[shopuid]==$cuid){
		$db->query(" DELETE FROM `{$_pre}car` WHERE id='$rs[id]' ");
	}else{
		$show.="<ul class='shopcar$rs[id]'>\r\n";
		$show.="<li class='li1'><a href='bencandy.php?fid=$rs[fid]&id=$rs[cid]'>$rs[title]</a></li>\r\n";
		$show.="<li class='li2'>\r\n";
		$show.="<span onclick='moveshop($rs[id])'>-</span>\r\n";
		$show.="<input type='text' class='shopnum' value='$rs[joins]' onBlur='ckshopnums()' readOnly='true'/>\r\n";
		$show.="<span onclick='addshop($rs[id])'>+</span>\r\n";	
		$show.="</li>\r\n";
		$show.="<li class='li3'>&yen;<span>$rs[price]</span></li>\r\n";
		$show.="</ul>\r\n";
	}
}
if($show==""){
	$show.="<div style='text-align:center;padding:15px;color:red;'>购物车没有任何商品</div>";
}
die($show);
?>