<?php
define('MOB_PAGE',true);
require(dirname(__FILE__)."/global.php");

web_cache($webdb['shoptg_cache_time']);	//��ȡ���棬ֱ����ʾ������Ĵ��벻��ִ��

//SEO
$titleDB['title'] = "{$city_DB[name][$city_id]} ".($webdb['SEO_title']?$webdb['SEO_title']:$webdb['Info_webname']);
$titleDB['keywords']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_keywords']?$webdb['SEO_keywords']:$webdb['metakeywords']);
$titleDB['description']	= "{$city_DB[name][$city_id]} ".($webdb['SEO_description']?$webdb['SEO_description']:$webdb['description']);


//��ҳ����ͷ����β��
get_index_tpl($head_tpl,$foot_tpl);

//��ȡ��ǩ����
$template_file=getTpl("wapindex");
fetch_label_value(array('pagetype'=>'0','file'=>$template_file,'module'=>$webdb['module_id']));


require(ROOT_PATH."inc/waphead.php");
require($template_file);
require(ROOT_PATH."inc/wapfoot.php");


?>