<?php 
require_once("global.php");
require_once(ROOT_PATH."inc/weixin/fcuntion.php");

if(check_wx_power()==false){
	write_wx_log();
	exit('ERR!!!');
}elseif($echostr){	//���Խӿ��Ƿ�����
	echo $echostr;
	exit;
}

/*
$HTTP_RAW_POST_DATA=gbk2utf8('<xml><ToUserName><![CDATA[gh_e1cea97ba72c]]></ToUserName>
<FromUserName><![CDATA[odWDZt9sIdzZVk_H9AEgL6jMqCKw]]></FromUserName>
<CreateTime>1402986337</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[tewter432423drwre]]></Content>
<MsgId>6025780434350363721</MsgId>
</xml>');*/

$responseObj = simplexml_load_string($HTTP_RAW_POST_DATA, 'SimpleXMLElement', LIBXML_NOCDATA);
$wx_apiId = $responseObj->ToUserName;
$user_appId = $responseObj->FromUserName;
$From_content = $responseObj->Content;
WEB_LANG=='gb2312' && $From_content = utf82gbk($From_content);
$EventType = $responseObj->Event;
$EventKey = $responseObj->EventKey;
$MsgType = $responseObj->MsgType;
$MediaId = $responseObj->MediaId;
$ThumbMediaId = $responseObj->ThumbMediaId;
$PicUrl = $responseObj->PicUrl;


WEB_LANG=='gb2312' && $HTTP_RAW_POST_DATA = utf82gbk($HTTP_RAW_POST_DATA);

preg_match("/<ToUserName><!\[CDATA\[([-_A-Za-z0-9]+)\]\]><\/ToUserName>/is",$HTTP_RAW_POST_DATA,$array);
$WEBER=$array[1];
preg_match("/<FromUserName><!\[CDATA\[([-_A-Za-z0-9]+)\]\]><\/FromUserName>/is",$HTTP_RAW_POST_DATA,$array);
$touser=$array[1];
preg_match("/<Content><!\[CDATA\[(.*?)\]\]><\/Content>/is",$HTTP_RAW_POST_DATA,$array);
$TXT=$array[1];
preg_match("/<Event><!\[CDATA\[([_a-z0-9]+)\]\]><\/Event>/is",$HTTP_RAW_POST_DATA,$array);
$TYPE=$array[1];


$lfjdb = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$touser'");

if($lfjdb){
	$db->query("UPDATE `{$pre}memberdata` SET lastvist='$timestamp',lastip='$onlineip' WHERE uid='$lfjdb[uid]'");
}


if($TYPE=='subscribe'){
	$MSG=$webdb[weixin_welcome];	//��л���ע�벩���������㽫���Ե�һʱ����յ��ٷ����������°�ȫ��������������������ѣ�
	if($MSG==''){
		$array = array('title'=>$webdb[weixin_welcome_title],'picurl'=>tempdir($webdb[weixin_welcome_pic]),'about'=>$webdb[weixin_welcome_desc],'url'=>$webdb[weixin_welcome_link]);
		echo give_news(array(0=>$array));
		exit;
	}
}elseif($TYPE=='unsubscribe'){	//ȡ����ע��ͬʱ�⿪��
	$touser = filtrate($touser);
	$ws = $db->get_one("SELECT * FROM {$pre}memberdata WHERE weixin_api='$touser'");
	$db->query("UPDATE {$pre}memberdata SET weixin_api='' WHERE weixin_api='$touser'");
	add_user($ws['uid'],-$webdb['YZ_WeixinMoney'],'���΢�ſ۳�����');

}elseif($MsgType=='location'){	//���͵���λ��
	die();
}elseif($EventType=='location_select'){	//�ϴ�����λ��
	die();
}elseif($EventType=='LOCATION'){	//�Զ��ϴ�����λ��
	die();
}elseif($TXT!=''){
	
	$MSG=$webdb[weixin_problem];	//�ܱ�Ǹ���벩��������ʱ�����ܽ��������⣬���ܻ��ڽ�һ���Ŀ������У���ʱ���һʱ��֪ͨ��ң�
	
	if(eregi("^ע��",$TXT)){
	
		$username=substr($TXT,4);	//��Ϊע�����ĸ��ֽڣ�����ȡ֮�����Ч�û���
		require_once(ROOT_PATH."inc/weixin/yznum.inc.php");
		$MSG="���ע�����ǣ�$rand_num";
		
	}elseif(eregi("^��",$TXT)){
	
		$username=substr($TXT,4);	//��Ϊ�����ĸ��ֽڣ�����ȡ֮�����Ч�û���
		require_once(ROOT_PATH."inc/weixin/yznum.inc.php");
		$MSG="�����֤���ǣ�$rand_num";
		
	}else{	//�Ӵʿ�����ƥ��Ĵ�
		//$array = @include(ROOT_PATH.'data/weixin.php');
		//foreach($array AS $key=>$value){
		//	if( strstr($TXT,$key) ){
		//		$ts = $db->get_one("SELECT * FROM {$pre}weixinword WHERE id='$value'");
		//		$MSG = $ts['answer'];
		//		break;
		//	}			
		//}
		$From_content = $TXT;
		$user_appId = $touser;
		$wx_apiId = $WEBER;
		$array = @include(ROOT_PATH."data/weixin.php");
		foreach($array AS $key=>$value){
			if($From_content==$key || strstr($From_content,$key) ){
				$ts = $db->get_one("SELECT * FROM {$pre}weixinword WHERE id='$value'");
				if($ts[type]==0){	//���ı���Ϣ
					echo give_text( $ts['answer'] );
					exit;
				}elseif($ts[type]==1){	//ͼ����Ϣ
					$_array = unserialize($ts[answer]);
					$_arr = '';
					if(is_array($_array)){
						foreach($_array AS $_r){
							$_arr[] = array('title'=>$_r['title'],
															'picurl'=>$_r['pic'],
															'about'=>$_r['desc'],
															'url'=>$_r['link'],
															);
						}						
					}
					if($_arr)echo give_news($_arr);
					exit;
				}
				//break;
			}			
		}
		$MSG = kefu_reply();
		if($MSG!=''){
			echo give_text($MSG);
		}
		//echo give_text($webdb[weixin_problem]);
		exit;
	}	
}else{
	$MSG=$webdb[weixin_problem];	//"��֪��������ѯʲô��";	
}

