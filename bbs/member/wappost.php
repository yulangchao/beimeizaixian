<?php
require_once(dirname(__FILE__)."/global.php");
require_once(Mpath.'inc/function.php');

if(!$lfjid){
	showerr('���ȵ�¼!');
}
if(!$fid){
	showerr("FID������");
}

if($atc=='upmorepic'){
	if(!$upfile_str){
		die('��ȷ����û�гɹ��ϴ��κ�ͼƬ��');
	}else{
		$access_token = wx_getAccessToken();
		$wx_api_url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=";
		$path = ROOT_PATH."$webdb[updir]/$_pre/$fid/";
		if(!is_dir($path)){
			makepath($path);
		}
		$detail = explode(',',$upfile_str);
		$show='';
		$nowlist||$nowlist=0;
		$i=$nowlist;
		foreach($detail AS $pic){
			if($pic==''){
				continue;
			}
			$i++;
			$name = substr(md5(WEB_ID),-3).'_'.$lfjuid.'_'.rands(4).'.jpg';
			$strcode = file_get_contents($wx_api_url.$pic);
			write_file("$path/$name",$strcode);		

			$show.="<div class='PicList'><input class='text' type='text' name='photodb[$i]' value=\"$_pre/$fid/$name\" style='width:70%;'/></div>\r\n";
		}
		if(!$show){
			die('��ȷ����û�гɹ��ϴ��κ�ͼƬ��');
		}else{
			die($show);
		}
	}
	exit;
}

/**
*��ȡ��Ŀ�����ļ�
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr('��Ŀ����!');
}

if( !$web_admin ){
	if($fidDB[allowpost]){
		if( !in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
			showerr("�������û�����Ȩ����");
		}
	}elseif($webdb[allowGroupPost]){
		if( !in_array($groupdb[gid],explode(",",$webdb[allowGroupPost])) ){
			showerr("�������û�����Ȩ����!");
		}
	}
}

if($fidDB[type]){
	showerr("����,�����������⣡");
}

if($action=="edit"||$action=="postnew"){
	if(strlen($postdb[title])>150){
		showerr("�����ֽڸ������ܴ���150");
	}
	if(!$postdb[title]){	
		showerr("�������Ʋ���Ϊ��");
	}
}


$fidDB[mid]=1;
/**
*ģ�Ͳ��������ļ�
**/
$field_db = $module_DB[1][field];
$ifdp = $module_DB[1][ifdp];
$m_config[moduleSet][useMap] = $module_DB[1][config][moduleSet][useMap];

if($fidDB[type]){
	showerr("�����,������������");
}elseif( $fidDB[allowpost] && $action!="del" && in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
	showerr("�������û���,��Ȩ�ڱ���Ŀ����");
}

/**�����ύ���·�������**/
if($action=="postnew"){
		
	if(eregi("[a-z0-9]{15,}",$postdb[title])){
		showerr("������д�ñ���!");
	}
	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���	

	/*��������������*/
	$db->query("INSERT INTO `{$_pre}content` (`title` ,`fid` , `fname` ,  `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` , `keywords` , `ip` , `city_id` ) 
	VALUES (
	'$postdb[title]','$fid','$fidDB[name]','$timestamp','$timestamp','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','1','$postdb[keywords]','$onlineip','$city_id')");

	$id=$db->insert_id();

	//����ͼƬ
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=addslashes(filtrate($titledb[$key]));
		$value=filtrate($value);
		$db->query("INSERT INTO `{$_pre}pic` ( `id` , `fid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
		$postdb[picurl] || $postdb[picurl]=$value;
	}

	if($postdb[picurl]){
		$db->query("UPDATE `{$_pre}content` SET  picurl='$postdb[picurl]',ispic='1' WHERE id='$id'");
	}
	
	$db->query("INSERT INTO `{$_pre}content_1` (`id` , `fid` , `uid` , `topic` , `content`) VALUES ('$id', '$fid', '$lfjdb[uid]', '1', '$postdb[content]')");
	
	set_user_log(4);	//�û�������־

	refreshto("$Mdomain/wapbencandy.php?fid=$fid&id=$id","<a href='$Mdomain/wapbencandy.php?fid=$fid&id=$id' target='_blank'>�鿴Ч��</a> <a href='wappost.php?fid=$fid'>�����·���</a>",0);
}
if($job=="edit"){
	require(ROOT_PATH."inc/weixin.jsdk.php"); //���΢�ŷ����¼�
	$jssdk = new JSSDK($webdb[wxpay_AppID], $webdb[wxpay_AppSecret]);
	$signPackage = $jssdk->GetSignPackage();

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id'");
	
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content] = editor_replace($rsdb[content]);

	$atc="edit";

	require(ROOT_PATH."member/waphead.php");
	require(dirname(__FILE__)."/"."template/wappost.htm");
	require(ROOT_PATH."member/wapfoot.php");
	//require(ROOT_PATH."inc/wapfoot.php");
}elseif($action=="del"){	//ɾ������,ֱ��ɾ��,������

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid]){	
		showerr("��Ŀ������");
	}

	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ����");
	}


	$db->query("DELETE FROM `{$_pre}content` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}content_1` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}comments` WHERE id='$id' ");
	//�Ƹ�����
	Give_News_money($rsdb[uid],'del');
	if($rsdb[levels]){
		//Give_News_money($rsdb[uid],'uncom');
	}
	delete_attachment($rsdb[uid],tempdir($rsdb[picurl]));
	delete_attachment($rsdb[uid],$rsdb[content]);

	refreshto("waplist.php?job=list",'ɾ���ɹ�',1);
	

}
/*�����ύ���������޸�*/
elseif($action=="edit"){
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}
	$postdb[content] = preg_replace('/<(script)([^<>]*)>/i','&lt;\\1\\2>',$postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���
	
	$db->query("UPDATE `{$_pre}content` SET title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',city_id='$city_id',iframeurl='$postdb[iframeurl]',jumpurl='$postdb[jumpurl]',author='$postdb[author]',copyfrom='$postdb[copyfrom]',copyfromurl='$postdb[copyfromurl]' WHERE id='$id'");

	$db->query("UPDATE `{$_pre}content_1` SET content='$postdb[content]' WHERE id='$id' AND topic=1");

	set_user_log(5);	//�û�������־

	refreshto("../wapbencandy.php?fid=$fid&id=$id","<a href='../waplist.php?fid=$fid'>�����б�</a> <a href='../wapbencandy.php?fid=$fid&id=$id'>�鿴Ч��</a>",60);
}else{
	$job='postnew';

	//$select_mysort = select_mysort($lfjuid,'postdb[myfid]');

	require(ROOT_PATH."inc/weixin.jsdk.php"); //���΢�ŷ����¼�
	$jssdk = new JSSDK($webdb[wxpay_AppID], $webdb[wxpay_AppSecret]);
	$signPackage = $jssdk->GetSignPackage();
	require(ROOT_PATH."member/waphead.php");
	require(dirname(__FILE__)."/"."template/wappost.htm");
	require(ROOT_PATH."member/wapfoot.php");
	//require(ROOT_PATH."inc/wapfoot.php");
}
?>