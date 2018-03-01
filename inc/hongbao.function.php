<?php


//给红包充值
function weixin_hongbao_putIn($type,$hongbaomoney=0){	//$type 的值含义 1为注册，2为分享链接，3为签到，4为推荐人，5为抢红包，6为大转盘
	global $db,$pre,$lfjuid,$lfjdb,$lfjid,$webdb,$onlineip,$timestamp,$userDB,$HongBaoGetTypeArray;
	
	if(!$lfjuid){
		return ;
	}
	
	$hongbao2DB = $db->get_one("SELECT * FROM `{$pre}wxhongbao` WHERE uid='$lfjuid' AND type='$type' ORDER BY id DESC");
	
	if($type==1){	//注册送一个红包
		$money = $webdb[RegHongBao];
		if($money>0){
			add_rmb($lfjuid,$money,0,'注册送红包');
			$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0)");
		}
		add_user($lfjuid,$webdb[RegJF],'注册得积分');
		if($lfjdb[introducer_1] && $webdb[CommendRegHongBao]){	//给推荐人也发红包
				$mDB = $userDB->get_info($lfjdb[introducer_1]);
				if($mDB[wx_attention]){	//用户如果取消了关注，就不再有佣金
					add_rmb($lfjdb[introducer_1],$webdb[CommendRegHongBao],0,"推荐“{$lfjid}”注册,奖励红包");
					$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjdb[introducer_1]',  '$mDB[username]',  '$timestamp',  '4',  '$webdb[CommendRegHongBao]',  '$onlineip',0)");
					if($mDB[weixin_api] && ($timestamp-$mDB[lastvist]<3600*48) && wx_check_attention($mDB[weixin_api]) ){
						$content = "恭喜你，获得￥“{$webdb[CommendRegHongBao]}”元的红包奖励，因为你分享链接推荐“{$lfjid}”注册";
						send_wx_msg($mDB[weixin_api],$content);
					}
					add_user($lfjdb[introducer_1],$webdb[EachSignInJF],'推荐“{$lfjid}”注册得积分');
				}				
		}
		
		return true;
		
	}elseif($type==2){	//分享链接送红包，一天限制一个
		global $WEBURL;
		$url = filtrate( str_replace($webdb[www_url],'',$WEBURL) );
		if(!$hongbao2DB){	//未分享过链接
			$money = $webdb[EachShareHongBao];
			//$money = $webdb[FirstShareHongBao];	//首次分享是个大红包
			add_user($lfjuid,$webdb[EachShareJF],'首次转发分享得积分');
			//if($money>0){				//不注释的话。会反复刷积分
				
				$money && add_rmb($lfjuid,$money,0,'首次转发分享送红包');

				$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`,  `url`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0,  '$url')");				
				return true;
			//}
			//也可以把推荐人得红包的代码放这里，提高一下门槛
			
		}else{
		
			$money = $webdb[EachShareHongBao];
			
			if( date('Ymd',$hongbao2DB[posttime])==date('Ymd',$timestamp) ){
				return false;	//同一天内，只能送一次红包
			}else{
				add_user($lfjuid,$webdb[EachShareJF],'转发分享得积分');
				//if($money>0){	//不注释的话。会反复刷积分

					$money && add_rmb($lfjuid,$money,0,'每天转发分享送红包');
					$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`,  `url`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0,  '$url')");
					return true;
				//}
			}
		}
	}elseif($type==3){	//每天签到送一个红包
		if( date('Ymd',$hongbao2DB[posttime])==date('Ymd',$timestamp) ){
			return false;	//同一天内，只能送一次红包
		}
		add_user($lfjuid,$webdb[EachSignInJF],'每天签到得积分');
		$money = $webdb[EachSignInHongBao];
		//if($money>0){	//不注释的话。会反复刷积分
			
			add_rmb($lfjuid,$money,0,'每天签到送一个红包');

			$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0)");
			$db->query("UPDATE  `{$pre}memberdata` SET signin_time='$timestamp' WHERE uid='$lfjuid'");
			return true;
		//}
	}elseif($type && $hongbaomoney){

		add_rmb($lfjuid,$hongbaomoney,0,"通过“{$HongBaoGetTypeArray[$type]}”得到奖励");
		$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$hongbaomoney',  '$onlineip',0)");
		return $hongbaomoney;
	}
	return false;
}

