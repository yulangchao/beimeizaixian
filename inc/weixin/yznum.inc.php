<?php
!function_exists('html') && exit('ERR');

//��ǰ�ļ���ע��ʱͨ��΢�Ż�ȡע����Ĺ���
if(!is_table("{$pre}weixinyznum")){
	$db->query("CREATE TABLE `{$pre}weixinyznum` (
  `sid` varchar(16) NOT NULL default '',
  `username` varchar(50) NOT NULL default '',
  `num` varchar(6) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `wx_id` varchar(50) NOT NULL default '',
  UNIQUE KEY `sid` (`sid`),
  KEY `username` (`username`,`num`)
	) ENGINE = HEAP");
	$db->query("ALTER TABLE  `{$pre}memberdata` ADD  `weixin_api` VARCHAR( 32 ) NOT NULL AFTER  `qq_api` ");
}
$username = filtrate($username);
$touser = filtrate($touser);
$rand_num = rand(1000,9999);
$sid = substr(md5($username),0,16);
$db->query("REPLACE INTO `{$pre}weixinyznum` ( `sid` , `num` , `posttime` ,`username` ,`wx_id`) VALUES ('$sid', '$rand_num', '$timestamp', '$username', '$touser')");

?>