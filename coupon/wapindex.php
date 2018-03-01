<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['coupon_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//主页个性头部与尾部
get_index_tpl($head_tpl,$foot_tpl);


//获取标签内容
$template_file=getTpl("wapindex");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));

$page||$page=1;
$rows=4;
$min=($page-1)*$rows;
$query = $db->query("SELECT A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON A.id=B.id WHERE A.yz='1' ORDER BY A.hits DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[url] = "wapbencandy.php?fid=$rs[fid]&id=$rs[id]";
	$rs[picurl] && $rs[picurl] = tempdir($rs[picurl]);
	$rs[picurl]=$rs[picurl]?$rs[picurl]:"/images/default/nopic.jpg";
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	$listdb[]=$rs;
}

if($job=="showmore"){
	if(!$listdb){
		echo "nodate";
	}else{
		foreach($listdb AS $key=>$rs){
			echo "<li>
		    <a href='$rs[url]'><div class='List'>
			  <div class='ListPic'><img src='$rs[picurl]'/></div>
			  <h3>$rs[title]</h3>
			  <div class='ListPrice'><span class='L'>￥{$rs[price]} 元</span><strike class='R'>￥{$rs[mart_price]} 元</strike></div>
			  <div class='ListOther'><span class='L'>截止日期：<em>{$rs[end_time]}</em></span><span class='R'>发布日期：{$rs[posttime]}</span></div>
			</div></a>
		  </li>";
		}
	}
	exit;
}

require($template_file);
?>