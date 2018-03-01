<?php
require(dirname(__FILE__)."/"."global.php");

if($action=='add'){
	$ts = $db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}mysort WHERE uid='$lfjuid' ");
	if($ts['NUM']>50){
		showerr("你创建的分类不能超过50个！");
	}
	
	$fup = intval($fup);
	$name = filtrate($name);
	$type=0;
	if($fup){	
		$rs = $db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}mysort WHERE uid='$lfjuid' AND fid='$fup' ");
		if(!$rs){
			showerr("父栏目不存在！");
		}
		$type=1;
	}
	
	
	$db->query("INSERT INTO  `{$_pre}mysort` ( `fup` ,  `type` ,  `name` ,  `uid` ,  `list` ) VALUES ( '$fup',  '$type',  '$name',  '$lfjuid',  '0')");
	
	refreshto("?job=list",'创建成功！',1);

}elseif($action=='del'){

	$db->query("DELETE FROM {$_pre}mysort WHERE uid='$lfjuid' AND fid='$fid'");
	refreshto("?job=list",'删除成功！',1);
	
}elseif($action=='order'){
	
	foreach($list_db AS $key=>$value){
		$db->query("UPDATE {$_pre}mysort SET list='$value' WHERE uid='$lfjuid' AND fid='$key'");
	}	
	refreshto("?job=list",'设置成功！',1);
	
}elseif($job=='edit'){
	
	$rsdb = $db->get_one("SELECT * FROM {$_pre}mysort WHERE uid='$lfjuid' AND fid='$fid' ");
	$select_fup=select_mysort($lfjuid,'fup',$rsdb[fup],true);

}elseif($action=='edit'){

	$fup = intval($fup);
	$name = filtrate($name);
	$type=0;
	if($fup){	
		$rs = $db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}mysort WHERE uid='$lfjuid' AND fid='$fup' ");
		if(!$rs){
			showerr("父栏目不存在！");
		}
		$type=1;
	}
	$db->query("UPDATE `{$_pre}mysort` SET name='$name',fup='$fup' WHERE uid='$lfjuid' AND fid='$fid'");
	refreshto("?job=list",'修改成功！',1);

}else{

	$select_fup=select_mysort($lfjuid,'fup',0,true);

	$listdb=array();
	$query = $db->query("SELECT * FROM {$_pre}mysort WHERE uid='$lfjuid' AND fup=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ps = $db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}content WHERE myfid='$rs[fid]' ");
		$rs[NUM]=$ps[NUM];
		$listdb[]=$rs;
		$query2 = $db->query("SELECT * FROM {$_pre}mysort WHERE fup='$rs[fid]' ORDER BY list DESC");
		while($ts = $db->fetch_array($query2)){
			$ps = $db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}content WHERE myfid='$ts[fid]' ");
			$ts[NUM]=$ps[NUM];
			$ts[icon]='&nbsp;&nbsp;|----';
			$listdb[]=$ts;
		}
	}
}





require(ROOT_PATH."member/head.php");
require(dirname(__FILE__)."/"."template/mysort.htm");
require(ROOT_PATH."member/foot.php");
?>