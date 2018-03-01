<?php
define('Mpath',dirname(__FILE__).'/');
define( 'Mdirname' , preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );
define('SYS_TYPE','waimai');

require_once(Mpath."../inc/common.inc.php");
require_once(Mpath."data{$webdb[web_dir]}/config.php");			//ϵͳȫ�ֱ���
require_once(Mpath."data{$webdb[web_dir]}/all_fid.php");			//��Ŀ������
require_once(Mpath."data{$webdb[web_dir]}/module_db.php");			//ģ�������
require_once(Mpath."inc/function.php");
require_once(Mpath."inc/module.class.php");
require_once(ROOT_PATH."inc/self.tpl.php");		//����ģ�庯��


@include_once(ROOT_PATH."data{$webdb[web_dir]}/ad_cache.php");	//ȫվ�����������ļ�
@include_once(ROOT_PATH."data{$webdb[web_dir]}/label_hf.php");	//��ǩ��ͷ��׵ı���ֵ
@include_once(ROOT_PATH."data{$webdb[web_dir]}/module.php");		//ģ��ϵͳ�Ĳ�������ֵ



$_pre="{$pre}{$webdb[module_pre]}";					//���ݱ�ǰ׺

$Module_db=new Module_Field(Mpath);						//�Զ���ģ�����

$Murl=$webdb[_www_url].'/'.Mdirname;					//��ģ��ķ��ʵ�ַ
$city_url=$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;


unset($foot_tpl,$head_tpl,$index_tpl,$list_tpl,$bencandy_tpl);
$ch=intval($ch);
$fid=intval($fid);
$id=intval($id);
$page=intval($page);
$city_id=intval($city_id);
$cid=intval($cid);
$zone_id=intval($zone_id);
$street_id=intval($street_id);
@include_once(ROOT_PATH."data{$webdb[web_dir]}/zone/$city_id.php");
/**
*ǰ̨�Ƿ񿪷�
**/
if($webdb[module_close])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	showerr("��ϵͳ��ʱ�ر�:$webdb[Info_closeWhy]");
}

function list_title($type='new',$rows=10){
	global $db,$pre,$_pre,$city_id;

	if($type=='new'){
		$SQL = " WHERE A.city_id='$city_id' ORDER BY A.id DESC LIMIT $rows";
	}elseif($type=='hot'){
		$SQL = " WHERE A.city_id='$city_id' ORDER BY A.hits DESC LIMIT $rows";
	}elseif($type=='com'){
		$SQL = " WHERE A.levels=1 AND A.city_id='$city_id' ORDER BY A.levelstime DESC LIMIT $rows";
	}
	$query = $db->query("SELECT A.*,B.* FROM {$_pre}content A LEFT JOIN {$_pre}content_1 B ON B.id=A.id $SQL");
	while($rs = $db->fetch_array($query)){
		$rs[picurl] && $rs[picurl] = tempdir($rs[picurl]);
		$listdb[]=$rs;
	}
	return $listdb;
}


function ShowXingXing($fen){
	global $Murl;
	$show='';
	for($i=0;$i<$fen;$i++){
		$show.="<img src='$Murl/images/ordering/yellow_x.png'/>\r\n";
	}
	for($j=$fen;$j<5;$j++){
		$show.="<img src='$Murl/images/ordering/grey_x.png'/>\r\n";
	}
	return $show;
}


function ge_pingfen_pc($id){
	global $db,$_pre;
	@extract($db->get_one("SELECT COUNT(cid) AS TotalDp FROM `{$_pre}dianping` WHERE id=$id"));
	@extract($db->get_one("SELECT sum(fen1) AS Totalfen1 FROM `{$_pre}dianping` WHERE id=$id"));
	$thisFen=intval($Totalfen1/$TotalDp*10)/10;
	$thisPC=intval($thisFen/5*100);
	return $thisPC;
}

?>