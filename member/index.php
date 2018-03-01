<?php
require_once(dirname(__FILE__).'/global.php');

if(!$lfjid){
	showerr("你还没登录");	
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
	showerr('URL被禁止的!');
}

if(!$nojump&&$webdb[_www_url]!=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL)){
	//不允许用二级域名访问会员中心
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

$lfjdb[grouptype]=='-1' && $lfjdb[grouptype]=0; //会员申请商铺待认证前这个值是-1，所以要把他当作个人会员看待.
//设法获取后台自定义菜单
$query = $db->query("SELECT * FROM {$pre}admin_menu WHERE groupid='-$lfjdb[groupid]' AND fid=0 AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
$i=0;
while($rs = $db->fetch_array($query)){	
	$query2 = $db->query("SELECT * FROM {$pre}admin_menu WHERE fid='$rs[id]' AND ifcompany='$lfjdb[grouptype]' ORDER BY list DESC");
	while($rs2 = $db->fetch_array($query2)){
		//为兼容程序放在二级目录
		eregi("^\/",$rs2['linkurl']) && $rs2['linkurl'] = $webdb[_www_url].$rs2['linkurl'];
		$menudb[$rs[name]][$rs2[name]]['link']=$rs2['linkurl'];
	}
	$menudb1["my$i"][links]=$menudb[$rs[name]];
	$menudb1["my$i"][name]="$rs[name]";
	$i++;
}
//后台不存在自定义菜单,则用默认的
if(!$menudb){
	require_once(dirname(__FILE__)."/"."menu.php");

	$menudb1[base][links]=$menudb['基本功能'];
	$menudb1[base][name]='基本功能';

	$menudb1[hack][links]=$menudb['插件功能'];
	$menudb1[hack][name]='插件功能';	

	//获取模块系统的会员菜单
	$query = $db->query("SELECT * FROM {$pre}module WHERE type=2 AND ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if($rs['pre']=='hy_'&&$webdb['ForbidUpHy']&&$lfjdb['grouptype']!=1){
			continue;
		}
		$array=@include(ROOT_PATH."$rs[dirname]/member/menu.php");
		foreach($array AS $key=>$value){
			if($value['power']==2&&!$lfjdb[grouptype]&&!$web_admin){
				continue;	//企业功能不显示在会员菜单那里
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
	$menudb1[other][name]='其它模块';
}

require(get_member_tpl('index'));


//处理内网的问题,内网的话$webdb[www_url]='/.';
if($webdb[www_url]=='/.'){
	$content=str_replace('/./','/',ob_get_contents());
	ob_end_clean();
	echo $content;
}

?>