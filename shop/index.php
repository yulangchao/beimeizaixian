<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapindex.php");
	exit;
}

web_cache($webdb['shop_cache_time']);	//��ȡ���棬ֱ����ʾ������Ĵ��벻��ִ��

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);

//��ҳ����ͷ����β��
get_index_tpl($head_tpl,$foot_tpl);


//��ȡ��ǩ����
$template_file=getTpl("index");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));



//�Ƽ�����Ŀ����ҳ��ʾ
//$listdb_moresort=Info_ListMoreSort($webdb[InfoIndexCSRow],$webdb[InfoIndexCSLeng],$city_id);

//ÿ����Ŀ����Ϣ��
//$InfoNum=get_infonum($city_id);

require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");


?>