<?php
define('MOB_PAGE',true);
require("global.php");

//导航条
@include(Mpath."data{$GLOBALS[webdb][web_dir]}/guide_fid.php");

$mid=2;

/**
*获取信息正文的内容
**/
if(!$id&&!$uid){
	$uid=$lfjuid;
}
$SQL = $id?"A.id='$id'":"A.uid='$uid'";
$rsdb=$db->get_one("SELECT A.*,B.*,M.icon FROM `{$_pre}person` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id LEFT JOIN {$pre}memberdata M ON A.uid=M.uid WHERE $SQL");
$id = $rsdb[id];

if(!$rsdb){
	showerr("简历不存在");
}elseif(!$web_admin&&$rsdb[uid]!=$lfjuid&&$rsdb[cuid]!=$lfjuid){
	//showerr("你无权查看");
}

$rsdb[picurl] = tempdir($rsdb[icon]);


$rsdb[C]=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$rsdb[cid]'");


$field_db = $module_DB[$mid]['field'];

/**
*对信息内容字段的处理
**/
$Module_db->hidefield=true;
$Module_db->classidShowAll=true;
$Module_db->showfield($field_db,$rsdb,'show');


$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$fav_num = 0;
extract($db->get_one("SELECT COUNT(*) AS fav_num FROM {$_pre}collection WHERE memberuid='$rsdb[uid]'"));

//内容页个性头部与尾部
$head_tpl=html('waphead');
$foot_tpl=html('wapfoot');
if($webdb[IF_Private_tpl]==3){
	if(is_file(Mpath.$webdb[Private_tpl_head])){
		$head_tpl=Mpath.$webdb[Private_tpl_head];
	}
	if(is_file(Mpath.$webdb[Private_tpl_foot])){
		$foot_tpl=Mpath.$webdb[Private_tpl_foot];
	}
}

$db->query("UPDATE {$_pre}person SET hits=hits+1 WHERE id=$id");
require(ROOT_PATH."inc/waphead.php");
require(getTpl("wapbencandy_$mid"));
require(ROOT_PATH."inc/wapfoot.php");
?>