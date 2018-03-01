<?php
require(dirname(__FILE__)."/global.php");

if(!$lfjuid){
	showerr("请先登录");
}elseif(!$uid){
	$uid=$lfjuid;
}

$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:6;

if($job=='editpicsort'){
	$name=filtrate($name);
	$remarks=filtrate($remarks);
	$psid=filtrate($psid);
	if($atc=='delpicsort'){
		$mypics=$db->get_one("SELECT count(*) AS num FROM {$_pre}pic WHERE psid='$psid'");
		if($mypics[num]>0){
			die("havepic");
		}
		$db->query("DELETE FROM {$_pre}picsort WHERE psid='$psid' AND uid='$uid'");
	}else{
		if(!$name){
			die("no");
		}
		if($psid){ //更新
			$db->query("UPDATE `{$_pre}picsort` SET `name`='$name',`remarks`='$remarks' WHERE psid='$psid' AND uid='$uid'");
		}else{
			$mypicsortnum=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}picsort WHERE uid='$uid' ");
			if($mypicsortnum[num]>=$webdb[company_picsort_Max]){
				die("no");
			}
			$db->query("INSERT INTO `{$_pre}picsort` (`name` , `remarks` , `uid` , `username` ,`posttime`) VALUES ('$name', '$remarks', '$uid', '$lfjid', '$timestamp');");
		}
	}
	$show='';
	$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC LIMIT 0,$webdb[company_picsort_Max];");
	while($rs=$db->fetch_array($query)){
		$rs[faceurl]=tempdir($rs[faceurl]);
		$show.="<div class='listsort psid$rs[psid]'>			
			<dl>
				<dt><a href='?atn=piclist&amp;uid=$uid&amp;psid=$rs[psid]'><img src='$rs[faceurl]' onerror=\"this.src='$Murl/images/default/userpicsortdefault.gif';\"/></a></dt>
				<dd>
					<h3>$rs[name]</h3>
					<p>$rs[remarks]</p>
					<div class='act'>
						<a href=\"javascript:;editPicSort($rs[psid])\">编辑</a>
						<a href=\"javascript:;delPicSort($rs[psid])\">删除</a>
					</div>
				</dd>
			</dl>
		</div>";
	}
	echo $show;
	exit;
}

if($job=='setpicsortfac'){
	$picdb=$db->get_one("SELECT url FROM {$_pre}pic WHERE uid='$uid' AND pid='$pid'");
	if(is_file(ROOT_PATH."$webdb[updir]/{$picdb[url]}.gif")){
		$faceurl="{$picdb[url]}.gif";
	}else{
		$faceurl=$picdb[url];
	}
	$db->query("UPDATE `{$_pre}picsort` SET `faceurl`='$faceurl' WHERE psid='$psid' AND uid='$uid'");
	$faceurl=tempdir($faceurl);
	echo $faceurl;
	exit;
}

if($job=='edittitle'){
	$title=filtrate($title);
	$db->query("UPDATE `{$_pre}pic` SET `title`='$title' WHERE pid='$pid' AND uid='$uid'");
	exit;
}

if($job=='delpic'){
	if($pid){
		$rt=$db->get_one("SELECT url FROM {$_pre}pic WHERE pid='$pid'");
		delete_attachment($uid,tempdir($rt[url]));
		delete_attachment($uid,tempdir("$rt[url].gif"));
		$db->query("DELETE FROM {$_pre}pic WHERE pid='$pid' AND uid='$uid'");
	}
	$atn="piclist";
	$listpics=list_thisSortPic();
	echo $listpics;
	exit;
}

