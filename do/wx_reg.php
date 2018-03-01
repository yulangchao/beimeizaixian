<?php
define('LOGIN_PAGE',true);
require(dirname(__FILE__)."/"."global.php");
require(ROOT_PATH."inc/hongbao.function.php");

//$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];

if($lfjid){
	refreshto('login.php?action=quit',"你已经注册了,请不要重复注册,要注册,请先退出",3);
}

if($webdb[forbidReg]){
	showerr("很抱歉,网站关闭了注册");
}

if($webdb[forbidRegHour]){
	$webdb[forbidRegHour] = str_replace(array('24','　'),array('0',' '),$webdb[forbidRegHour]);
	$detail=explode(" ",$webdb[forbidRegHour]);
	if(in_array(ceil(date('H',$timestamp)),$detail)){
		showerr("系统设置当前时间段不允许注册");
	}
}

if($webdb[forbidRegIp]){
	$detail=explode("\r\n",$webdb[forbidRegIp]);
	foreach( $detail AS $key=>$value){
		//if(strstr($onlineip,$value)&&ereg("^$value",$onlineip)){
		if(strstr($onlineip,$value)){
			showerr("你所在IP禁止注册");
		}
	}
}
if($webdb[limitRegTime]&&get_cookie("limitRegTime")){
	showerr("{$webdb[limitRegTime]} 分钟内,请不要重复注册");
}

$check_attention = 0;
if($openid){
	$check_attention = wx_check_attention($openid);	//检查一下是否已关注过的老用户注册
}

if($state==1 || $check_attention){
	
	if($state){
		if(!$code){
			showerr('code 值不存在！');
		}
		$string = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]&code=$code&grant_type=authorization_code");
		
		$array = json_decode($string,true);
		
		if(!$array['access_token']){
			showerr('access_token 值不存在！');
		}elseif(!$array['openid']){
			showerr('openid 值不存在！');
		}
		
		$string2 = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=$array[access_token]&openid=$array[openid]&lang=zh_CN");
		
		$data = json_decode($string2,true);
		//file_put_contents(dirname(__FILE__)."/test4.txt", "{$_SERVER['REQUEST_URI']}\t{$_SERVER['HTTP_USER_AGENT']}\r\n".var_export($data,true), FILE_APPEND);
		if($data['nickname']=='' || $data['openid']==''){
			showerr('nickname 值不存在！');
		}
		$check_attention = wx_check_attention($data['openid']);
		$openid = $data['openid'];
	}else{
		$data = '';
		$check_attention = 1;
	}
	
	//统计分销系统的UID
	$Mdb = $introducer_1 = $introducer_2 = $introducer_3 = '';
	$introducer_uid = get_cookie('IntroducerUid');	
	if( $introducer_uid && $Mdb = $userDB->get_info($introducer_uid) ){
		$introducer_1 = $introducer_uid;
		$Mdb[introducer_1] && $introducer_2 = $Mdb[introducer_1];
		$Mdb[introducer_2] && $introducer_3 = $Mdb[introducer_2];
	}
		
	$Marray = array('check_attention'=>$check_attention,
									'introducer_1'=>$introducer_1,
									'introducer_2'=>$introducer_2,
									'introducer_3'=>$introducer_3,
							);
	
	$__FromPage && $Marray['pageid'] = intval($__FromPage);
							
	$lfjdb = $userDB->weixin_reg($openid,$data,$Marray);	//注册，数据入库
	
	if(!is_array($lfjdb)){
		showerr("注册失败,详情如下：$lfjdb");
	}

	$lfjuid = $lfjdb[uid];
	$lfjid = $username = $lfjdb[username];
	$password = $lfjdb[password];
	
	//用户登录
	$userDB->login($username,$password,3600);

	//注册时间间隔处理
	if($webdb[limitRegTime]){
		set_cookie("limitRegTime",1,$webdb[limitRegTime]*60);
	}
	
	$jumpto && $jumpto=urldecode($jumpto);

	
	//if($weixin_id && $webdb[YZ_WeixinMoney]){
	//	add_user($uid,$webdb[YZ_WeixinMoney],'绑定微信奖励积分');
	//}

	//propagandize_reg($uid,$propagandize_name);	//介绍用户注册奖励积分


	//set_user_log(1);	//用户访问日志
	
	/*
	if($Mdb){
			if($Mdb[weixin_api] && ($timestamp-$Mdb[lastvist]<3600*48) && wx_check_attention($Mdb[weixin_api]) ){
					$content = "恭喜你，通过分享链接，成功推荐 {$lfjid} 注册成为会员，今后只要他有消费，你都会有佣金提成！";
					send_wx_msg($Mdb[weixin_api],$content);
			}
	}
	*/
	
	weixin_hongbao_putIn(1); //注册送红包,推荐人也有红包
	
	
	
	
	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/wapindex.php";
	}

	//if(strstr($jumpto,$webdb[www_url])){
	//	refreshto("$jumpto","恭喜你，注册成功",1);
	//}else{
		refreshto($jumpto,"恭喜你，注册成功",0);
	//}
	
}else{
	
	//跳转到微信服务器再返回来，将会得到一个有效的code值。
	$url = urlencode($WEBURL);
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=https://open.weixin.qq.com/connect/oauth2/authorize?appid=$webdb[wxpay_AppID]&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect'>";
	exit;
}


?>