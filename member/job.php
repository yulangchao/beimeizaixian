<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("�㻹û��¼");
}

if($job=='signIn'){
	showerr('���ڿ������У�');
	require(ROOT_PATH."inc/hongbao.function.php");
	if(weixin_hongbao_putIn(3)==false){
		refreshto("$webdb[www_url]/member/hongbaomoney.php","�������ǩ���ˣ��벻Ҫ�ظ�ǩ��",3);
	}else{
		refreshto("$webdb[www_url]/member/hongbaomoney.php","��ϲ�㣬ǩ���ɹ����{$webdb[EachSignInHongBao]}Ԫ���",2);
	}	
}elseif($job=='gethongbao'){
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/shop/generalize.php?job=get'>";
	exit;
}


?>