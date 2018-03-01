<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

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
$rowss=$webdb[Info_ShowIndexRows]?$webdb[Info_ShowIndexRows]:30;
$rowss=12;
$mins=($page-1)*$rowss;
$query = $db->query("SELECT * FROM `{$_pre}content` WHERE yz=1 $SQL ORDER BY id DESC LIMIT $mins,$rowss");
while($rs = $db->fetch_array($query)){
	@extract($db->get_one("SELECT icon AS head_icon FROM {$pre}memberdata WHERE uid=$rs[uid]"));
	@extract($db->get_one("SELECT content FROM {$_pre}content_1 WHERE id=$rs[id]"));
	$rn[head_icon]=$head_icon?tempdir($head_icon):"$webdb[www_url]/images/default/noface.gif";
	$rn[picurl]=$rs[picurl]?tempdir($rs[picurl]):"";
	$content=preg_replace('/<style([^<]+)<\/style>/is',"",$content);
	$content=preg_replace('/<([^<]*)>/is',"",$content);	//把HTML代码过滤掉
	$content=preg_replace('/ |　|&nbsp;/is',"",$content);	//把多余的空格去除掉
	$rn[content]=get_word($content,150);
	$rs[title] || $rs[title] = get_txt_word($content,40);
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

$rows=4;
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
	unset($i,$list_html1,$list_html2,$list_html3,$list_html4);
	foreach($listdb AS $key=>$rs){
		$i++;
		$pic=$rs[picurl]?"<div class='img'><a href='$webdb[www_url]/wei/wapbencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><img src='$rs[picurl]' width='210' onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"></a></div>\n":"";
		$show="<div class='side'>\n
			$pic
			<h3><a href='$webdb[www_url]/wei/wapbencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></h3>\n			
			<p>$rs[content]</p>\n
			<dl>\n
			<dt>评论($rs[comments]) 点击($rs[hits])</dt>\n
			<dd>\n
			<span><a href='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]' target='_blank'><img src='$rs[head_icon]' width=30></a></span>
			<a href='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]' target='_blank'>$rs[username]</a><br/>
			$rs[posttime]
			</dd>\n
			</dl>\n
		</div>\n";
		if($i<4) $list_html1.=$show;
		elseif($i<7) $list_html2.=$show;
		elseif($i<10) $list_html3.=$show;
		else $list_html4.=$show;
	}
}

require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapindex"));
require(ROOT_PATH."inc/wapfoot.php");
?>