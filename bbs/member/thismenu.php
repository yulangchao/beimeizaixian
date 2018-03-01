<?php
require("global.php");
die();
if($web_admin){
	$power=3;
}elseif($lfjdb['grouptype']==1){
	$power=2;
}else{
	$power=1;
}

$lfjdb[grouptype]=='-1' && $lfjdb[grouptype]=0; //会员申请商铺待认证前这个值是-1，所以要把他当作个人会员看待.
//设法获取后台自定义菜单
$query = $db->query("SELECT * FROM {$pre}admin_menu WHERE groupid='-$lfjdb[groupid]' AND fid=0 AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	$query2 = $db->query("SELECT * FROM {$pre}admin_menu WHERE fid='$rs[id]' AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
	while($rs2 = $db->fetch_array($query2)){
		//为兼容程序放在二级目录
		eregi("^\/",$rs2['linkurl']) && $rs2['linkurl'] = $webdb[_www_url].$rs2['linkurl'];
		$menudb[$rs[name]][$rs2[name]]['link']=$rs2['linkurl'];
	}
}
if(!$menudb){
	$array=@include(dirname(__FILE__)."/menu.php");
	$checks[$listnum]="class='ck'";
	$listMenu="<div class='cheaders'>\r\n<ul>\r\n";
	$i=0;
	foreach($array AS $key=>$rs){
		$i++;
		$link=(strstr($rs[link],"?"))?$rs[link]."&listnum=$i":$rs[link]."?listnum=$i";
		$listMenu.="<li $checks[$i]><a href='$link'>$key</a></li>\r\n";
	}
	$listMenu.="</ul>\r\n</div>\r\n";
	echo $listMenu;
}
?>