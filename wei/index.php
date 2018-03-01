<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapindex.php");
	exit;
}

$type=='date' || web_cache($webdb['wei_cache_time']);	//调取缓存，直接显示，下面的代码不再执行


require(ROOT_PATH."data{$GLOBALS[webdb][web_dir]}/friendlink.php");
require(Mpath."inc/class.json.php");

//SEO
$titleDB['title'] = $webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname'];
$titleDB['keywords']	= $webdb['SEO_keywords']?$webdb['SEO_keywords']:$titleDB['keywords'];
$titleDB['description']	= $webdb['SEO_description']?$webdb['SEO_description']:$titleDB['description'];

$SQL='';
if( count($city_DB['name']) > 1 ){
	$SQL=" AND city_id='$city_id' ";
}

/**
*标签使用
**/
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id];
require(ROOT_PATH."inc/label_module.php");

unset($listdbs,$listdba);
$page||$page=1;
$rowss=24;
$mins=($page-1)*$rowss;
$query = $db->query("SELECT * FROM `{$_pre}content` WHERE yz=1 $SQL ORDER BY id DESC LIMIT $mins,$rowss");
while($rs = $db->fetch_array($query)){
	@extract($db->get_one("SELECT icon AS head_icon FROM {$pre}memberdata WHERE uid=$rs[uid]"));
	@extract($db->get_one("SELECT content FROM {$_pre}content_1 WHERE id=$rs[id]"));
	$rn[head_icon]=$head_icon?tempdir($head_icon):"$webdb[www_url]/images/default/noface.gif";
	$rn[picurl]=$rs[picurl]?tempdir($rs[picurl]):"$webdb[_www_url]/images/default/nopic.jpg";
	$content=preg_replace('/<style([^<]+)<\/style>/is',"",$content);
	$content=preg_replace('/<([^<]*)>/is',"",$content);	//把HTML代码过滤掉
	$content=preg_replace('/ |　|&nbsp;/is',"",$content);	//把多余的空格去除掉
	$rn[content]=get_word($content,150);
	$rn[title]=$rs[title];
	$rn[username]=$rs[username];
	$rn[posttime]=List_Post_Time($rs[posttime]);
	$rn[fid]=$rs[fid];
	$rn[uid]=$rs[uid];
	$rn[id]=$rs[id];
	$rn[comments]=$rs[comments];
	$rn[hits]=$rs[hits];
	$listdbs[]=$rn;
}
$showpage=getpage("{$_pre}content","WHERE yz=1 $SQL","?",$rowss);

$rows=8;
$shows||$shows=1;
$min=intval( ($shows-1)*$rows );
$max=$min+$rows;

if($type=="date"){
	$listdb=List_Dates($listdbs,$min,$max);
	foreach($listdb AS $key=>$rs){
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
	$listdb=List_Dates($listdbs,$min,$max);
}

require(ROOT_PATH."inc/head.php");
require(getTpl("index"));
require(ROOT_PATH."inc/foot.php");
?>