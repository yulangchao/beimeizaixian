<?php
require_once("global.php");

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID有误!");
}

$infodb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$cid'");

if(!$infodb){
	showerr("内容不存在");
}elseif($infodb[fid]!=$fid){
	showerr("FID有误!!!");
}
if(intval( strtotime($infodb[end_time].' 23:59:59'))<$timestamp){
	showerr("活动已经结束，不能再报名！");
}
if(!$job&&!$action&&$rs=$db->get_one("SELECT * FROM {$_pre}join WHERE cid='$cid' AND uid='$lfjuid'") ){
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?cid=$cid&fid=$fid&id=$rs[id]&job=edit'>";
	exit;
}


$mid=2;

/**
*模块参数配置文件
**/
$field_db = $module_DB[$mid][field];


/**处理提交的新内容**/
if($action=="postnew")
{

	$postdb[yz]=0;

	//自定义字段的合法检查与数据处理
	$Module_db->checkpost($field_db,$postdb,'');


	/*往主信息表插入内容*/
	$db->query("INSERT INTO `{$_pre}join` ( `mid` , `cid` , `cuid`, `fid` ,  `posttime` ,  `uid` , `username` , `yz` , `ip` ) 
	VALUES (
	'$mid','$cid','$infodb[uid]', '$fid','$timestamp','$lfjdb[uid]','$lfjdb[username]','$postdb[yz]','$onlineip')");

	$id = $db->insert_id();

	unset($sqldb);
	$sqldb[]="id='$cid'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	/*检查判断辅信息表要插入哪些字段的内容*/
	/*foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}*/

	$sql=implode(",",$sqldb);
	
	$db->query("INSERT INTO `{$_pre}content_$mid` SET $sql,content='$postdb[content]',realname='$postdb[realname]',telphone='$postdb[telphone]',gototime='$postdb[gototime]',address='$postdb[address]'");
	
	$db->query("UPDATE {$_pre}content SET totaluser=totaluser+1 WHERE id='$cid'");

	refreshto("joinshow.php?fid=$fid&id=$id&cid=$cid","预约成功,请等待审核!",8);
}

/*删除内容,直接删除,不保留*/
elseif($action=="del")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.rid WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权操作");
	}
	$db->query("DELETE FROM `{$_pre}content_$mid` WHERE rid='$id' ");
	$db->query("DELETE FROM `{$_pre}join` WHERE id='$id' ");
	$db->query("UPDATE {$_pre}content SET totaluser=totaluser-1 WHERE id='$cid'");
	refreshto($FROMURL,"删除成功");
}

/*编辑内容*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.rid WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	/*表单默认变量作处理*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";	
	
	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$mid",$FidTpl['post']));
	require(ROOT_PATH."member/foot.php");
}

/*处理提交的内容做修改*/
elseif($action=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.rid WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	//自定义字段的合法检查与数据处理
	$Module_db->checkpost($field_db,$postdb,$rsdb);


	/*检查判断辅信息表要插入哪些字段的内容*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*更新辅信息表*/
	$db->query("UPDATE `{$_pre}content_$mid` SET $sql WHERE rid='$id'");
	
	refreshto("joinshow.php?fid=$fid&id=$id&cid=$cid","修改成功");
}
else
{
	/*模块设置时,有些字段有默认值*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*表单默认变量作处理*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="postnew";

	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$mid"));
	require(ROOT_PATH."member/foot.php");
}

?>