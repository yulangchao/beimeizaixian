<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapindex.php");
	exit;
}

web_cache($webdb['shop_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);


	$rows=12;
	$SQL = " WHERE 1 ";
	$sql_list="A.list";
	$sql_order="DESC";

if($city_id){
	$SQL .= " AND A.city_id='$city_id' ";
}
if($fid){
	$SQL .= " AND A.fid='$fid' ";
}
if($zone_id){
	$SQL .= " AND A.zone_id='$zone_id' ";
}
if($street_id){
	$SQL .= " AND A.street_id='$street_id' ";
}
if($orders){
	$orders=filtrate($orders);
	$sql_list="A.$orders";
}
$listdb=array();
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,C.title AS companyname,C.renzheng  FROM {$_pre}company A LEFT JOIN {$pre}hy_company C ON A.uid=C.uid $SQL ORDER BY $sql_list $sql_order LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?fid=$fid&city_id=$city_id&zone_id=$zone_id&street_id=$street_id&orders=$orders",$rows,$totalNum);

while( $rs=$db->fetch_array($query) ){

	$rs[content]=@preg_replace('/<([^<]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
	$rs[content]=get_word($rs[full_content]=$rs[content],100);
	$rs[title]=get_word($rs[full_title]=$rs[title],50);
	if($rs['list']>$timestamp){
		$rs[title]=" <font color='$webdb[Info_TopColor]'>$rs[title]</font> <img src='$webdb[www_url]/images/default/icotop.gif' border=0>";
	}elseif($rs['list']>$rs[posttime]){
		//置顶过期的信息,需要恢复原来发布日期以方便排序,放在后面
		$db->query("UPDATE {$_pre}company SET list='$rs[posttime]' WHERE id='$rs[id]'");
	}

	$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
	if($rs[picurl]){
		$rs[picurl]=tempdir($rs[picurl]);
	}

	$Module_db->showfield($field_db,$rs,'list');

	$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);

	$listdb[]=$rs;
}



//主页个性头部与尾部
get_index_tpl($head_tpl,$foot_tpl);


//获取标签内容
$template_file=getTpl("index");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));



//推荐的栏目在首页显示
//$listdb_moresort=Info_ListMoreSort($webdb[InfoIndexCSRow],$webdb[InfoIndexCSLeng],$city_id);

//每个栏目的信息数
//$InfoNum=get_infonum($city_id);

require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");


?>