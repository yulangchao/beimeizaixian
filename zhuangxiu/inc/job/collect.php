<?php
if(!function_exists('html')){
die('F');
}
if(!$lfjid){
	showerr("���ȵ�¼");
}elseif(!$id){
	showerr("ID������");
}
if($db->get_one("SELECT * FROM `{$_pre}collection` WHERE `id`='$id' AND uid='$lfjuid'")){
	showerr("�벻Ҫ�ظ��ղر�����Ϣ",1); 
}
if(!$web_admin){
	$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}collection` WHERE uid='$lfjuid'");
	if($rs[NUM]>=$groupdb[post_info_collection_num]){
		showerr("�����ֻ���ղ�{$groupdb[post_info_collection_num]}����Ϣ",1);
	}
}
$db->query("INSERT INTO `{$_pre}collection` (  `id` , `uid` , `posttime`) VALUES ('$id','$lfjuid','$timestamp')");

refreshto("$webdb[_www_url]/member/?main=$Murl/member/collection.php","�ղسɹ�!",1);
?>