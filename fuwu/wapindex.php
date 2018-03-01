<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['tg_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

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
$query = $db->query("SELECT * FROM {$_pre}content WHERE yz='1' ORDER BY hits DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[url] = "wapbencandy.php?fid=$rs[fid]&id=$rs[id]";
	$rs[url1] =  "$Murl/member/wapjoin.php?fid=$rs[fid]&cid=$rs[id]";
	$rs[totaluser] = $rs[totaluser]?"已有$rs[totaluser]人预约":"欢迎预约";
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
			echo "<dl>
	    <dt class='L'> <a href='$rs[url]'><img src='$rs[picurl]'/></a></dt>
		<dd class='R'>
		  <h3><a href='$rs[url]'>$rs[title]</a></h3>
		  <p class='ListPrice'><span class='L'>$rs[totaluser]</span><span class='R'><a href='$rs[url1]'>立即预约</a></span></p>
		  <p class='ListOther'><span class='L'>发布人：$rs[username]</span><span class='R'>发布时间：$rs[posttime]</span></p>
		</dd>
	  </dl>";
		}
	}
	exit;
}

require($template_file);
?>