<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");


//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);


//主页个性头部与尾部
get_index_tpl($head_tpl,$foot_tpl);

//获取标签内容
$template_file=getTpl('wapindex');
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));

$page||$page=1;
$rows=4;
$min=($page-1)*$rows;
$query = $db->query("SELECT * FROM {$_pre}content WHERE yz='1' ORDER BY list DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[url] = "wapbencandy.php?fid=$rs[fid]&id=$rs[id]";
	$rs[picurl] && $rs[picurl] = tempdir($rs[picurl]);
	$rs[picurl]=$rs[picurl]?$rs[picurl]:"/images/default/nopic.jpg";
	$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
	$listdb[]=$rs;
}

if($job=="showmore"){
	if(!$listdb){
		echo "nodate";
	}else{
		foreach($listdb AS $key=>$rs){
			echo "<li>
		    <div class='List'>
			  <a href='$rs[url]'><div class='ListPic'><img src='$rs[picurl]'/></div>
			  <h3>$rs[title]</h3>
			  <p><span class='L'>{$webdb[MoneyName]}:<em>{$rs[money]}</em>{$webdb[MoneyDW]}</span><span class='R'>浏览:$rs[hits]人</span></p></a>
			</div>
		  </li>";
		}
	}
	exit;
}
require($template_file);
?>