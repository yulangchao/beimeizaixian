<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['coupon_cache_time']);	//��ȡ���棬ֱ����ʾ������Ĵ��벻��ִ��

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//��ҳ����ͷ����β��
get_index_tpl($head_tpl,$foot_tpl);


//��ȡ��ǩ����
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
			  <div class='ListPrice'><span class='L'>��{$rs[price]} Ԫ</span><strike class='R'>��{$rs[mart_price]} Ԫ</strike></div>
			  <div class='ListOther'><span class='L'>��ֹ���ڣ�<em>{$rs[end_time]}</em></span><span class='R'>�������ڣ�{$rs[posttime]}</span></div>
			</div></a>
		  </li>";
		}
	}
	exit;
}

require($template_file);
?>