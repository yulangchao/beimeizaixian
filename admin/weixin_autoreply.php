<?php
!function_exists('html') && exit('ERR');

if(!$Apower['weixin_autoreply']||!$Apower['weixin_FirstReply']){
	showerr('你没权限!');
}

/*
if(!table_field("{$pre}weixinword",'type')){
	$db->query("ALTER TABLE  `{$pre}weixinword` ADD  `type` TINYINT( 1 ) NOT NULL");
	$db->query("ALTER TABLE  `{$pre}weixinword` ADD INDEX (  `type` )");
}*/

if($job=="list")
{
	!$page&&$page=1;
	$rows=200;
	$min=($page-1)*$rows;
	
	$showpage=getpage("{$pre}weixinword","","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query(" SELECT * FROM {$pre}weixinword ORDER BY list DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[types]=$rs[type]?"图文":"纯文本";
		if($rs[type]){
			$answers= unserialize($rs[answer]);
			$lists="";
			foreach($answers AS $key=>$rss){
				$rss[title]=$rss[title]?$rss[title]:($rss[desc]?$rss[desc]:$rss[link]);
				$lists.='<div>'.get_word($rss[title],80).'</div>';
			}
			$rs[answer]=$lists;
		}else{		
			$rs[answer]=get_word($rs[answer],80);
		}
		$listdb[]=$rs;
	}
	$kill_badword[intval($webdb[kill_badword])]=' checked ';
	
	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/weixin_autoreply/list.htm");
	require(dirname(__FILE__).'/foot.php');
	
}
elseif($action=="set")
{
	if($webdbs[weixin_welcome_pic] && $webdbs[weixin_welcome_pic]!=$webdb[weixin_welcome_pic]){
		delete_attachment($lfjuid,tempdir($webdb[weixin_welcome_pic]));
	}
	write_config_cache($webdbs);
	jump("设置成功","index.php?lfj=$lfj&job=FirstReply",1);
}
elseif($action=="delete")
{
	if(!$iddb){
		showmsg("请选择一个");
	}
	foreach($iddb AS $key=>$rs){
		$db->query("DELETE FROM {$pre}weixinword WHERE id='$key'");
	}
	jump("删除成功","index.php?lfj=$lfj&job=list&",1);
}
elseif($action=="addword")
{
	$answer=format_answer();
	if(!$ask){
		showmsg("关键词不能为空");
	}elseif(!$answer){
		showmsg("回复内容不能为空");
	}	
	$db->query("INSERT INTO {$pre}weixinword SET ask='$ask',answer='$answer',type='$type'");
	write_weixinword_cache();
	jump("添加成功","index.php?lfj=$lfj&job=list&",1);
}
elseif($action=="edit")
{
	$answer=format_answer();
	if(!$ask){
		showmsg("关键词不能为空");
	}elseif(!$answer){
		showmsg("回复内容不能为空");
	}
	$db->query("UPDATE {$pre}weixinword SET ask='$ask',answer='$answer',list='$list',type='$type' WHERE id='$id' ");
	write_weixinword_cache();
	jump("修改成功","index.php?lfj=$lfj&job=list&",1);
}
elseif($job=="edit")
{
	$rsdb=$db->get_one(" SELECT * FROM {$pre}weixinword WHERE id='$id' ");
	
	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/weixin_autoreply/edit.htm");
	require(dirname(__FILE__).'/foot.php');

}
elseif($job=="FirstReply")
{
	//$rsdb=$db->get_one(" SELECT * FROM {$pre}weixinword WHERE id='$id' ");
	
	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/weixin_autoreply/firstreply.htm");
	require(dirname(__FILE__).'/foot.php');

}

function write_weixinword_cache(){
	global $db,$pre,$webdb;
	$query=$db->query(" SELECT * FROM {$pre}weixinword ORDER BY list DESC ");
	while($rs=$db->fetch_array($query)){
		$rs[ask] = str_replace('　',' ',$rs[ask]);
		$detail = explode(' ',$rs[ask]);
		foreach($detail AS $key=>$value){
			if($value){
				$array[$value]=$rs[id];
			}
		}
	}
	write_file(ROOT_PATH."data{$webdb[web_dir]}/weixin.php","<?php\r\n return ".var_export($array,true).';?>');
}

function format_answer(){
	global $type,$answers,$answer;
	if($type==1){
		$i=0;
		foreach($answers AS $key=>$rs){
			if($rs[link]){
				$listdb[$i][title]=filtrate($rs[title]);
				$listdb[$i][desc]=filtrate($rs[desc]);
				$listdb[$i][pic]=filtrate($rs[pic]);
				$listdb[$i][link]=filtrate($rs[link]);
				$i++;
			}
		}
		if($i<1){
			showmsg("请规范填写图文内容！");
		}
		$shoptypes1 = serialize($listdb);
	}else{
		$shoptypes1 = $answer;
	}	
	return $shoptypes1;
}
?>