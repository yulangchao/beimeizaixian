<?php
require_once(dirname(__FILE__).'/global.php');

if(!$lfjid){
	showerr("�㻹û��¼");	
}

if($IsMob){
	header("location:wapindex.php");
	exit;
}

if($web_admin){
	$power=3;
}elseif($lfjdb['grouptype']==1){
	$power=2;
}else{
	$power=1;
}

preg_match("/(.*)\/(index\.php|)\?main=(.+)/is",$WEBURL,$UrlArray);
$MainUrl=$UrlArray[3]?$UrlArray[3]:"map.php?uid=$lfjuid";
if(eregi('^http',$MainUrl)&&!eregi("^$webdb[www_url]",$MainUrl)){
	showerr('URL����ֹ��!');
}

if(!$nojump&&$webdb[_www_url]!=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL)){
	//�������ö����������ʻ�Ա����
	$url = strstr($WEBURL,'/member/');
	if(ereg('\/$',$url)){
		$url.="?nojump=1";
	}elseif(ereg('index\.php/$',$url)){
		$url.="?nojump=1";
	}elseif(ereg('\.php$',$url)){
		$url.="?nojump=1";
	}else{
		$url.="&nojump=1";
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[_www_url]".$url."'>";
	exit;
}
unset($menudb);

if(!table_field("{$pre}admin_menu",'ifcompany')){
	$db->query("ALTER TABLE `{$pre}admin_menu` ADD `ifcompany` TINYINT( 1 ) NOT NULL ");
}

unset($menudb1,$menudb2);

$lfjdb[grouptype]=='-1' && $lfjdb[grouptype]=0; //��Ա�������̴���֤ǰ���ֵ��-1������Ҫ�����������˻�Ա����.
//�跨��ȡ��̨�Զ���˵�
$query = $db->query("SELECT * FROM {$pre}admin_menu WHERE groupid='-$lfjdb[groupid]' AND fid=0 AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
$i=0;
while($rs = $db->fetch_array($query)){	
	$query2 = $db->query("SELECT * FROM {$pre}admin_menu WHERE fid='$rs[id]' AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
	while($rs2 = $db->fetch_array($query2)){
		//Ϊ���ݳ�����ڶ���Ŀ¼
		eregi("^\/",$rs2['linkurl']) && $rs2['linkurl'] = $webdb[_www_url].$rs2['linkurl'];
		$menudb[$rs[name]][$rs2[name]]['link']=$rs2['linkurl'];
	}
	$menudb1["my$i"][links]=$menudb[$rs[name]];
	$menudb1["my$i"][name]="$rs[name]";
	$i++;
}
//��̨�������Զ���˵�,����Ĭ�ϵ�
if(!$menudb){
	require_once(dirname(__FILE__)."/"."menu.php");

	$menudb1[base][links]=$menudb['��������'];
	$menudb1[base][name]='��������';

	$menudb1[hack][links]=$menudb['�������'];
	$menudb1[hack][name]='�������';	

	//��ȡģ��ϵͳ�Ļ�Ա�˵�
	$query = $db->query("SELECT * FROM {$pre}module WHERE type=2 AND ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if($rs['pre']=='hy_'&&$webdb['ForbidUpHy']&&$lfjdb['grouptype']!=1){
			continue;
		}
		$array=@include(ROOT_PATH."$rs[dirname]/member/menu.php");
		foreach($array AS $key=>$value){
			if($value['power']==2&&!$lfjdb[grouptype]&&!$web_admin){
				continue;	//��ҵ���ܲ���ʾ�ڻ�Ա�˵�����
			}
			$value['link']="$webdb[_www_url]/$rs[dirname]/member/".$value['link'];
			$menudb["$rs[name]"][$key]=$value;
		}
		if($rs['ifsys']){
			$menudb1["$rs[dirname]"][links]=$menudb["$rs[name]"];
			$menudb1["$rs[dirname]"][name]="$rs[name]";
		}else{
			$menudb2["$rs[dirname]"][links]=$menudb["$rs[name]"];
			$menudb2["$rs[dirname]"][name]="$rs[name]";
		}
	}
	$menudb1[other][links]=$menudb2;
	$menudb1[other][name]='����ģ��';
}

require(get_member_tpl('index'));


//��������������,�����Ļ�$webdb[www_url]='/.';
if($webdb[www_url]=='/.'){
	$content=str_replace('/./','/',ob_get_contents());
	ob_end_clean();
	echo $content;
}

?>