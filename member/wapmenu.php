<?php
!function_exists('html') && exit('ERR');

unset($menudb,$base_menudb);
//$base_menudb['�޸ĸ�������']['link']='wapuserinfo.php?job=edit';

//$base_menudb['<font color=red>��������.���</font>']['link']='waplist.php';
//$base_menudb['<font color=red>��������.���</font>']['power']='2';

//$base_menudb['<font color=red>��������.����</font>']['link']='wapcomment.php?job=work';
//$base_menudb['<font color=red>��������.����</font>']['power']='2';

$menudb['��������']['�޸���Ϣ']['link']='wapuserinfo.php?job=edit';
$menudb['��������']['վ�ڶ���Ϣ']['link']='wappm.php?job=list';
$menudb['��������']['���ֳ�ֵ']['link']='wapmoney.php?job=list';
$menudb['��������']['����ȼ�']['link']='wapbuygroup.php?job=list';
//$menudb['��������']['��ҵ��Ϣ']['link']='wapcompany.php?job=edit';
$menudb['��������']['�����֤']['link']='wapyz.php?job=email';
$menudb['��������']['��������']['link']='wapmoneylog.php?job=list';
$menudb['��������']['����ռ�']['link']='wapbuyspace.php';
$menudb['��������']['�տ��ʺ�']['link']='wapbank.php?job=set';
$menudb['��������']['������Ϣ']['link']='waprmb.php?job=list';
$menudb['��������']['��������']['link']='wapupload_file.php?job=show';


//����˵�
$query = $db->query("SELECT * FROM {$pre}hack ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	if(is_file(ROOT_PATH."hack/$rs[keywords]/member_menu.php")){
		$array = include(ROOT_PATH."hack/$rs[keywords]/member_menu.php");
		$menudb['�������']["$array[name]"]['link']=$array['url'];
	}
}

//@include(dirname(__FILE__)."/cms_menu.php");

?>