<?php
require_once(dirname(__FILE__)."/global.php");

if(!$Apower['appmenu']){
	showerr('你没权限!');
}

if( !table_field("{$pre}memberdata",'weixin_api') ){
	$db->query("ALTER TABLE  `{$pre}memberdata` ADD  `weixin_api` VARCHAR( 32 ) NOT NULL");
	$db->query("ALTER TABLE  `{$pre}memberdata` ADD INDEX (  `weixin_api` )");
}

if(!is_table("{$pre}wxlogin")){
	$db->query("CREATE TABLE `{$pre}wxlogin` (
  `usrid` varchar(15) NOT NULL default '',
  `sid` varchar(15) NOT NULL default '',
  `usrinfo` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  UNIQUE KEY `usrid` (`usrid`),
  KEY `sid` (`sid`)
	) ENGINE=MyISAM;");
}

if( !is_table("{$pre}weixinmenu") ){
	
	$db->insert_file('',"CREATE TABLE IF NOT EXISTS `{$pre}weixinmenu` (
	  `id` mediumint(5) NOT NULL auto_increment,
	  `uid` int(7) NOT NULL default '0',
	  `fid` mediumint(5) NOT NULL default '0',
	  `name` varchar(80) NOT NULL default '',
	  `keyword` varchar(255) NOT NULL default '',
	  `linkurl` varchar(150) NOT NULL default '',
	  `type` tinyint(2) NOT NULL default '0',
	  `hide` tinyint(1) NOT NULL default '0',
	  `list` smallint(4) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;");

	$db->insert_file('',"CREATE TABLE IF NOT EXISTS `{$pre}weixinmsg` (
	  `id` int(10) NOT NULL auto_increment,
	  `fid` int(10) NOT NULL default '0',
	  `appid` varchar(32) NOT NULL default '',
	  `uid` int(7) NOT NULL default '0',
	  `username` varchar(50) NOT NULL default '',
	  `posttime` int(10) NOT NULL default '0',
	  `content` text NOT NULL,
	  `type` tinyint(1) NOT NULL default '0',
	  `url` varchar(100) NOT NULL default '',
	  PRIMARY KEY  (`id`),
	  KEY `uid` (`uid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;");

	$db->query("ALTER TABLE  `{$pre}memberdata` CHANGE  `rmb`  `rmb` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0'");
	$db->query("ALTER TABLE  `{$pre}memberdata` CHANGE  `rmb_freeze`  `rmb_freeze` DECIMAL( 10, 2 ) NOT NULL DEFAULT  '0'");

	$db->query("ALTER TABLE  `{$pre}weixinword` ADD  `type` TINYINT( 1 ) NOT NULL");
}

if( !is_table("{$pre}weixinword") ){
	$db->insert_file('',"CREATE TABLE IF NOT EXISTS `{$pre}weixinword` (
	  `id` int(10) NOT NULL auto_increment,
	  `ask` varchar(150) NOT NULL default '',
	  `answer` text NOT NULL,
	  `list` int(10) NOT NULL default '0',
	  `type` tinyint(1) NOT NULL default '0',
	  PRIMARY KEY  (`id`),
	  KEY `list` (`list`),
	  KEY `type` (`type`)
	) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;");
}

if( !is_table("{$pre}login_check") ){
	$db->insert_file('',"CREATE TABLE IF NOT EXISTS `{$pre}login_check` (
	  `usr` varchar(32) NOT NULL default '',
	  `uid` int(10) NOT NULL default '0',
	  `posttime` int(10) NOT NULL default '0',
	  UNIQUE KEY `usr` (`usr`)
	) ENGINE=MEMORY DEFAULT CHARSET=gbk;");
}




if($action=='add'){

	if($postdb[name]==''){
		showerr('名称不能为空！');
	}elseif($postdb[keyword]=='' && $postdb[linkurl]==''){
		showerr('关键词与其它链接地址不能同时为空！');
	}elseif($postdb[linkurl]!='' && !eregi('^(http:|\/)',$postdb[linkurl]) ){
		showerr('网址必须是http://或斜杠/开头！');
	}
	
	foreach($postdb AS $key=>$value){
		$postdb[$key] = filtrate($value);
	}
	
	if($postdb[fid]){
		$SQL = " AND fid='$postdb[fid]' ";
	}else{
		$SQL = " AND fid='0' ";
	}	
	$ts = $db->get_one("SELECT COUNT(*) AS NUM FROM `{$pre}weixinmenu` WHERE hide=0 $SQL");
	if($postdb[fid]){
		if($ts[NUM]>=5){
			showerr('当前分类下的子菜单不能超过5个');
		}
	}else{
		if($ts[NUM]>=3){
			showerr('父级菜单不能超过3个');
		}
	}	
	
	$db->query("INSERT INTO  `{$pre}weixinmenu`  ( `uid` ,  `fid` ,  `name` ,  `keyword` ,  `linkurl`  ) VALUES ('$lfjuid',  '$postdb[fid]',  '$postdb[name]',  '$postdb[keyword]',  '$postdb[linkurl]')");
	refreshto($FROMURL,'添加成功',3);
	
}elseif($action=='delete'){

	foreach( $iddb AS $value){
		$db->query("DELETE FROM `{$pre}weixinmenu` WHERE id='$value'");
	}
	refreshto($FROMURL,'删除成功',3);

}elseif($action=='edit'){
	if($postdb[name]==''){
		showerr('名称不能为空！');
	}elseif($postdb[keyword]=='' && $postdb[linkurl]==''){
		showerr("关键词与其它链接地址不能同时为空！$postdb[url]");
	}elseif($postdb[fid]==$id){
		showerr('父分类不能是自己！');
	}elseif($postdb[linkurl]!='' && !eregi('^(http:|\/)',$postdb[linkurl]) ){
		showerr('网址必须是http://或斜杠/开头！');
	}
	foreach($postdb AS $key=>$value){
		$postdb[$key] = filtrate($value);
	}
	
	$db->query("UPDATE `{$pre}weixinmenu` SET fid='$postdb[fid]',name='$postdb[name]',`keyword`='$postdb[keyword]',`linkurl`='$postdb[linkurl]',`list`='$postdb[list]' WHERE id='$id'");
	refreshto($FROMURL,'修改成功',3);
	
}elseif($job=='edit'){

	$rsdb = $db->get_one("SELECT * FROM `{$pre}weixinmenu` WHERE id='$id'");
	
	$selectWapmenu = select_wapmenu('postdb[fid]',$rsdb[fid]);

	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/appmenu/appmenu.htm");
	require(dirname(__FILE__).'/foot.php');
	
}
elseif($action=='makemenu'){	
	//对菜单重新编辑开始
	$db->query("TRUNCATE TABLE `{$pre}weixinmenu` ");
	$detail=explode("#",$postdb[menulist]);
	$fups=array();
	$i=0;
	foreach($detail AS $key=>$value){		
		$detail1=explode("@",$value);
		$fup=intval($detail1[0]);
		$name=$postdb[$fup][name];
		$keyword=$postdb[$fup][keyword];
		$linkurl=$postdb[$fup][linkurl];
		$type=$postdb[$fup][type];		
		if($fup&&$name&&$i<3){
			$i++;
			$db->query("INSERT INTO  `{$pre}weixinmenu`  (`uid`,`fid`,`name`,`keyword`,`linkurl`,`type`) VALUES ('$lfjuid',  '0',  '$name','$keyword','$linkurl','$type')");	
			$detai2=explode(",",$detail1[1]);
			foreach($detai2 AS $keys=>$rss){
				$fid=intval($rss);
				if($fid){				
					$fups[$i][]=$fup.','.$fid;
				}
			}
		}		
	}
	
	foreach($fups AS $key=>$value){
		$j=0;
		foreach($value AS $keys=>$rss){
			$arrays=explode(",",$rss);
			$fup=$arrays[0];
			$fid=$arrays[1];

			$name1=$postdb[$fup][$fid][name];
			$keyword1=$postdb[$fup][$fid][keyword];
			$linkurl1=$postdb[$fup][$fid][linkurl];
			$type1=$postdb[$fup][$fid][type];
			if($key&&$name1&&$j<5){
				$j++;
				$db->query("INSERT INTO  `{$pre}weixinmenu`  (`uid`,`fid`,`name`,`keyword`,`linkurl`,`type`) VALUES ('$lfjuid',  '$key',  '$name1','$keyword1','$linkurl1','$type1')");
			}
		}					
	}
	//对菜单重新编辑结束

	if(!is_file(ROOT_PATH."inc/class.json.php")){
		showerr('inc/class.json.php该文件不存在！');
	}
	require_once(ROOT_PATH."inc/class.json.php");
	$jsonObj = new Services_JSON();
	
	if(!function_exists('curl_init')){
		showerr('你的空间不支持“curl_init”函数，请联系空间商配置服务器使之支持该函数');
	}
	
	$Marray = $array = array();
	$i=-1;

	$query =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid='0' AND hide=0 ORDER BY list DESC LIMIT 3");
	while($rs =$db->fetch_array($query)){
		$i++;
		$Marray[$i]['name'] = change_lang($rs['name']);
		$j=-1;
		$query2 =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid='$rs[id]' AND hide=0 ORDER BY list DESC LIMIT 5");
		while($rs2 =$db->fetch_array($query2)){
			$j++;
			if($rs2['linkurl']){
				if($rs2['linkurl']=='map'){
					$type = 'location_select';
					$Marray[$i]['sub_button'][$j]['key'] = change_lang($rs2['keyword']);
				}else{
					eregi('^http:',$rs2['linkurl']) || $rs2['linkurl']=$webdb[www_url].$rs2['linkurl'];
					$type = 'view';
					$Marray[$i]['sub_button'][$j]['url'] = change_lang($rs2['linkurl']);
				}
			}else{
				$type = 'click';
				$Marray[$i]['sub_button'][$j]['key'] = change_lang($rs2['keyword']);
			}
			$Marray[$i]['sub_button'][$j]['type'] = $type;
			$Marray[$i]['sub_button'][$j]['name'] = change_lang($rs2['name']);			
		}
		
		if(!is_array($Marray[$i]['sub_button'])){
			if($rs['linkurl']){
				eregi('^http:',$rs['linkurl']) || $rs['linkurl']=$webdb[www_url].$rs['linkurl'];
				$type = 'view';
				$Marray[$i]['url'] = change_lang($rs['linkurl']);
			}else{
				$type = 'click';
				$Marray[$i]['key'] = change_lang($rs['keyword']);
			}
			$Marray[$i]['type'] = $type;
		}
	}
	$array['button']=$Marray;
	$data = $jsonObj->encode((object)$array);
	$data = urldecode($data);;

	
	//$string = curl_post("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]");
	//if(!strstr($string,'access_token')){
	//	showerr('当前服务器空间配置有问题，获取access_token失败!');
	//}
	//{"access_token":"vn7B8gAvcguFuyJqC_TZkJEJ_gGpZdhgsCjAtQaw4t-eS8wkX4F5pcU-7YKLuzB-fCfwaio1JIMqlgJ0444qeqkua3c85zbIvDMfm7mMVvY","expires_in":7200}	
	//preg_match("/\"([^\"]{32,})\"/is",$s,$array);
	//$access_token = $array[1];
	//$jsonArr = get_object_vars($jsonObj->decode($string));
	//$access_token = $jsonArr['access_token'];
	
	$access_token = wx_getAccessToken();
	
	//curl_post("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access_token");	//先删除旧菜单	
	http_Curl("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access_token");	//先删除旧菜单	

	//$code = curl_post("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token",$data);
	$code = http_Curl("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token",$data);
	
	$res = json_decode($code);
	
	if($res->errcode!=0){
	
		$wx_errorcodeArray = @include(ROOT_PATH.'inc/wx_errorcode.php');
		
		$msg = $wx_errorcodeArray[$res->errcode];
		if($msg!=''){
			showerr("菜单生成失败，原因是：$msg");
		}else{
			showerr('<a href="http://mp.weixin.qq.com/wiki/17/fa4e1434e57290788bde25603fa2fcbd.html" target="_blank">生成失败,请点击查看对应的错误代码原因：</a><br>'.$code);
		}
		//string(58) "{"errcode":40054,"errmsg":"invalid sub button url domain"}"
	}elseif(strstr($code,'ok')){
		refreshto($FROMURL,'设置成功，你需要重新关注微信才能看得到效果',3);
	}else{
		showerr('当前服务器空间配置有问题，导致菜单生成失败!');		
	}
	
}else{

	$selectWapmenu = select_wapmenu('postdb[fid]');

	$page<1 && $page=1;
	$min = ($page-1)*20;
	$listdb = array();
	$query =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid=0 ORDER BY list DESC LIMIT $min,20");
	while($rs = $db->fetch_array($query)){
		$listdb[] = $rs;
		$query2 =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid='$rs[id]'");
		while($rs2 =$db->fetch_array($query2)){
			$rs2[icon]='|-------';
			$listdb[] = $rs2;
		}
		
	}

	$query1 =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid=0 ORDER BY list ASC");
	while($rs = $db->fetch_array($query1)){		
		$query2 =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid='$rs[id]'");
		while($rs2 =$db->fetch_array($query2)){
			$rs[children][]=$rs2;
		}
		$listdb1[] = $rs;
	}
	
	require(dirname(__FILE__).'/head.php');
	require(dirname(__FILE__)."/"."template/appmenu/appmenu.htm");
	//require(dirname(__FILE__).'/foot.php');
}

function select_wapmenu($name,$ckfid=0){
	global $pre,$db,$lfjuid;
	
	$query =$db->query("SELECT * FROM `{$pre}weixinmenu` WHERE fid=0 ORDER BY list DESC");
	while($rs =$db->fetch_array($query)){
		$ck = $ckfid==$rs[id] ? 'selected' : '' ;
		$show.="<option value='$rs[id]' $ck>$rs[name]</option>";
	}
	
	return "<select name='$name'><option value='0'>请选择..</option>$show</select>";
}

function change_lang($word){
	WEB_LANG=='gb2312' && $word = gbk2utf8($word);
	$word = urlencode($word);
	return $word;
}


function curl_post($url,$data=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$info = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'Errno'.curl_error($ch);
	}
	curl_close($ch);
	return $info;
}

?>