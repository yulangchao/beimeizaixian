<?php
require(dirname(__FILE__)."/global.php");
@include(dirname(__FILE__)."/data{$GLOBALS[webdb][web_dir]}/guide_fid.php");

//获取栏目与模块的配置文件
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");
if(!$fidDB){
	//showerr("栏目不存在");
}

$fidDB[descrip]=En_TruePath($fidDB[descrip],0);


//SEO
$titleDB['title']	= $fidDB['metatitle']?$fidDB['metatitle']:($fidDB['name']?$fidDB['name']:"分享列表");
$titleDB['keywords'] = $fidDB['metakeywords']?$fidDB['metakeywords']:$webdb['SEO_keywords'];
$titleDB['description'] = $fidDB['metadescription']?$fidDB['metadescription']:$webdb['SEO_description'];


$_url="list.php?fid=$fid";



//为获取标签参数
$chdb[main_tpl]=getTpl("list");


//标签
$ch_fid	= intval($fidDB[config][label_list]);		//是否定义了栏目专用标签
$ch_pagetype = 2;									//2,为list页,3,为bencandy页
$ch_module = $webdb[module_id];						//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
require(ROOT_PATH."inc/label_module.php");

unset($listdbs,$listdba);

$rows=$fidDB[maxperpage]>0?$fidDB[maxperpage]:($webdb[Infolist_row]>0?$webdb[Infolist_row]:24);

$page<1 && $page=1;
$min = ($page-1)*$rows;

$SQL='';
if( count($city_DB['name']) > 1 ){
	$SQL=" AND A.city_id='$city_id' ";
}

//小分类
if($fidDB[type]==0){
	$SQL="SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON A.id=B.id WHERE A.fid='$fid' $SQL ORDER BY A.id DESC LIMIT $min,$rows";
}else{
	$SQL="SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON A.id=B.id LEFT JOIN {$_pre}sort D ON A.fid=D.fid WHERE D.fup='$fid' $SQL ORDER BY A.id DESC LIMIT $min,$rows";
}

if(!$fid){
	$SQL="SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON A.id=B.id WHERE A.yz=1 ORDER BY A.id DESC LIMIT $min,$rows";
}

$listdb='';
$query = $db->query($SQL);
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while($rs = $db->fetch_array($query)){
	$rs[content]=preg_replace('/<style([^<]+)<\/style>/is',"",$rs[content]);
	$rs[content]=preg_replace('/<([^<]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
	$rs[content]=preg_replace('/ |　|&nbsp;/is',"",$rs[content]);	//把多余的空格去除掉
	$rs[content]=get_word($rs[content],150);
	@extract($db->get_one("SELECT icon AS head_icon FROM {$pre}memberdata WHERE uid='$rs[uid]'"));
	$rs[head_icon]=$head_icon?tempdir($head_icon):"$webdb[www_url]/images/default/noface.gif";
	$rs[picurl] = tempdir($rs[picurl]);
	$rs[posttime]=List_Post_Time($rs[posttime]);
	$listdb[]=$rs;
}

$showpage=getpage("","","list.php?fid=$fid",$rows,$totalNum);

$rowa=8;
$shows||$shows=1;
$mins=($shows-1)*$rowa;
$maxs=$mins+$rowa;

if($type=="date"){
	$listdbs=List_Dates($listdb,$mins,$maxs);
	foreach($listdbs AS $key=>$rs){
		if(WEB_LANG!='utf-8' && $type=="date"){
			$rs[title]=gbk2utf8($rs[title]);
			$rs[content]=gbk2utf8($rs[content]);
			$rs[username]=gbk2utf8($rs[username]);
			$rs[posttime]=gbk2utf8($rs[posttime]);
		}
		$listdba[]=$rs;
	}
	echo json_encode($listdba);
	exit;
}else{
	$listdbs=List_Dates($listdb,$mins,$maxs);
}

require(ROOT_PATH."inc/head.php");
require($chdb[main_tpl]);
require(ROOT_PATH."inc/foot.php");

?>