<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['house_cache_time']);	//��ȡ���棬ֱ����ʾ������Ĵ��벻��ִ��

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);


//��ҳ����ͷ����β��
get_index_tpl($head_tpl,$foot_tpl);

//��ȡ��ǩ����
$template_file=getTpl("wapindex");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));

//�Ƽ�����Ŀ����ҳ��ʾ
//$listdb_moresort=Info_ListMoreSort($webdb[InfoIndexCSRow],$webdb[InfoIndexCSLeng],$city_id);

//ÿ����Ŀ����Ϣ��
//$InfoNum=get_infonum($city_id);
$page||$page=1;
$rows=3;
$min=($page-1)*$rows;
$query = $db->query("SELECT A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON A.id=B.id WHERE A.yz='1' AND A.fid='1' ORDER BY A.list DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[url] = "wapbencandy.php?fid=$rs[fid]&id=$rs[id]";
	$rs[picurl] && $rs[picurl] = tempdir($rs[picurl]);
	$rs[picurl]=$rs[picurl]?$rs[picurl]:"/$webdb[www_url]/images/default/nopic.jpg";
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	if($rs[sortid] == 1){
		$rs[sortid] ="�н�";
	}else{
		$rs[sortid] ="����";
	}
	$listdb[]=$rs;
}

if($job=="showmore"){
	if(!$listdb){
		echo "nodate";
	}else{
		foreach($listdb AS $key=>$rs){
			echo "<a href='$rs[url]'><dl>
	    <dt class='L'><img src='$rs[picurl]' onerror=\"src='$webdb[www_url]/images/wap/newspic.png'\"/></dt>
		<dd class='R'>
		  <h3>$rs[title]</h3>
		  <p class='ListPrice'><span class='L'>���ͣ�<em>{$rs[room_type]}</em></span><span class='R'>���ʣ�<em>{$rs[sortid]}</em></span></p>
		  <p><span class='L'>$rs[posttime]</span><span class='L'>�����<em>$rs[hits]</em>��</span><span class='R'>�����ߣ�$rs[username]</span></p>
		</dd>
	  </dl></a>";
		}
	}
	exit;
}

require($template_file);
?>