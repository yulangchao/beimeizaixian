<?php
require(dirname(__FILE__)."/global.php");
@include(dirname(__FILE__)."/data{$webdb[web_dir]}/guide_fid.php");
require(ROOT_PATH."inc/class.json.php");


//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);



$template_file=getTpl("index");

fetch_label_value(array('pagetype'=>'1','file'=>$template_file,'module'=>$webdb['module_id']));





$Lrows=15;

unset($SQL);
$SQL = " WHERE A.yz=1 ";
$SQL .= build_module_sql();

/*Ð¡·ÖÀà*/
//if($fidDB[type]==0)
//{	
	$listdb=ListThisSort($Lrows,$webdb[InfoListLeng]>0?$webdb[InfoListLeng]:70);
	$showpage=getpage("{$_pre}content A",$SQL,"index.php?",$Lrows);	

	if($types=="date"){
		foreach($listdb AS $key=>$rs){
			if(WEB_LANG!='utf-8'){
				$rs[icon]=gbk2utf8(get_member_icon($rs[uid]));
				$rs[title]=gbk2utf8($rs[title]);
				$rs[username]=gbk2utf8($rs[username]);
				$rs[posttime]=gbk2utf8($rs[posttime]);
			}
			$listdba[]=$rs;
		}
		echo json_encode($listdba);
		exit;
	}
//}

$year = date('Y');
$today = date('d');
$month = date('m');
$rs = $db->get_one("SELECT COUNT(*) AS todayTopic FROM {$_pre}content WHERE FROM_UNIXTIME( posttime, '%d%m%Y' ) = {$today}{$month}{$year}");
$todayTopic = intval($rs[todayTopic]);

$rs = $db->get_one("SELECT COUNT(*) AS todayComment FROM {$_pre}comments WHERE FROM_UNIXTIME( posttime, '%d%m%Y' ) = {$today}{$month}{$year}");
$todayComment = intval($rs[todayComment]);

require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");

$content=ob_get_contents();
ob_end_clean();
echo str_replace("document.domain","//document.domain",$content);

function ListThisSort($rows,$leng){
	global $page,$fid,$fidDB,$SQL,$city_id,$listtype;
	if($page<1){
		$page=1;
	}
	if($fidDB[listorder]==1||$listtype==1){
		$sql_list="A.posttime";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==2){
		$sql_list="A.posttime";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==3||$listtype==3){
		$sql_list="A.hits";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==4){
		$sql_list="A.hits";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==5||$listtype==2){
		$sql_list="A.lastview";
		$sql_order="DESC";
	}else{
		$sql_list="A.list";
		$sql_order="DESC";
	}
	$min=($page-1)*$rows;
	$_SQL="$SQL ORDER BY $sql_list $sql_order LIMIT $min,$rows";
	$listdb=list_content($_SQL,$leng);
	return $listdb;
}

 

?>