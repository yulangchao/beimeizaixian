<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['ershou_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//主页个性头部与尾部
get_index_tpl($head_tpl,$foot_tpl);



//获取标签内容
$template_file=getTpl("wapindex");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));


/**
*推荐的栏目在首页显示
**/
$listdb_moresort=Info_ListMoreSort($webdb[InfoIndexCSRow],$webdb[InfoIndexCSLeng],$city_id);

//每个栏目的信息数
$InfoNum=get_infonum($city_id);

$page||$page=1;
$rows=4;
$min=($page-1)*$rows;
$query = $db->query("SELECT * FROM {$_pre}content WHERE yz='1' ORDER BY list DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[url] = "wapbencandy.php?fid=$rs[fid]&id=$rs[id]";
	$rs[picnum]=$rs[picnum]?"【{$rs[picnum]}图】":"";
	$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
	$listdb[]=$rs;
}

if($job=="showmore"){
	if(!$listdb){
		echo "nodate";
	}else{
		foreach($listdb AS $key=>$rs){
			echo "<dl>
	    <dt class='R'><a href='$rs[url]'>浏览</a></dt>
		<dd class='L'>
		  <h3><a href='$rs[url]'>$rs[title]</a><span><em>{$rs[picnum]}</em></span></h3>
		  <p><span class='L'>发布者：{$rs[username]}</span><span class='L'>时间：{$rs[posttime]}</span><span class='R'>浏览：<em>{$rs[hits]}</em>人</span></p>
		</dd>
	  </dl>";
		}
	}
	exit;
}

require($template_file);

?>