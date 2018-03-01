<?php
require("global.php");

$mid=2;

/**
*获取信息正文的内容
**/
$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id'");


if(!$rsdb){
	showerr("内容不存在");
}elseif(!$web_admin&&$rsdb[uid]!=$lfjuid&&$rsdb[cuid]!=$lfjuid){
	showerr("你无权查看");
}



$id_array=array(intval($rsdb[cid]));
$detail = explode(',',$rsdb[products]);
foreach($detail AS $value){
	list($pid,$pnum) = explode('=',$value);
	$id_array[] = intval($pid);
	$parray[$pid] = $pnum;
}
$query2 = $db->query("SELECT * FROM {$_pre}content WHERE id IN (".implode(',',$id_array).")");
while($rs2 = $db->fetch_array($query2)){
	$rs2[num] = $parray[$rs2[id]];
	$rsdb['plist'][]=$rs2;
}

$field_db = $module_DB[$mid]['field'];

/**
*对信息内容字段的处理
**/
$Module_db->hidefield=true;
$Module_db->classidShowAll=true;
$Module_db->showfield($field_db,$rsdb,'show');


$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[ifpay]=$rsdb[ifpay]?'已支付':'未支付';
$rsdb[ifsend]=$rsdb[ifsend]?'已发货':'未发货';

if($rsdb[banktype]=='alipay'){
	$rsdb[banktype]='支付宝';
}elseif($rsdb[banktype]=='tenpay'){
	$rsdb[banktype]='财付通';
}elseif($rsdb[banktype]=='99pay'){
	$rsdb[banktype]='快钱';
}elseif($rsdb[banktype]=='yeepay'){
	$rsdb[banktype]='易宝支付';
}

require(ROOT_PATH."member/head.php");
require(getTpl("bencandy_$mid"));
require(ROOT_PATH."member/foot.php");

?>