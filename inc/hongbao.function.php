<?php


//�������ֵ
function weixin_hongbao_putIn($type,$hongbaomoney=0){	//$type ��ֵ���� 1Ϊע�ᣬ2Ϊ�������ӣ�3Ϊǩ����4Ϊ�Ƽ��ˣ�5Ϊ�������6Ϊ��ת��
	global $db,$pre,$lfjuid,$lfjdb,$lfjid,$webdb,$onlineip,$timestamp,$userDB,$HongBaoGetTypeArray;
	
	if(!$lfjuid){
		return ;
	}
	
	$hongbao2DB = $db->get_one("SELECT * FROM `{$pre}wxhongbao` WHERE uid='$lfjuid' AND type='$type' ORDER BY id DESC");
	
	if($type==1){	//ע����һ�����
		$money = $webdb[RegHongBao];
		if($money>0){
			add_rmb($lfjuid,$money,0,'ע���ͺ��');
			$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0)");
		}
		add_user($lfjuid,$webdb[RegJF],'ע��û���');
		if($lfjdb[introducer_1] && $webdb[CommendRegHongBao]){	//���Ƽ���Ҳ�����
				$mDB = $userDB->get_info($lfjdb[introducer_1]);
				if($mDB[wx_attention]){	//�û����ȡ���˹�ע���Ͳ�����Ӷ��
					add_rmb($lfjdb[introducer_1],$webdb[CommendRegHongBao],0,"�Ƽ���{$lfjid}��ע��,�������");
					$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjdb[introducer_1]',  '$mDB[username]',  '$timestamp',  '4',  '$webdb[CommendRegHongBao]',  '$onlineip',0)");
					if($mDB[weixin_api] && ($timestamp-$mDB[lastvist]<3600*48) && wx_check_attention($mDB[weixin_api]) ){
						$content = "��ϲ�㣬��ã���{$webdb[CommendRegHongBao]}��Ԫ�ĺ����������Ϊ����������Ƽ���{$lfjid}��ע��";
						send_wx_msg($mDB[weixin_api],$content);
					}
					add_user($lfjdb[introducer_1],$webdb[EachSignInJF],'�Ƽ���{$lfjid}��ע��û���');
				}				
		}
		
		return true;
		
	}elseif($type==2){	//���������ͺ����һ������һ��
		global $WEBURL;
		$url = filtrate( str_replace($webdb[www_url],'',$WEBURL) );
		if(!$hongbao2DB){	//δ���������
			$money = $webdb[EachShareHongBao];
			//$money = $webdb[FirstShareHongBao];	//�״η����Ǹ�����
			add_user($lfjuid,$webdb[EachShareJF],'�״�ת������û���');
			//if($money>0){				//��ע�͵Ļ����ᷴ��ˢ����
				
				$money && add_rmb($lfjuid,$money,0,'�״�ת�������ͺ��');

				$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`,  `url`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0,  '$url')");				
				return true;
			//}
			//Ҳ���԰��Ƽ��˵ú���Ĵ����������һ���ż�
			
		}else{
		
			$money = $webdb[EachShareHongBao];
			
			if( date('Ymd',$hongbao2DB[posttime])==date('Ymd',$timestamp) ){
				return false;	//ͬһ���ڣ�ֻ����һ�κ��
			}else{
				add_user($lfjuid,$webdb[EachShareJF],'ת������û���');
				//if($money>0){	//��ע�͵Ļ����ᷴ��ˢ����

					$money && add_rmb($lfjuid,$money,0,'ÿ��ת�������ͺ��');
					$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`,  `url`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0,  '$url')");
					return true;
				//}
			}
		}
	}elseif($type==3){	//ÿ��ǩ����һ�����
		if( date('Ymd',$hongbao2DB[posttime])==date('Ymd',$timestamp) ){
			return false;	//ͬһ���ڣ�ֻ����һ�κ��
		}
		add_user($lfjuid,$webdb[EachSignInJF],'ÿ��ǩ���û���');
		$money = $webdb[EachSignInHongBao];
		//if($money>0){	//��ע�͵Ļ����ᷴ��ˢ����
			
			add_rmb($lfjuid,$money,0,'ÿ��ǩ����һ�����');

			$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$money',  '$onlineip',0)");
			$db->query("UPDATE  `{$pre}memberdata` SET signin_time='$timestamp' WHERE uid='$lfjuid'");
			return true;
		//}
	}elseif($type && $hongbaomoney){

		add_rmb($lfjuid,$hongbaomoney,0,"ͨ����{$HongBaoGetTypeArray[$type]}���õ�����");
		$db->query("INSERT INTO  `{$pre}wxhongbao` ( `uid` ,  `username` ,  `posttime` ,  `type` ,  `money`,  `ip` , `ifpay`) VALUES ( '$lfjuid',  '$lfjid',  '$timestamp',  '$type',  '$hongbaomoney',  '$onlineip',0)");
		return $hongbaomoney;
	}
	return false;
}

