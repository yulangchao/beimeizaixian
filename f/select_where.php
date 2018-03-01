<?php
require_once(dirname(__FILE__)."/global.php");
if($selects=='city'){
	$name || $name='city';
	$show="<select name=$name onChange=\"select_where('zone',$(this).val(),'postdb[zone_id]',0)\">\r\n";
	$show.="<option value=''>请选择城市</option>\r\n";
	$query = $db->query("SELECT fid,name FROM {$pre}city ORDER BY fid ASC");
	while($rs = $db->fetch_array($query)){
		$ckk=($fids==$rs[fid])?"selected":"";
		$show.="<option value='$rs[fid]' $ckk>$rs[name]</option>\r\n";
	}
	$show.="</select>\r\n";
	echo $show;
	exit;
}
elseif($selects=='zone'){
	$name || $name='zone';
	$show="<select name=$name onChange=\"select_where('street',$(this).val(),'postdb[street_id]',0)\">\r\n";
	$show.="<option value=''>请选择区域</option>\r\n";
	if($fups){
		$query = $db->query("SELECT fid,name FROM {$pre}zone WHERE fup='$fups' ORDER BY fid ASC");
		while($rs = $db->fetch_array($query)){
			$ckk=($fids==$rs[fid])?"selected":"";
			$show.="<option value='$rs[fid]' $ckk>$rs[name]</option>\r\n";
		}
	}
	$show.="</select>\r\n";
	echo $show;
	exit;
}
elseif($selects=='street'){
	$name || $name='street';
	$show="<select name=$name>\r\n";
	$show.="<option value=''>请选择街道</option>\r\n";
	if($fups){
		$query = $db->query("SELECT fid,name FROM {$pre}street WHERE fup='$fups' ORDER BY fid ASC");
		while($rs = $db->fetch_array($query)){
			$ckk=($fids==$rs[fid])?"selected":"";
			$show.="<option value='$rs[fid]' $ckk>$rs[name]</option>\r\n";
		}
	}
	$show.="</select>\r\n";
	echo $show;
	exit;
}
?>