//限制范围内收红包
function Limit_map_check($appid,$map=''){
	global $db,$pre,$webdb;
	if(!$webdb[left_top_maps] ||!$webdb[right_top_maps] ||!$webdb[left_bottom_maps] ||!$webdb[right_bottom_maps]){
		return true;
	}
	if(!$map){
		$rsdb = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$appid'");
		$map = $rsdb[maps];
	}
	
	if(!$map){
		return false;
	}
	
	list($M_y,$M_x) = explode(',',$map);
	list($LT_y,$LT_x) = explode(',',$webdb[left_top_maps]);
	//list($RT_y,$RT_x) = explode(',',$webdb[right_top_maps]);
	//list($LB_y,$LB_x) = explode(',',$webdb[left_bottom_maps]);
	list($RB_y,$RB_x) = explode(',',$webdb[right_bottom_maps]);
	//$Ty = $LT_y>$RT_y ? $LT_y : $RT_y ;
	//$By = $LB_y<$RB_y ? $LB_y : $RB_y ;
	//$Lx = $LT_x<$LB_x ? $LT_x : $LB_x ;
	//$Rx = $RT_x>$RB_x ? $RT_x : $RB_x ;
	//if($M_y>$Ty || $M_y<$By){
	//	return false;
	//}
	//if($M_x<$Lx || $M_x>$Rx){
	//	return false;
	//}
	if($M_y>$LT_y || $M_y<$RB_y || $M_x<$LT_x || $M_x>$RB_x){
		return false;
	}
	return true;
}

//发放红包
function weixin_hongbao_sendOut($Array=array('uid'=>'','id'=>'','money'=>'','title'=>'','name'=>''),$num=1){
	global $webdb;
	
	//if(!$webdb[HongBao_autoGive]){
		//add_rmb($Array[uid],$Array[money],0,'红包转入');
		//return 'ok';
	//}

	$Array[title] || $Array[title]='恭喜发财';
	$Array[name] || $Array[name]='齐博公司';
	$Array[id] || $Array[id]='oQ_-puMsC3CwwnQCZy5xtkDuVuXI';
	
	if($num>1){	//裂变红包，可以转发
		if($num<3)$num=3;	//最小要3
		if($Array[money]<$num)$Array[money]=$num; //每个人最少要1块钱
		$wxHongBaoArray["amt_type"]='ALL_RAND';		//全部随机,比现金红包只多了这一项参数
		$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
	}else{
		$num=1;
		if($Array[money]<1)$Array[money] = 1;	//最少要1块钱
		$wxHongBaoArray["client_ip"]=$_SERVER[SERVER_ADDR];//调用接口的机器 Ip 地址 ,比裂变红包多了这个参数
		$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	}
	
	$totalmoney = read_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt");
	if($Array[money]>$totalmoney){
		send_wx_msg($Array[id],"红包发送失败，商家帐户余额不足，请联系商家给微信商户平台充值！");
		return '红包发送失败，商家帐户余额不足，请联系商家给微信商户平台充值！';
	}
	
	if( Limit_map_check($Array[id])==false ){
		send_wx_msg($Array[id],"红包发送失败，获取不到你的地址，或者是在收红包的地址范围之外不能收红包！<a href='$webdb[www_url]/do/get_map.php'>点击重新获取定位，再领取红包</a>");
		return '获取不到你的地址，或者是在收红包的地址范围之外不能收红包！';
	}

	$wxHongBaoArray["nonce_str"]=rands(10);		//随机字符串，小于 32 位
	$wxHongBaoArray["mch_billno"]=$webdb[wxpay_ID].date('YmdHis').rand(1000,9999);		//订单号
	$wxHongBaoArray["mch_id"]=$webdb[wxpay_ID];		//商户号
	$wxHongBaoArray["wxappid"]=$webdb[wxpay_AppID];
		
	$wxHongBaoArray["send_name"]=$Array[name];		//红包发送者名称
	$wxHongBaoArray["re_openid"]=$Array[id];		//收款的openid
	$wxHongBaoArray["total_amount"]=$Array[money]*100;		//付款金额，单位分

	$wxHongBaoArray["total_num"]=$num;		//红包发放总人数，最少3人
	$wxHongBaoArray["wishing"]=$Array[title];		//红包祝福
	$wxHongBaoArray["act_name"]='test';		//活动名称，基本用不到
	$wxHongBaoArray["remark"]='test';		//备注信息，基本用不到

	//$wxHongBaoArray["min_value"]=100;//最小红包金额，单位分
	//$wxHongBaoArray["max_value"]=100;//最大红包金额，单位分
	//$wxHongBaoArray["nick_name"]='红包';//提供方名称

	if(WEB_LANG!='utf-8'){
		$wxHongBaoArray["send_name"]=gbk2utf8($wxHongBaoArray["send_name"]);
		$wxHongBaoArray["wishing"]=gbk2utf8($wxHongBaoArray["wishing"]);
	}

	ksort($wxHongBaoArray);

	$string = arrayToUrl($wxHongBaoArray);

	$wxHongBaoArray["sign"] = strtoupper(md5( $string."&key=".$webdb[wxpay_ApiKey] ));

	$xml_string = arrayToXml($wxHongBaoArray);

	$contentXml = wxHttpsRequestPem($Url, $xml_string);

	$objXml = simplexml_load_string($contentXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	
	if( strstr($contentXml,'SUCCESS') ){
		
		write_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt",$totalmoney-$Array[money]);
		
		return 'ok';
	}else{
		if($objXml->return_msg){
			if(WEB_LANG!='utf-8'){
				$errMsg = filtrate( utf82gbk($objXml->return_msg) );
			}
		}else{
			$errMsg = filtrate($contentXml);
		}
		
		send_wx_msg($Array[id],"红包发送失败，微信服务器反馈信息如下：$errMsg");
		
		return $errMsg;
	}
}


//现金转帐，不需要关注公众号
function weixin_money_sendOut( $Array=array('id'=>'','money'=>'','title'=>'') ,$errtype=false){
	global $webdb;

	$Array[title] || $Array[title]='恭喜发财';
	$Array[id] || $Array[id]='oQ_-puMsC3CwwnQCZy5xtkDuVuXI';
	
	if($Array[money]<1)$Array[money] = 1;	//最少要1钱

	$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	
	$totalmoney = read_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt");
	if($Array[money]>$totalmoney){
		if($errtype==true){
			return '无法实时转帐，商家帐户余额不足，请联系商家给微信商户平台充值！';
		}else{
			showerr('无法实时转帐，商家帐户余额不足，请联系商家给微信商户平台充值！');
		}
	}

	$serverIP = $_SERVER[SERVER_ADDR];
	if(!$serverIP){
		$serverIP=file_get_contents("http://www.qibosoft.com/ip.php?weburl=$webdb[www_url]");
	}
	if(!$serverIP){
		showerr('无法获取服务器所在IP！');
	}

	$wxHongBaoArray["mch_appid"] = $webdb[wxpay_AppID];
	$wxHongBaoArray["mchid"] = $webdb[wxpay_ID];		//商户号
	$wxHongBaoArray["nonce_str"] = rands(10);		//随机字符串，小于 32 位
	$wxHongBaoArray["partner_trade_no"] = $webdb[wxpay_ID].date('YmdHis').rand(1000,9999);		//订单号
	$wxHongBaoArray["openid"] = $Array[id];		//收款的openid
	$wxHongBaoArray["check_name"] = 'NO_CHECK';
	$wxHongBaoArray["amount"] = $Array[money]*100;		//付款金额，单位分 $Array[money]*100
	$wxHongBaoArray["desc"] = $Array[title];		//备注信息
	$wxHongBaoArray["spbill_create_ip"] = $serverIP;//调用接口的机器 Ip 地址

	if(WEB_LANG!='utf-8'){
		$wxHongBaoArray["desc"]=gbk2utf8($wxHongBaoArray["desc"]);
	}

	ksort($wxHongBaoArray);

	$string = arrayToUrl($wxHongBaoArray);

	$wxHongBaoArray["sign"] = strtoupper(md5( $string."&key=".$webdb[wxpay_ApiKey] ));

	$xml_string = arrayToXml($wxHongBaoArray);

	$contentXml = wxHttpsRequestPem($Url, $xml_string);

	$objXml = simplexml_load_string($contentXml, 'SimpleXMLElement', LIBXML_NOCDATA);


	if( strstr($contentXml,'SUCCESS')&&!strstr($contentXml,'err_code_des') ){
		
		write_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt",$totalmoney-$Array[money]);
		
		return 'ok';
	}else{
		if($objXml->return_msg){
			if(WEB_LANG!='utf-8'){
				$errMsg = utf82gbk($objXml->return_msg);
			}
		}else{
			$errMsg = filtrate($contentXml);
		}
		return $errMsg;
	}
}



function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else{
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        	 } 
        }
        $xml.="</xml>";
        return $xml; 
}

