<?php
require(dirname(__FILE__)."/global.php");

if($web_admin){
	$power=3;
}elseif($lfjdb['grouptype']==1){
	$power=2;
}else{
	$power=1;
}

$lfjdb[grouptype]=='-1' && $lfjdb[grouptype]=0; //��Ա�������̴���֤ǰ���ֵ��-1������Ҫ�����������˻�Ա����.


if(!$menudb){
	$array=@include(dirname(__FILE__)."/wapmenu.php");
	$checks[$listnum]="class='ck'";
	$listMenu="<div class='cheaders'>\r\n<ul>\r\n";
	$i=0;
	foreach($array AS $key=>$rs){
		if($rs['power']==2&&!$lfjdb[grouptype]&&!$web_admin){
			continue;	//��ҵ���ܲ���ʾ�ڻ�Ա�˵�����
		}
		$i++;
		$link=(strstr($rs[link],"?"))?$rs[link]."&listnum=$i":$rs[link]."?listnum=$i";
		$listMenu.="<li $checks[$i]><a href='$link'>$key</a></li>\r\n";
	}
	$listMenu.="</ul>\r\n</div>\r\n";
	echo $listMenu;
}
?>