//���Ʒ�Χ���պ��
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

//���ź��
function weixin_hongbao_sendOut($Array=array('uid'=>'','id'=>'','money'=>'','title'=>'','name'=>''),$num=1){
	global $webdb;
	
	//if(!$webdb[HongBao_autoGive]){
		//add_rmb($Array[uid],$Array[money],0,'���ת��');
		//return 'ok';
	//}

	$Array[title] || $Array[title]='��ϲ����';
	$Array[name] || $Array[name]='�벩��˾';
	$Array[id] || $Array[id]='oQ_-puMsC3CwwnQCZy5xtkDuVuXI';
	
	if($num>1){	//�ѱ���������ת��
		if($num<3)$num=3;	//��СҪ3
		if($Array[money]<$num)$Array[money]=$num; //ÿ��������Ҫ1��Ǯ
		$wxHongBaoArray["amt_type"]='ALL_RAND';		//ȫ�����,���ֽ���ֻ������һ�����
		$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
	}else{
		$num=1;
		if($Array[money]<1)$Array[money] = 1;	//����Ҫ1��Ǯ
		$wxHongBaoArray["client_ip"]=$_SERVER[SERVER_ADDR];//���ýӿڵĻ��� Ip ��ַ ,���ѱ��������������
		$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	}
	
	$totalmoney = read_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt");
	if($Array[money]>$totalmoney){
		send_wx_msg($Array[id],"�������ʧ�ܣ��̼��ʻ����㣬����ϵ�̼Ҹ�΢���̻�ƽ̨��ֵ��");
		return '�������ʧ�ܣ��̼��ʻ����㣬����ϵ�̼Ҹ�΢���̻�ƽ̨��ֵ��';
	}
	
	if( Limit_map_check($Array[id])==false ){
		send_wx_msg($Array[id],"�������ʧ�ܣ���ȡ������ĵ�ַ�����������պ���ĵ�ַ��Χ֮�ⲻ���պ����<a href='$webdb[www_url]/do/get_map.php'>������»�ȡ��λ������ȡ���</a>");
		return '��ȡ������ĵ�ַ�����������պ���ĵ�ַ��Χ֮�ⲻ���պ����';
	}

	$wxHongBaoArray["nonce_str"]=rands(10);		//����ַ�����С�� 32 λ
	$wxHongBaoArray["mch_billno"]=$webdb[wxpay_ID].date('YmdHis').rand(1000,9999);		//������
	$wxHongBaoArray["mch_id"]=$webdb[wxpay_ID];		//�̻���
	$wxHongBaoArray["wxappid"]=$webdb[wxpay_AppID];
		
	$wxHongBaoArray["send_name"]=$Array[name];		//�������������
	$wxHongBaoArray["re_openid"]=$Array[id];		//�տ��openid
	$wxHongBaoArray["total_amount"]=$Array[money]*100;		//�������λ��

	$wxHongBaoArray["total_num"]=$num;		//�������������������3��
	$wxHongBaoArray["wishing"]=$Array[title];		//���ף��
	$wxHongBaoArray["act_name"]='test';		//����ƣ������ò���
	$wxHongBaoArray["remark"]='test';		//��ע��Ϣ�������ò���

	//$wxHongBaoArray["min_value"]=100;//��С�������λ��
	//$wxHongBaoArray["max_value"]=100;//���������λ��
	//$wxHongBaoArray["nick_name"]='���';//�ṩ������

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
		
		send_wx_msg($Array[id],"�������ʧ�ܣ�΢�ŷ�����������Ϣ���£�$errMsg");
		
		return $errMsg;
	}
}


