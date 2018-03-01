<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapbencandy.php?fid=$fid&id=$id");
	exit;
}

include_once(Mpath."data{$GLOBALS[webdb][web_dir]}/guide_fid.php");

/**
*��ȡ��Ŀ��ģ�����ò���
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID����!");
}

/**
*ģ�������ļ�
**/
$field_db = $module_DB[$fidDB[mid]][field];


$db->query("UPDATE {$_pre}content SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");


/**
*��ȡ��Ϣ���ĵ�����
**/
$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");


if(!$rsdb){
	showerr("���ݲ�����");
}
elseif($rsdb[fid]!=$fid){
	showerr("FID����!!!");
}
elseif($rsdb[yz]!=1&&!$web_admin){
	if($rsdb[yz]==2){
		showerr("����վ������,���޷��鿴");
	}else{
		showerr("��ûͨ�����");
	}
}


$city_id = $rsdb[city_id];

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title] - {$city_DB[name][$city_id]}$fidDB[name] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags($rsdb[keywords]));
$titleDB[description]	= filtrate(get_word(preg_replace('/<([^<]*)>/is',"",$rsdb[content]),200).strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


/**
*����Ϣ�����ֶεĴ���
**/
$Module_db->hidefield=true;
$Module_db->classidShowAll=true;
$Module_db->showfield($field_db,$rsdb,'show');



$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);

$numtag=substr(number_format($id/1000000,6),2);

//����ҳ����ͷ����β��
get_show_tpl($head_tpl,$foot_tpl,$main_tpl);

//��ȡ��ǩ����
$template_file=getTpl("bencandy_$fidDB[mid]",$main_tpl);
fetch_label_value(array('pagetype'=>'3','file'=>$template_file,'module'=>$webdb['module_id']));
/* ���� */
$fendb[fen1][name]="����";
$fendb[fen2][name]="����";
$fendb[fen3][name]="��λ";

$fendb[fen1][set] || $fendb[fen1][set]="1=������\r\n2=���պ�\r\n3=����";
$fendb[fen2][set] || $fendb[fen2][set]="1=��\r\n2=��\r\n3=��";
$fendb[fen3][set] || $fendb[fen3][set]="1=ƫ��\r\n2=�е�\r\n3=ʵ��";

$fen1=setfen("fen1",$fendb[fen1][name],$fendb[fen1][set]);
$fen2=setfen("fen2",$fendb[fen2][name],$fendb[fen2][set]);
$fen3=setfen("fen3",$fendb[fen3][name],$fendb[fen3][set]);

require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");

function setfen($name,$title,$set){
	$detail=explode("\r\n",$set);
	foreach( $detail AS $key=>$value){
		$d=explode("=",$value);
		$shows.="<option value='$d[0]' style='color:blue;'>$d[1]</option>";
	}
	$shows="<div> <select name='$name' id='$name'><option value=''>{$title}</option>$shows</select><span class='xx_bg'></span></div>";
	//$shows="{$title}:<select name='$name' id='$name'><option value=''>��ѡ��</option>$shows</select>";
	return $shows;
}
?>