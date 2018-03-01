<?php
!function_exists('html') && exit('ERR');


if($action=="del"&&$Apower[user_log])
{
	foreach( $idDB AS $key=>$value){
		$db->query("DELETE FROM `{$pre}log` WHERE lid='$value'");
	}
	jump("删除成功","$FROMURL",1);
}
elseif($job=="list"&&$Apower[user_log])
{
	$rows=50;
	if(!$page){
		$page=1;
	}
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}log`"," ","?lfj=$lfj&job=$job",$rows);
	$query = $db->query("SELECT P.*,D.username FROM `{$pre}log` P LEFT JOIN {$pre}memberdata D ON P.uid=D.uid ORDER BY P.lid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		list($agent,$WAP_PROFILE,$PROFILE) = explode("\n",$rs[about]);
		$rs[browser] = getbrowser($agent);
		$rs[os] = get_os($agent);
		$rs[ismob] = check_mobile($WAP_PROFILE,$PROFILE,$agent);
		
		if(in_array($rs[type],array('4','5','6'))){
			$ts = $db->get_one("SELECT * FROM {$pre}$rs[systype]content WHERE id='$rs[id]'");
			$rs[tag] = "<a href='$webdb[www_url]/{$ModuleDB[$rs[systype]][dirname]}/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$ts[city_id]' target='_blank'>$rs[tag]</a>";
		}
		
		$rs[ipfrom]=ipfrom($rs[ip]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}

	hack_admin_tpl('list');
}
elseif($action=="set"&&$Apower[user_log])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
elseif($job=="set"&&$Apower[user_log])
{
	$if_set_user_log[intval($webdb[if_set_user_log])]=' checked ';

	hack_admin_tpl('set');
}

function check_mobile($WAP_PROFILE,$PROFILE,$agent){    
	$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";    
	$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";    
	$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";        
	$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";    
	$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";    
	$regex_match.=")/i";
	if( $WAP_PROFILE!='' or $PROFILE!='' or preg_match($regex_match, strtolower($agent)) ){
		return 'Mobile';
	}else{
		return 'PC';
	}	
}

function getbrowser($agent)  {
 $browser= ''; 
 $browser_ver= '';

 if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) 
 { 
  $browser='OmniWeb'; 
  $browser_ver= $regs[2]; 
 }

 if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) 
 { 
  $browser='Netscape'; 
  $browser_ver= $regs[2]; 
 }

 if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) 
 { 
  $browser='Safari'; 
  $browser_ver=$regs[1]; 
 }

 if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) 
 { 
  $browser='Internet Explorer'; 
  $browser_ver= $regs[1]; 
 }

 if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) 
 { 
  $browser='Opera'; 
  $browser_ver=$regs[1]; 
 }

 if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) 
 { 
  $browser='(Internet Explorer ' .$browser_ver. ') NetCaptor'; 
  $browser_ver= $regs[1]; 
 }

 if (preg_match('/Maxthon/i', $agent, $regs)) 
 { 
  $browser='(Internet Explorer ' .$browser_ver. ') Maxthon'; 
  $browser_ver=''; 
 }

 if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) 
 { 
  $browser='FireFox'; 
  $browser_ver=$regs[1]; 
 }

 if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) 
 { 
  $browser='Lynx'; 
  $browser_ver=$regs[1]; 
 }

 if ($browser != '') 
 { 
  return $browser.' '.$browser_ver; 
 } 
 else 
 { 
  return 'Unknow browser'; 
 } 
}



function get_os($agent) { 
     $os = ''; 
  
     if (eregi('win', $agent) && strpos($agent, '95')) 
     { 
       $os = 'Windows 95'; 
     } 
     else if (eregi('win 9x', $agent) && strpos($agent, '4.90')) 
     { 
       $os = 'Windows ME'; 
     } 
     else if (eregi('win', $agent) && ereg('98', $agent))
     {
       $os = 'Windows 98';
     }
     else if (eregi('win', $agent) && eregi('nt 6.0', $agent))
     {
       $os = 'Windows Vista';
     }
     else if (eregi('win', $agent) && eregi('nt 6.1', $agent))
     {
       $os = 'Windows 7';
     }
     else if (eregi('win', $agent) && eregi('nt 5.1', $agent))
     {
       $os = 'Windows XP';
     }
     else if (eregi('win', $agent) && eregi('nt 5', $agent))
     {
       $os = 'Windows 2000';
     }
     else if (eregi('win', $agent) && eregi('nt', $agent))
     {
       $os = 'Windows NT';
     }
     else if (eregi('win', $agent) && ereg('32', $agent))
     {
       $os = 'Windows 32';
     }
     else if (eregi('linux', $agent))
     {
       $os = 'Linux';
     }
     else if (eregi('unix', $agent))
     {
       $os = 'Unix';
     }
     else if (eregi('sun', $agent) && eregi('os', $agent))
     {
       $os = 'SunOS';
     }
     else if (eregi('ibm', $agent) && eregi('os', $agent))
     {
       $os = 'IBM OS/2';
     }
     else if (eregi('Mac', $agent) && eregi('PC', $agent))
     {
       $os = 'Macintosh';
     }
     else if (eregi('PowerPC', $agent))
     {
       $os = 'PowerPC';
     }
     else if (eregi('AIX', $agent))
     {
       $os = 'AIX';
     }
     else if (eregi('HPUX', $agent))
     {
       $os = 'HPUX';
     }
     else if (eregi('NetBSD', $agent))
     {
       $os = 'NetBSD';
     }
     else if (eregi('BSD', $agent))
     {
       $os = 'BSD';
     }
     else if (ereg('OSF1', $agent))
     {
       $os = 'OSF1';
     }
     else if (ereg('IRIX', $agent))
     {
       $os = 'IRIX';
     }
     else if (eregi('FreeBSD', $agent))
     {
       $os = 'FreeBSD';
     }
     else if (eregi('teleport', $agent))
     {
       $os = 'teleport';
     }
     else if (eregi('flashget', $agent))
     {
       $os = 'flashget';
     }
     else if (eregi('webzip', $agent))
     {
       $os = 'webzip';
     }
     else if (eregi('offline', $agent))
     {
       $os = 'offline';
     }
     else
     {
       $os = 'Unknown';
     }
     return $os;
}

?>