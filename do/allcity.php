<?php
define('allcity_page',true);
require_once(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:$webdb[www_url]/do/wapallcity.php");
	exit;
}

@include_once(ROOT_PATH."data/all_area.php");
if($webdb[Info_allcityType]==1){
	$query = $db->query("SELECT * FROM {$pre}city ORDER BY letter ASC,list DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[$rs[letter]][]=$rs;
	}
}

//SEO
$titleDB[title] = $city_DB[metaT][$city_id]?seo_eval($city_DB[metaT][$city_id]):"{$city_DB[name][$city_id]} $titleDB[title]";
$titleDB[keywords]	= $city_DB[metaK][$city_id]?seo_eval($city_DB[metaK][$city_id]):$titleDB[keywords];
$titleDB[description] = $city_DB[metaD][$city_id]?seo_eval($city_DB[metaD][$city_id]):$titleDB[description];

require(ROOT_PATH."inc/head.php");
require(html("allcity"));
require(ROOT_PATH."inc/foot.php");
?>