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
	//�޸ĵ�ǰ��ʽ����
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
	//�޸ĵ�ǰ��ʽ����
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
	//�޸ĵ�ǰ��ʽ����
	edit_styles($config);
	exit;
}
if($job=='edit_hide_style'){
	$styleword[$tag]=$slide_hide;
	$config = addslashes(serialize($styleword));
	//�޸ĵ�ǰ��ʽ����
	edit_styles($config);
	exit;
}
if($job=='selectmenu'){
	unset($menudbs);
	$menudbs=array(
		array('title' => '�̼���ҳ','url' => 'index.php',),
		array('title' => '�̼ҽ���','url' => 'about.php',),
		array('title' => '�̼�����','url' => 'news.php',),
		array('title' => '�̼Ҳ�Ʒ','url' => 'shop.php',),
		array('title' => 'ͼƬչʾ','url' => 'hypic.php',),
		array('title' => '�˿͵���','url' => 'hydianping.php',),
		array('title' => '�̼���Ʒ','url' => 'gift.php',),
		array('title' => '������Ϣ','url' => 'coupon.php',),
		array('title' => '��ϵ��ʽ','url' => 'contact.php',),
		array('title' => '�ÿ�����','url' => 'msg.php',),
		array('title' => '��Ƹ��Ϣ','url' => 'hr.php',),
		array('title' => '������Ϣ','url' => '2shou.php',),
		array('title' => '������Ϣ','url' => 'fenlei.php',),
		array('title' => '������Ϣ','url' => 'house.php',),
		array('title' => '�̼һ','url' => 'tg.php',),
		array('title' => '�̼��Ź�','url' => 'shoptg.php',),
		array('title' => '�̼ҵ���','url' => 'dianpinginfo.php',),
		array('title' => '�ҵķ���','url' => 'wei.php',),
		array('title' => '�ҵ�����','url' => 'myartic.php',),
		array('title' => '���ŷ���','url' => 'fuwu.php',),
		array('title' => 'װ����Ϣ','url' => 'zhuangxiu.php',),
		array('title' => '��������','url' => 'waimai.php',),
	);
	$showlist='';
	foreach($menudbs AS $key=>$rs){
		$showlist.="<div class='listshop' onClick=\"select_Menu1('$rs[url]','$rs[title]',$num)\">$rs[title]</div>";
	}	
	die($showlist);
}

require_once(WebStyleDir."/tpl/menus.htm");
?>