<?php
require_once(dirname(__FILE__).'/global.php');

if(!$lfjid){
	showerr("�㻹û��¼");
	
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
unset($menudb,$menudb3);




$lfjdb[grouptype]=='-1' && $lfjdb[grouptype]=0; //��Ա�������̴���֤ǰ���ֵ��-1������Ҫ�����������˻�Ա����.
//�跨��ȡ��̨�Զ���˵�
$query = $db->query("SELECT * FROM {$pre}admin_menu WHERE groupid='-$lfjdb[groupid]' AND fid=0 AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	$query2 = $db->query("SELECT * FROM {$pre}admin_menu WHERE fid='$rs[id]' AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
	while($rs2 = $db->fetch_array($query2)){
		//Ϊ���ݳ�����ڶ���Ŀ¼
		eregi("^\/",$rs2['linkurl']) && $rs2['linkurl'] = $webdb[_www_url].$rs2['linkurl'];
		
		//�ƶ����ļ�ǰ�����wap
		$rs2['linkurl']	= preg_replace("/(\/|)([_a-z0-9]+)\.php/is","\\1wap\\2.php",$rs2['linkurl']);

		$menudb3[$rs[name]][$rs2[name]]['link']=$rs2['linkurl'];
	}
}


//��̨�������Զ���˵�,����Ĭ�ϵ�
if(!$menudb3){

	require_once(dirname(__FILE__).'/wapmenu.php');

	//��ȡģ��ϵͳ�Ļ�Ա�˵�
	unset($menudb1,$menudb2);
	$query = $db->query("SELECT * FROM {$pre}module WHERE type=2 AND ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if($rs['pre']=='hy_'&&$webdb['ForbidUpHy']&&$lfjdb['grouptype']!=1){
			continue;
		}
		$array=@include(ROOT_PATH."$rs[dirname]/member/wapmenu.php");
		foreach($array AS $key=>$value){
			if($value['power']==2&&!$lfjdb[grouptype]&&!$web_admin){
				continue;	//��ҵ���ܲ���ʾ�ڻ�Ա�˵�����
			}
			$value['link']="$webdb[_www_url]/$rs[dirname]/member/".$value['link'];
			$menudb["$rs[name]"][$key]=$value;
			
			if($rs['ifsys']){
				$menudb1["$rs[name]"][$key]=$value;
			}else{
				$menudb2["$rs[name]"][$key]=$value;
			}
		}		
	}
}

//require(get_member_tpl('index'));
require("template/wap/index.htm");

//��������������,�����Ļ�$webdb[www_url]='/.';
if($webdb[www_url]=='/.'){
	$content=str_replace('/./','/',ob_get_contents());
	ob_end_clean();
	echo $content;
}

?>