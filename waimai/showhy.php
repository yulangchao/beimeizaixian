<?php
require(dirname(__FILE__)."/global.php");

if($IsMob){
	header("location:wapshowhy.php?fid=$fid&id=$id");
	exit;
}

include_once(Mpath."data{$webdb[web_dir]}/guide_fid.php");

$GuideFid[$fid] = str_replace('list.php','listhy.php',$GuideFid[$fid]);


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


$db->query("UPDATE {$_pre}company SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");


/**
*��ȡ��Ϣ���ĵ�����
**/
$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

if($rsdb['storage']<1){
	$rsdb[yz] = 2;
	$db->query("UPDATE {$_pre}company SET yz='2' WHERE id='$id'");
}

if(!$rsdb){
	showerr("���ݲ�����");
}
elseif($rsdb[fid]!=$fid){
	showerr("FID����!!!");
}elseif($rsdb[yz]!=1&&!$web_admin){
	if($rsdb[yz]==2){
		//showerr("��Ʒ���¼�,���޷��鿴");
	}else{
		//showerr("��ûͨ�����");
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
//$Module_db->hidefield=true;
//$Module_db->classidShowAll=true;
//$Module_db->showfield($field_db,$rsdb,'show');

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);


//����ͷ����β��
get_show_tpl($head_tpl,$foot_tpl,$main_tpl);


//��ȡ��ǩ����
$template_file=getTpl("showhy",$main_tpl);
fetch_label_value(array('pagetype'=>'5','file'=>$template_file,'module'=>$webdb['module_id']));


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
if($rsdb[picnum]>0){
	$query = $db->query("SELECT * FROM {$_pre}pic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		$rs[imgurl]=tempdir($imgurl=$rs[imgurl]);
		$rs[picurl]=eregi("^http:",$imgurl)?$rs[imgurl]:"$rs[imgurl].gif";
		$picdb[]=$rs;
	}
}

//��ȡ�û���Ϣ
$companydb=$db->get_one("SELECT * FROM {$pre}hy_company WHERE uid='$rsdb[uid]'");

$listmysort=show_my_sort();

if($myfid){
	$myfid=intval($myfid);
	$mysortdb=$db->get_one("SELECT * FROM {$_pre}mysort WHERE uid='$rsdb[uid]' AND fid='$myfid'");
	$tagname=$mysortdb[name];
	$MYSQL=" LEFT JOIN {$_pre}mysort S ON A.myfid=S.fid WHERE (S.fup='$myfid' OR S.fid='$myfid') AND A.uid='$rsdb[uid]' ";
}else{
	$tagname='ȫ��';
	$MYSQL=" WHERE A.uid='$rsdb[uid]' ";
}

$listdb=array();
$rows=12;
$page||$page=1;
$min=($page-1)*$rows;

$query = $db->query("SELECT SQL_CALC_FOUND_ROWS A.* FROM {$_pre}content A $MYSQL ORDER BY A.list DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?fid=$fid&id=$id&myfid=$myfid",$rows,$totalNum);
while($rs = $db->fetch_array($query)){
	$rs[picurl]=$rs[picurl]?tempdir($rs[picurl]):"$webdb[www_url]/images/default/noimg.jpg";
	$listdb[]=$rs;
}

require(ROOT_PATH."inc/head.php");
require($template_file);
require(ROOT_PATH."inc/foot.php");

function show_my_sort(){
	global $db,$_pre,$rsdb,$myfid,$fid,$id;
	$query = $db->query("SELECT * FROM {$_pre}mysort WHERE uid='$rsdb[uid]' AND fup=0 ORDER BY list DESC");
	$show="";
	while($rs = $db->fetch_array($query)){
		$checks=($myfid==$rs[fid])?"class='ck'":"";
		$show.="<li $checks><a href='?myfid=$rs[fid]&fid=$fid&id=$id'>$rs[name]</a></li>\r\n";
		$query2 = $db->query("SELECT * FROM {$_pre}mysort WHERE fup='$rs[fid]' ORDER BY list DESC");
		while($ts = $db->fetch_array($query2)){
			$checks=($myfid==$ts[fid])?"class='ck'":"";
			$show.="<li $checks><a href='?myfid=$ts[fid]&fid=$fid&id=$id'>$ts[name]</a></li>\r\n";
		}
	}
	return $show;
}
?>