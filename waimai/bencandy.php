<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapbencandy.php?fid=$fid&id=$id");
	exit;
}

include_once(Mpath."data{$webdb[web_dir]}/guide_fid.php");


/**
*��ȡ��Ŀ��ģ�����ò���
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID����!");
}

/**
*ģ�������ļ�
**/
$field_db = $module_DB[$fidDB[mid]][field];


/**
*��Ŀ���ò���
*��Ŀ�����ļ��û��Զ���ı���
*��Ŀ����,�û��Զ��������Щʹ�������߱༭��Ҫ��������������ʵ��ַ������
**/
$fidDB[config]=unserialize($fidDB[config]);
$CV=$fidDB[config][field_value];
$_array=array_flip($fidDB[config][is_html]);
foreach( $fidDB[config][field_db] AS $key=>$rs){
	if(in_array($key,$_array)){
		$CV[$key]=En_TruePath($CV[$key],0);
	}elseif($rs[form_type]=='upfile'){
		$CV[$key]=tempdir($CV[$key]);
	}
}


$db->query("UPDATE {$_pre}content SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");


/**
*��ȡ��Ϣ���ĵ�����
**/
$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

if($rsdb['storage']<1){
	$rsdb[yz] = 2;
	$db->query("UPDATE {$_pre}content SET yz='2' WHERE id='$id'");
}

if(!$rsdb){
	showerr("���ݲ�����");
}
elseif($rsdb[fid]!=$fid){
	showerr("FID����!!!");
}elseif($rsdb[yz]!=1&&!$web_admin){
	if($rsdb[yz]==2){
		showerr("��Ʒ���¼�,���޷��鿴");
	}else{
		showerr("��ûͨ�����");
	}
}


/**
*����ҳ�ķ����������Ŀ�ķ��,��Ŀ�ķ��������ϵͳ�ķ��
**/
if($rsdb[style]){
	$STYLE=$rsdb[style];
}elseif($fidDB[style]){
	$STYLE=$fidDB[style];
}


//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title] - {$city_DB[name][$city_id]}$fidDB[name] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags($rsdb[keywords]));
$titleDB[description]	= filtrate(get_word(preg_replace('/<([^<]*)>/is',"",$rsdb[content]),200).strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));




/**
*����Ϣ�����ֶεĴ���
**/
$Module_db->hidefield=true;
$Module_db->classidShowAll=true;
$Module_db->showfield($field_db,$rsdb,'show');

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);


//����ͷ����β��
get_show_tpl($head_tpl,$foot_tpl,$main_tpl);


//��ȡ��ǩ����
$template_file=getTpl("bencandy_$fidDB[mid]",$main_tpl);
fetch_label_value(array('pagetype'=>'3','file'=>$template_file,'module'=>$webdb['module_id']));


//��ȡ�û���Ϣ
if($rsdb[uid]){
	$userdb=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$rsdb[uid]'");
	$userdb[username]=$rsdb[username];
	$userdb[regdate]=date("y-m-d H:i",$userdb[regdate]);
	$userdb[lastvist]=date("y-m-d H:i",$userdb[lastvist]);
	$userdb[icon]=tempdir($userdb[icon]);
	$userdb[level]=$ltitle[$userdb[groupid]];
}else{
	$userdb[username]=preg_replace("/([\d]+)\.([\d]+)\.([\d]+)\.([\d]+)/is","\\1.\\2.*.*",$rsdb[ip]);
	$userdb[level]="�ο�";
}

$rsdb[username]=(!$rsdb[username])?"*�ο�*":"$rsdb[username]";

//�ϴ���ͼ��
unset($picdb);
if($rsdb[picnum]>1){
	$query = $db->query("SELECT * FROM {$_pre}pic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		$rs[imgurl]=tempdir($imgurl=$rs[imgurl]);
		$rs[picurl]=eregi("^http:",$imgurl)?$rs[imgurl]:"$rs[imgurl].gif";
		$picdb[]=$rs;
	}
}

//��ȡ�û���Ϣ
$companydb=$db->get_one("SELECT * FROM {$pre}hy_company WHERE uid='$rsdb[uid]'");



require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");


?>