//�ֽ�ת�ʣ�����Ҫ��ע���ں�
function weixin_money_sendOut( $Array=array('id'=>'','money'=>'','title'=>'') ,$errtype=false){
	global $webdb;

	$Array[title] || $Array[title]='��ϲ����';
	$Array[id] || $Array[id]='oQ_-puMsC3CwwnQCZy5xtkDuVuXI';
	
	if($Array[money]<1)$Array[money] = 1;	//����Ҫ1Ǯ

	$Url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	
	$totalmoney = read_file(ROOT_PATH."data{$webdb[web_dir]}/weixinMoney.txt");
	if($Array[money]>$totalmoney){
		if($errtype==true){
			return '�޷�ʵʱת�ʣ��̼��ʻ����㣬����ϵ�̼Ҹ�΢���̻�ƽ̨��ֵ��';
		}else{
			showerr('�޷�ʵʱת�ʣ��̼��ʻ����㣬����ϵ�̼Ҹ�΢���̻�ƽ̨��ֵ��');
		}
	}

	$serverIP = $_SERVER[SERVER_ADDR];
	if(!$serverIP){
		$serverIP=file_get_contents("http://www.qibosoft.com/ip.php?weburl=$webdb[www_url]");
	}
	if(!$serverIP){
		showerr('�޷���ȡ����������IP��');
	}

	$wxHongBaoArray["mch_appid"] = $webdb[wxpay_AppID];
	$wxHongBaoArray["mchid"] = $webdb[wxpay_ID];		//�̻���
	$wxHongBaoArray["nonce_str"] = rands(10);		//����ַ�����С�� 32 λ
	$wxHongBaoArray["partner_trade_no"] = $webdb[wxpay_ID].date('YmdHis').rand(1000,9999);		//������
	$wxHongBaoArray["openid"] = $Array[id];		//�տ��openid
	$wxHongBaoArray["check_name"] = 'NO_CHECK';
	$wxHongBaoArray["amount"] = $Array[money]*100;		//�������λ�� $Array[money]*100
	$wxHongBaoArray["desc"] = $Array[title];		//��ע��Ϣ
	$wxHongBaoArray["spbill_create_ip"] = $serverIP;//���ýӿڵĻ��� Ip ��ַ

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
                //��ʱʱ��
                curl_setopt($ch,CURLOPT_TIMEOUT,$second);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                //�������ô�������еĻ�
                //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
                //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
 
                //�������ַ�ʽ��ѡ��һ��
 
                //��һ�ַ�����cert �� key �ֱ���������.pem�ļ�
                //Ĭ�ϸ�ʽΪPEM������ע��
                curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/apiclient_cert.pem");
                //Ĭ�ϸ�ʽΪPEM������ע��
                curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
                curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/apiclient_key.pem");
 
                curl_setopt($ch,CURLOPT_CAINFO,'PEM');
                curl_setopt($ch,CURLOPT_CAINFO,ROOT_PATH."data{$webdb[web_dir]}/olpay/weixin_cert_{$webdb[mymd5]}/rootca.pem");
 
                //�ڶ��ַ�ʽ�������ļ��ϳ�һ��.pem�ļ�  
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