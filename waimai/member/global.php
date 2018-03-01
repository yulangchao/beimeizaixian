<?php
define('Memberpath',dirname(__FILE__).'/');

require(Memberpath."../global.php");

/**
*前台是否开放
**/
if($webdb[module_close])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	showerr("本系统暂时关闭:$webdb[Info_closeWhy]");
}

if(!$lfjid){
	showerr("你还没登录");
}

if(count($_POST)<1&&!$nojump&&!strstr($webdb[_www_url],preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL))){
	//不允许用二级域名访问会员中心

	$url = strstr($WEBURL,'/'.basename(dirname(dirname(__FILE__))).'/member/');
	if(ereg('\/$',$url)){
		$url.="?nojump=1";
	}elseif(ereg('\.php$',$url)){
		$url.="?nojump=1";
	}else{
		$url.="&nojump=1";
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[_www_url]".$url."'>";
	exit;
}


function select_mysort($uid,$name='fup',$fid=0,$hideson=false){
	global $db,$_pre;
	
	if(!table_field("{$_pre}content",'myfid')){
		$db->query("ALTER TABLE  `{$_pre}content` ADD  `myfid` INT( 10 ) NOT NULL ");
		$db->query("ALTER TABLE  `{$_pre}content` ADD INDEX (  `myfid` ) ");		
		$db->insert_file('',"CREATE TABLE `{$_pre}mysort` (
  `fid` int(10) NOT NULL auto_increment,
  `fup` int(10) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
	) TYPE=MyISAM AUTO_INCREMENT=1 ;");
	}
	
	
	$select_fup="<select name='$name'><option value='0'>请选择...</option>";
	$query =$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$uid' AND fup=0 ORDER BY list DESC");
	while($rs =$db->fetch_array($query)){
		$ckk = $fid==$rs['fid']?' selected ':'';
		$select_fup.="<option value='$rs[fid]' $ckk>$rs[name]</option>";
		if($hideson==false){
			$query2 =$db->query("SELECT * FROM `{$_pre}mysort` WHERE fup='$rs[fid]' ORDER BY list DESC");
			while($rs2 =$db->fetch_array($query2)){
				$ckk = $fid==$rs2['fid']?' selected ':'';
				$select_fup.="<option value='$rs2[fid]' $ckk>&nbsp;&nbsp;|----$rs2[name]</option>";
			}		
		}
	}
	$select_fup.='</select>';
	return $select_fup;
}


?>