function arrayToUrl($paraMap, $urlencode=0){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
			if (null != $v && "null" != $v && "sign" != $k) {
			    if($urlencode){
				   $v = urlencode($v);
				}
				$buff .= $k . "=" . $v . "&";
			}
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
}

function wxHttpsRequestPem($url, $vars, $second=30,$aHeader=array()){
	global $webdb;
                $ch = curl_init();
                //超时时间
                curl_setopt($ch,CURLOPT_TIMEOUT,$second);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                //这里设置代理，如果有的话
                //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
                //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
 
                //以下两种方式需选择一种
 
                //第一种方法，cert 与 key 分别属于两个.pem文件
                //默认格式为PEM，可以注释
                curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/apiclient_cert.pem");
                //默认格式为PEM，可以注释
                curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/apiclient_key.pem");
 
                curl_setopt($ch,CURLOPT_CAINFO,'PEM');
                curl_setopt($ch,CURLOPT_CAINFO,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/rootca.pem");
 
                //第二种方式，两个文件合成一个.pem文件  
                //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
 
                if( count($aHeader) >= 1 ){
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
                }
 
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
                $data = curl_exec($ch);
                if($data){
                        curl_close($ch);
                        return $data;
                }
                else { 
                        $error = curl_errno($ch);
                        echo "call faild, errorCode:$error\n"; 
                        curl_close($ch);
                        return false;
                }
}

?>