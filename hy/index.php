<?php
require(dirname(__FILE__)."/"."global.php");

if($IsMob){
	header("location:wapindex.php");
	exit;
}

web_cache($webdb['hy_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//主页个性头部与尾部
get_index_tpl($head_tpl,$foot_tpl);


//获取标签内容
$template_file=getTpl("index");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));


if($page<1){
	$page=1;
}
$rows=10;
$min=($page-1)*$rows;
$renzheng  = filtrate($renzheng);
$SQL = ' AND A.yz=1 ';
if($street_id){
	$SQL .= " AND A.street_id='$street_id' ";
}elseif($zone_id){
	$SQL .= " AND A.zone_id='$zone_id' ";
}else{
	$SQL .= build_module_sql();
}
if($renzheng){
	$SQL .= " AND A.renzheng='$renzheng' ";
}

$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}company A WHERE 1 $SQL ORDER BY A.posttime DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while($rs = $db->fetch_array($query)){
	$userdb=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$rs[uid]'");
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$rs[picurl]=tempdir($rs[picurl]);
	$rs[grouptype]=$userdb[grouptype];
	$rs[idcard_yz]=$userdb[idcard_yz];
	$rs[email_yz]=$userdb[email_yz];
	$rs[mob_yz]=$userdb[mob_yz];
	$rs[picurl] && $rs[picurl]=tempdir($rs[picurl]);
	$listdb[]=$rs;
}
$showpage=getpage("","","index.php?zone_id=$zone_id&street_id=$street_id",$rows,$totalNum);



require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");

?>