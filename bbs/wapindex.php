<?php
require(dirname(__FILE__)."/global.php");
@include(dirname(__FILE__)."/data{$webdb[web_dir]}/guide_fid.php");
require(ROOT_PATH."inc/class.json.php");

 

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//获取标签内容
$template_file=getTpl("wapindex");

fetch_label_value(array('pagetype'=>'1','file'=>$template_file,'module'=>$webdb['module_id']));



	$SQL = " WHERE A.yz=1 ";
	$SQL .= build_module_sql();


$Lrows=$fidDB[maxperpage]>0?$fidDB[maxperpage]:($webdb[Infolist_row]>0?$webdb[Infolist_row]:15);
$Lrows=8;

@extract($db->get_one("SELECT COUNT(A.id) AS NUM FROM {$_pre}content A $SQL"));
$totalcontent=intval($NUM);

@extract($db->get_one("SELECT SUM(A.hits) AS NUM FROM {$_pre}content A $SQL"));
$totalhits=intval($NUM)>1000?intval($NUM):round($NUM,2);

/*小分类*/
//if($fidDB[type]==0)
//{	
	$listdb=ListThisSort($Lrows,$webdb[InfoListLeng]>0?$webdb[InfoListLeng]:70);
	//$showpage=getpage("{$_pre}content A",$SQL,"list.php?fid=$fid",$Lrows);	

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


require(ROOT_PATH."inc/waphead.php");
require($template_file);
//require(ROOT_PATH."inc/wapfoot.php");



function ListThisSort($rows,$leng){
	global $page,$fid,$fidDB,$SQL,$city_id;
	if($page<1){
		$page=1;
	}
	if($fidDB[listorder]==1){
		$sql_list="A.posttime";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==2){
		$sql_list="A.posttime";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==3){
		$sql_list="A.hits";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==4){
		$sql_list="A.hits";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==5){
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