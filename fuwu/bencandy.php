<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapbencandy.php?fid=$fid&id=$id");
	exit;
}

include_once(Mpath."data{$GLOBALS[webdb][web_dir]}/guide_fid.php");

/**
*获取栏目与模块配置参数
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID有误!");
}

/**
*模型配置文件
**/
$field_db = $module_DB[$fidDB[mid]][field];


$db->query("UPDATE {$_pre}content SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");


/**
*获取信息正文的内容
**/
$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");


if(!$rsdb){
	showerr("内容不存在");
}
elseif($rsdb[fid]!=$fid){
	showerr("FID有误!!!");
}
elseif($rsdb[yz]!=1&&!$web_admin){
	if($rsdb[yz]==2){
		showerr("回收站的内容,你无法查看");
	}else{
		showerr("还没通过审核");
	}
}


$city_id = $rsdb[city_id];

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title] - {$city_DB[name][$city_id]}$fidDB[name] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags($rsdb[keywords]));
$titleDB[description]	= filtrate(get_word(preg_replace('/<([^<]*)>/is',"",$rsdb[content]),200).strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


/**
*对信息内容字段的处理
**/
$Module_db->hidefield=true;
$Module_db->classidShowAll=true;
$Module_db->showfield($field_db,$rsdb,'show');



$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);

$numtag=substr(number_format($id/1000000,6),2);

//内容页个性头部与尾部
get_show_tpl($head_tpl,$foot_tpl,$main_tpl);

//获取标签内容
$template_file=getTpl("bencandy_$fidDB[mid]",$main_tpl);
fetch_label_value(array('pagetype'=>'3','file'=>$template_file,'module'=>$webdb['module_id']));
/* 点评 */
$fendb[fen1][name]="总评";
$fendb[fen2][name]="服务";
$fendb[fen3][name]="价位";

$fendb[fen1][set] || $fendb[fen1][set]="1=不满意\r\n2=还凑合\r\n3=满意";
$fendb[fen2][set] || $fendb[fen2][set]="1=差\r\n2=中\r\n3=好";
$fendb[fen3][set] || $fendb[fen3][set]="1=偏贵\r\n2=中等\r\n3=实惠";

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
	//$shows="{$title}:<select name='$name' id='$name'><option value=''>请选择</option>$shows</select>";
	return $shows;
}
?>