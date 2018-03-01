<?php
if(!function_exists('html')){
	die('F');
}
if( !is_table("{$_pre}collectionhy") ){
	$db->query("CREATE TABLE `{$_pre}collectionhy` (
  `cid` mediumint(7) NOT NULL AUTO_INCREMENT,
  `id` mediumint(7) NOT NULL DEFAULT '0',
  `uid` mediumint(7) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=1;");
}
if(!$lfjid){
	die("请先登录");
}elseif(!$id){
	die("ID不存在");
}
if($db->get_one("SELECT * FROM `{$_pre}collectionhy` WHERE `id`='$id' AND uid='$lfjuid'")){
	die("请不要重复收藏本条店铺"); 
}
$db->query("INSERT INTO `{$_pre}collectionhy` (  `id` , `uid` , `posttime`) VALUES ('$id','$lfjuid','$timestamp')");

die("收藏成功!");
?>