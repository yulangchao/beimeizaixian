<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("你还没登录");
}

if($job=='signIn'){
	showerr('还在开发当中！');
	require(ROOT_PATH."inc/hongbao.function.php");
	if(weixin_hongbao_putIn(3)==false){
		refreshto("$webdb[www_url]/member/hongbaomoney.php","你今天已签到了，请不要重复签到",3);
	}else{
		refreshto("$webdb[www_url]/member/hongbaomoney.php","恭喜你，签到成功获得{$webdb[EachSignInHongBao]}元红包",2);
	}	
}elseif($job=='gethongbao'){
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/shop/generalize.php?job=get'>";
	exit;
}


?>