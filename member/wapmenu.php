<?php
!function_exists('html') && exit('ERR');

unset($menudb,$base_menudb);
//$base_menudb['修改个人资料']['link']='wapuserinfo.php?job=edit';

//$base_menudb['<font color=red>版主管理.稿件</font>']['link']='waplist.php';
//$base_menudb['<font color=red>版主管理.稿件</font>']['power']='2';

//$base_menudb['<font color=red>版主管理.评论</font>']['link']='wapcomment.php?job=work';
//$base_menudb['<font color=red>版主管理.评论</font>']['power']='2';

$menudb['基本功能']['修改信息']['link']='wapuserinfo.php?job=edit';
$menudb['基本功能']['站内短消息']['link']='wappm.php?job=list';
$menudb['基本功能']['积分充值']['link']='wapmoney.php?job=list';
$menudb['基本功能']['购买等级']['link']='wapbuygroup.php?job=list';
//$menudb['基本功能']['企业信息']['link']='wapcompany.php?job=edit';
$menudb['基本功能']['身份验证']['link']='wapyz.php?job=email';
$menudb['基本功能']['积分消费']['link']='wapmoneylog.php?job=list';
$menudb['基本功能']['购买空间']['link']='wapbuyspace.php';
$menudb['基本功能']['收款帐号']['link']='wapbank.php?job=set';
$menudb['基本功能']['财务信息']['link']='waprmb.php?job=list';
$menudb['基本功能']['附件管理']['link']='wapupload_file.php?job=show';


//插件菜单
$query = $db->query("SELECT * FROM {$pre}hack ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	if(is_file(ROOT_PATH."hack/$rs[keywords]/member_menu.php")){
		$array = include(ROOT_PATH."hack/$rs[keywords]/member_menu.php");
		$menudb['插件功能']["$array[name]"]['link']=$array['url'];
	}
}

//@include(dirname(__FILE__)."/cms_menu.php");

?>