if($job=='wxpicupload'){
	if(!$upfile_str){
		die('请确认有没有成功上传任何图片！');
	}else{
		$access_token = wx_getAccessToken();
		$wx_api_url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=";
		$path = ROOT_PATH."$webdb[updir]/homepage/pic/$uid/";
		if(!is_dir($path)){
			makepath($path);
		}
		$detail = explode(',',$upfile_str);

		foreach($detail AS $pic){
			if($pic==''){
				continue;
			}
			$name = substr(md5(WEB_ID),-3).'_'.$lfjuid.'_'.rands(4).'.jpg';
			$strcode = file_get_contents($wx_api_url.$pic);
			write_file("$path/$name",$strcode);		
			gdpic("$path/$name","$path/$name".'.gif',200,200);
			$picurl="homepage/pic/$uid/$name";

			$db->query("INSERT INTO `{$_pre}pic` (`psid` , `uid` , `username` ,  `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('$psid', '$uid', '$lfjid', '$name', '$picurl', '0', '1', '$timestamp', '0', '0')");
		}
	}
	$atn="piclist";
	$listpics=list_thisSortPic();
	echo $listpics;
	exit;
}

if($job=='picupload'){
	$url=filtrate($url);
	if($url){
		$picname=basename($url);
		$picurl="homepage/pic/".ceil($uid/1000)."/$picname";
		$Newpicpath=ROOT_PATH."$webdb[updir]/$picurl";
		copy(ROOT_PATH."$webdb[updir]/{$url}",$Newpicpath);
		gdpic($Newpicpath,$Newpicpath.'.gif',200,200);
		$db->query("INSERT INTO `{$_pre}pic` (`psid` , `uid` , `username` ,  `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('$psid', '$uid', '$lfjid', '$picname', '$picurl', '0', '1', '$timestamp', '0', '0')");
	}
	del_file(ROOT_PATH."$webdb[updir]/homepage/pic0/$lfjuid");

	$atn="piclist";
	$listpics=list_thisSortPic();
	echo $listpics;
	exit;	
}

if($job=='picsort'){	
	$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY psid DESC LIMIT 0,$webdb[company_picsort_Max];");
	while($rs=$db->fetch_array($query)){
		$rs[faceurl]=tempdir($rs[faceurl]);
		$listdb[]=$rs;
	}
}

if($atn=='piclist'){
	$picsortdb=$db->get_one("SELECT * FROM {$_pre}picsort WHERE uid='$uid' AND psid='$psid'");
	$picsortdb[faceurl]=$picsortdb[faceurl]?tempdir($picsortdb[faceurl]):"$Murl/images/default/userpicsortdefault.gif";
	$listpics=list_thisSortPic();
}

require(dirname(__FILE__)."/waphead.php");
require(dirname(__FILE__)."/template/waphomepic.htm");
require(dirname(__FILE__)."/wapfoot.php");

function list_thisSortPic(){
	global $db,$_pre,$page,$uid,$psid,$atn;
	$rows=6;
	$page||$page=1;
	$min=($page-1)*$rows;
	$query=$db->query("SELECT * FROM {$_pre}pic WHERE uid='$uid' AND psid='$psid' ORDER BY pid DESC LIMIT $min,$rows;");
	$listpics="";
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		if(is_file(ROOT_PATH."$webdb[updir]/{$rs[url]}.gif")){
			$rs[url1]="{$rs[url]}.gif";
		}else{
			$rs[url1]=$rs[url];
		}
		$rs[url]=tempdir($rs[url]);
		$rs[url1]=tempdir($rs[url1]);
		$listpics.="<div class='lists lists$rs[pid]'><dl><dt><a href='$rs[url]'><img src='$rs[url1]' onerror=\"this.src='$Murl/images/default/userpicdefault.gif';\" /></a></dt><dd><div><input type='text' value='$rs[title]' onBlur=\"editTitle(\$(this).val(),$rs[pid])\"/></div><div><a href='javascript:;setPicsortFac($rs[pid])'>设为封面</a> <a href='javascript:;delThisPic($rs[pid])'>删除</a></div></dd></dl></div>\r\n";
	}
	$showpage=getpage("{$_pre}pic"," WHERE uid='$uid' AND psid='$psid'","?atn=$atn&uid=$uid&psid=$psid",$rows);
	if($showpage){
		$listpics.="<div class='ShowPage'>$showpage</div>";
	}
	return $listpics;
}
?>