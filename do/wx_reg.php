<?php
define('LOGIN_PAGE',true);
require(dirname(__FILE__)."/"."global.php");
require(ROOT_PATH."inc/hongbao.function.php");

//$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];

if($lfjid){
	refreshto('login.php?action=quit',"���Ѿ�ע����,�벻Ҫ�ظ�ע��,Ҫע��,�����˳�",3);
}

if($webdb[forbidReg]){
	showerr("�ܱ�Ǹ,��վ�ر���ע��");
}

if($webdb[forbidRegHour]){
	$webdb[forbidRegHour] = str_replace(array('24','��'),array('0',' '),$webdb[forbidRegHour]);
	$detail=explode(" ",$webdb[forbidRegHour]);
	if(in_array(ceil(date('H',$timestamp)),$detail)){
		showerr("ϵͳ���õ�ǰʱ��β�����ע��");
	}
}

if($webdb[forbidRegIp]){
	$detail=explode("\r\n",$webdb[forbidRegIp]);
	foreach( $detail AS $key=>$value){
		//if(strstr($onlineip,$value)&&ereg("^$value",$onlineip)){
		if(strstr($onlineip,$value)){
			showerr("������IP��ֹע��");
		}
	}
}
if($webdb[limitRegTime]&&get_cookie("limitRegTime")){
	showerr("{$webdb[limitRegTime]} ������,�벻Ҫ�ظ�ע��");
}

$check_attention = 0;
if($openid){
	$check_attention = wx_check_attention($openid);	//���һ���Ƿ��ѹ�ע�������û�ע��
}

if($state==1 || $check_attention){
	
	if($state){
		if(!$code){
			showerr('code ֵ�����ڣ�');
		}
		$string = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]&code=$code&grant_type=authorization_code");
		
		$array = json_decode($string,true);
		
		if(!$array['access_token']){
			showerr('access_token ֵ�����ڣ�');
		}elseif(!$array['openid']){
			showerr('openid ֵ�����ڣ�');
		}
		
		$string2 = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=$array[access_token]&openid=$array[openid]&lang=zh_CN");
		
		$data = json_decode($string2,true);
		//file_put_contents(dirname(__FILE__)."/test4.txt", "{$_SERVER['REQUEST_URI']}\t{$_SERVER['HTTP_USER_AGENT']}\r\n".var_export($data,true), FILE_APPEND);
		if($data['nickname']=='' || $data['openid']==''){
			showerr('nickname ֵ�����ڣ�');
		}
		$check_attention = wx_check_attention($data['openid']);
		$openid = $data['openid'];
	}else{
		$data = '';
		$check_attention = 1;
	}
	
	//ͳ�Ʒ���ϵͳ��UID
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
							
	$lfjdb = $userDB->weixin_reg($openid,$data,$Marray);	//ע�ᣬ�������
	
	if(!is_array($lfjdb)){
		showerr("ע��ʧ��,�������£�$lfjdb");
	}

	$lfjuid = $lfjdb[uid];
	$lfjid = $username = $lfjdb[username];
	$password = $lfjdb[password];
	
	//�û���¼
	$userDB->login($username,$password,3600);

	//ע��ʱ��������
	if($webdb[limitRegTime]){
		set_cookie("limitRegTime",1,$webdb[limitRegTime]*60);
	}
	
	$jumpto && $jumpto=urldecode($jumpto);

	
	//if($weixin_id && $webdb[YZ_WeixinMoney]){
	//	add_user($uid,$webdb[YZ_WeixinMoney],'��΢�Ž�������');
	//}

	//propagandize_reg($uid,$propagandize_name);	//�����û�ע�ά������


	//set_user_log(1);	//�û�������־
	
	/*
	if($Mdb){
			if($Mdb[weixin_api] && ($timestamp-$Mdb[lastvist]<3600*48) && wx_check_attention($Mdb[weixin_api]) ){
					$content = "��ϲ�㣬ͨ���������ӣ��ɹ��Ƽ� {$lfjid} ע���Ϊ��Ա�����ֻҪ�������ѣ��㶼����Ӷ����ɣ�";
					send_wx_msg($Mdb[weixin_api],$content);
			}
	}
	*/
	
	weixin_hongbao_putIn(1); //ע���ͺ��,�Ƽ���Ҳ�к��
	
	
	
	
	$fromurl = get_cookie('From_url');
	if( $fromurl ){
		set_cookie('From_url','');
		$jumpto=$fromurl;
	}else{
		$jumpto="$webdb[www_url]/member/wapindex.php";
	}

	//if(strstr($jumpto,$webdb[www_url])){
	//	refreshto("$jumpto","��ϲ�㣬ע��ɹ�",1);
	//}else{
		refreshto($jumpto,"��ϲ�㣬ע��ɹ�",0);
	//}
	
}else{
	
	//��ת��΢�ŷ������ٷ�����������õ�һ����Ч��codeֵ��
	$url = urlencode($WEBURL);
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=https://open.weixin.qq.com/connect/oauth2/authorize?appid=$webdb[wxpay_AppID]&redirect_uri=$url&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect'>";
	exit;
}


?>