WEB_LANG=='gb2312' && $MSG = gbk2utf8($MSG);

//write_wx_log();

echo "<xml>
<ToUserName><![CDATA[{$touser}]]></ToUserName>
<FromUserName><![CDATA[{$WEBER}]]></FromUserName>
<CreateTime>$timestamp</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[{$MSG}]]></Content>
</xml>";
exit;


//write_wx_log();
function give_text($MSG){
	global $user_appId,$wx_apiId,$timestamp;
	WEB_LANG=='gb2312' && $MSG = gbk2utf8($MSG);
	return "<xml>
	<ToUserName><![CDATA[{$user_appId}]]></ToUserName>
	<FromUserName><![CDATA[{$wx_apiId}]]></FromUserName>
	<CreateTime>$timestamp</CreateTime>
	<MsgType><![CDATA[text]]></MsgType>
	<Content><![CDATA[{$MSG}]]></Content>
	</xml>";
}


function kefu_reply($type=''){
	global $db,$pre,$webdb,$From_content,$timestamp,$lfjdb,$user_appId,$MediaId,$ThumbMediaId;
	
	if($webdb['WXRZ']==-1){
		return stripslashes($webdb[weixin_problem]);	//����֤�ʺŲ���ִ������Ĳ���
	}
	if($webdb[weixin_reply_kefu]!=''){	//��̨�����˿ͷ�UID
		$detail = explode(' ',$webdb[weixin_reply_kefu]);
		foreach($detail AS $value){
			is_numeric($value) && $uid_array[] = intval($value);
		}
	}else{
		$query = $db->query("SELECT * FROM {$pre}memberdata WHERE groupid=3");
		while($rs =$db->fetch_array($query)){
			$rs[weixin_api] && $uid_array[] = intval($rs[uid]);
		}
	}
	//if(!is_array($uid_array)){
		//return false;
	//}
	
	$uid_str = $uid_array?implode(',',$uid_array):0;
	if( in_array($lfjdb[uid],$uid_array) ){	//���ںŸ��ͷ������Ա��������Ϣ
	
		//�ж�ʹ���˶��ٸ���־���ʹ����������ĸ��û��ظ���Ϣ����־�������ǿո�
		if($webdb[weixin_reply_Tag]!='' && ereg("^$webdb[weixin_reply_Tag]",$From_content)){
			$i=-1;
			while(ereg("^$webdb[weixin_reply_Tag]",$From_content)){
				$i++;
				$From_content=substr($From_content,1);
			}
			$_SQL="$i,1";
			//$ms = $db->get_one("SELECT G.id,D.weixin_api FROM `{$pre}weixinmsg` G LEFT JOIN `{$pre}memberdata` D ON G.uid=D.uid WHERE G.uid NOT IN ($uid_str) ORDER BY G.id DESC LIMIT $_SQL ");
			$ms = $db->get_one("SELECT G.id,G.appid AS weixin_api FROM `{$pre}weixinmsg` G WHERE G.uid NOT IN ($uid_str) ORDER BY G.id DESC LIMIT $_SQL ");
			if( !send_wx_msg($ms[weixin_api],$From_content) ){
				$MSG = "�ͻ��뿪����2���ˣ���Ϣ����ʧ�ܣ�";
			}			
		}elseif($webdb[weixin_reply_Tag]!=''){
			$MSG = "�ͷ��ظ��û���Ϣ��ʹ�ñ�־����{$webdb[weixin_reply_Tag]}������Ȼϵͳ��֪�������˭����Ϣ";
		}else{
			$MSG = "�����̨���ñ�־������Ȼϵͳ��֪�������˭����Ϣ";
		}
		
	}else{	//�ͻ�ѯ��
	
		$ps = $db->get_one("SELECT lastvist FROM `{$pre}memberdata` WHERE uid IN ($uid_str) ORDER BY lastvist DESC LIMIT 1 ");

		$webdb[weixin_reply_Time]>=1 || $webdb[weixin_reply_Time]=1;	//�ͷ������Աָ�������Ϊ����
		
		if($timestamp-$ps[lastvist]<$webdb[weixin_reply_Time]*3600*3 ){	//�ͷ����ߵ������
			
			$query = $db->query("SELECT weixin_api FROM {$pre}memberdata WHERE uid IN ($uid_str)");
			while($rs =$db->fetch_array($query)){

				$lfjdb[username] || $lfjdb[username]='�ο�';
				//���ֿͷ��������2��ͻᷢ�Ͳ�����Ϣ������û���ж�
				if($type=='image'){
					send_wx_msg($rs[weixin_api],"���ԡ�{$lfjdb[username]}����ͼƬ");
					send_wx_msg($rs[weixin_api],'',array('type'=>'image','id'=>$MediaId));

				}elseif($type=='voice'){
					send_wx_msg($rs[weixin_api],"���ԡ�{$lfjdb[username]}��������");
					send_wx_msg($rs[weixin_api],'',array('type'=>'voice','id'=>$MediaId));

				}elseif($type=='video'){
					send_wx_msg($rs[weixin_api],"���ԡ�{$lfjdb[username]}���Ķ���Ƶ");
					send_wx_msg($rs[weixin_api],'',array('type'=>'video','id'=>$MediaId,'thumb_media_id'=>$ThumbMediaId));

				}else{
					send_wx_msg($rs[weixin_api],"��{$lfjdb[username]}��:$From_content");
				}
				$havesend++;
			}
			$MSG = $havesend?'':stripslashes($webdb[weixin_problem]); //���ͷ��ɹ�������Ϣ���Ͳ���Ҫ�ٸ��ͻ�����Ϣ�ˡ�
		}else{
			$MSG = stripslashes($webdb[weixin_problem]);	//�ͷ����ߣ����Ժ򣬹���Ա��ظ������Ϣ��
		}
	}

	if($type=='image'){
		$_type=1;
	}elseif($type=='voice'){
		$_type=2;
	}elseif($type=='video'){
		$_type=3;
	}elseif($type=='map'){
		$_type=4;
	}
	
	$From_content = filtrate($From_content);
	$db->query("INSERT INTO  `{$pre}weixinmsg` (`fid` ,  `appid` ,  `uid` ,  `username` ,  `posttime` ,  `content` ,  `type` ,  `url` ) VALUES ('$ms[id]',  '$user_appId',  '$lfjdb[uid]',  '$lfjdb[username]',  '$timestamp',  '$From_content',  '$_type',  '$MediaId')");
	
	//$str = check_addpage();	//��ʾ����΢��վ
	//$str!='' && $MSG=$str;
	
	return $MSG;
}


function give_news($array){
	global $user_appId,$wx_apiId,$timestamp;
	$num = count($array);
	foreach( $array AS $rs){
		if(WEB_LANG=='gb2312'){
			$rs[title] = gbk2utf8($rs[title]);
			$rs[about] = gbk2utf8($rs[about]);
		}
		$rs[picurl] && $rs[picurl] = tempdir($rs[picurl]);
		$string.="<item><Url><![CDATA[{$rs[url]}]]></Url>
		<PicUrl><![CDATA[{$rs[picurl]}]]></PicUrl>
		<Description><![CDATA[{$rs[about]}]]></Description>
		<Title><![CDATA[{$rs[title]}]]></Title></item>\r\n\r\n";
	}
	return "<xml><ToUserName><![CDATA[{$user_appId}]]></ToUserName>
				<FromUserName><![CDATA[{$wx_apiId}]]></FromUserName>
				<CreateTime>$timestamp</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<ArticleCount>$num</ArticleCount>
				<Articles>
				$string
				</Articles>
				<FuncFlag>0</FuncFlag>
				</xml>";
}


?>