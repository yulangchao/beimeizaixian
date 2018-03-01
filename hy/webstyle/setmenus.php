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
if($job=='editmenu'){

	if($types=='url'){
		$vals=$_POST[vals];
	}else{
		$vals=$_GET[vals];
	}
	$vals = filtrate($vals);

	$styleword[$tag][$num][$types]=$vals;
	$config = addslashes(serialize($styleword));
	//修改当前样式参数
	edit_styles($config);
	exit;
}
if($job=='edit_url_name'){
	$url=$_GET[url];
	$title=$_GET[title];
	$url = filtrate($url);
	$title = filtrate($title);

	$styleword[$tag][$num][url]=$url;
	$styleword[$tag][$num][title]=$title;
	print_r($styleword[$tag]);
	$config = addslashes(serialize($styleword));
	//修改当前样式参数
	edit_styles($config);
	exit;
}
if($job=='DelMenu'){
	
	$i=0;
	$newarray='';
	foreach($styleword[$tag] AS $key=>$value){
		if($key==$num){
			unset($styleword[$tag][$key]);
		}else{
			$styleword[$tag][$i]=$value;
			$newarray[$i]=$value;
		}
		$i++;
	}
	unset($styleword[$tag]);
	$styleword[$tag]=$newarray;

	$config = addslashes(serialize($styleword));
	//修改当前样式参数
	edit_styles($config);
	exit;
}
if($job=='edit_hide_style'){
	$styleword[$tag]=$slide_hide;
	$config = addslashes(serialize($styleword));
	//修改当前样式参数
	edit_styles($config);
	exit;
}
if($job=='selectmenu'){
	unset($menudbs);
	$menudbs=array(
		array('title' => '商家首页','url' => 'index.php',),
		array('title' => '商家介绍','url' => 'about.php',),
		array('title' => '商家新闻','url' => 'news.php',),
		array('title' => '商家产品','url' => 'shop.php',),
		array('title' => '图片展示','url' => 'hypic.php',),
		array('title' => '顾客点评','url' => 'hydianping.php',),
		array('title' => '商家礼品','url' => 'gift.php',),
		array('title' => '促销信息','url' => 'coupon.php',),
		array('title' => '联系方式','url' => 'contact.php',),
		array('title' => '访客留言','url' => 'msg.php',),
		array('title' => '招聘信息','url' => 'hr.php',),
		array('title' => '二手信息','url' => '2shou.php',),
		array('title' => '分类信息','url' => 'fenlei.php',),
		array('title' => '房产信息','url' => 'house.php',),
		array('title' => '商家活动','url' => 'tg.php',),
		array('title' => '商家团购','url' => 'shoptg.php',),
		array('title' => '商家点评','url' => 'dianpinginfo.php',),
		array('title' => '我的分享','url' => 'wei.php',),
		array('title' => '我的文章','url' => 'myartic.php',),
		array('title' => '上门服务','url' => 'fuwu.php',),
		array('title' => '装修信息','url' => 'zhuangxiu.php',),
		array('title' => '外卖店铺','url' => 'waimai.php',),
	);
	$showlist='';
	foreach($menudbs AS $key=>$rs){
		$showlist.="<div class='listshop' onClick=\"select_Menu1('$rs[url]','$rs[title]',$num)\">$rs[title]</div>";
	}	
	die($showlist);
}

require_once(WebStyleDir."/tpl/menus.htm");
?>