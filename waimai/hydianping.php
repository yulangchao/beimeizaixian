<?php
require(dirname(__FILE__)."/global.php");

if( !is_table("{$_pre}dianping") ){
	$db->query("CREATE TABLE IF NOT EXISTS `{$_pre}dianping` (
  `cid` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `cuid` int(7) NOT NULL DEFAULT '0',
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `id` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `fid` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL DEFAULT '',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `icon` tinyint(3) NOT NULL DEFAULT '0',
  `yz` tinyint(1) NOT NULL DEFAULT '0',
  `fen1` smallint(4) NOT NULL DEFAULT '0',
  `fen2` smallint(4) NOT NULL DEFAULT '0',
  `fen3` smallint(4) NOT NULL DEFAULT '0',
  `fen4` smallint(4) NOT NULL DEFAULT '0',
  `fen5` smallint(4) NOT NULL DEFAULT '0',
  `flowers` smallint(4) NOT NULL DEFAULT '0',
  `egg` smallint(4) NOT NULL DEFAULT '0',
  `price` mediumint(5) NOT NULL DEFAULT '0',
  `keywords` varchar(100) NOT NULL DEFAULT '',
  `keywords2` varchar(100) NOT NULL DEFAULT '',
  `fen6` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=$dbcharset AUTO_INCREMENT=1 ;");
}

$showtype=intval($showtype); //$showtype=2时是针对移动版的
$listtype=intval($listtype); //显示类型

header('Content-Type: text/html; charset='.WEB_LANG);

if($act=='flowers'){
	$cid=intval($cid);
	if(get_cookie("flowers_$cid")){
		die("no");
	}else{
		set_cookie("flowers_$cid",1,3600);
	}
	$db->query(" UPDATE {$_pre}dianping SET flowers=flowers+1 WHERE cid='$cid' ");
	@extract($db->get_one("SELECT flowers FROM {$_pre}dianping WHERE cid='$cid'"));
	die($flowers);
}

if($job=='adddianping'){
	if(!$lfjid){
		die("游客不能评论！");
	}
	/*验证码处理*/
	if(!$web_admin&&!check_imgnum($yzimg)&&$showtype!=2){//移动版不需要验证码	
		die("验证码不符合,点评失败");
	}
	if(!$content){	
		die("内容不能为空");
	}

	/*是否允许评论判断处理*/
	$allow=1;
	/*是否允许评论自动通过审核判断处理*/
	$yz=1;
	$username=filtrate($lfjid);
	$content=filtrate($content);
	$content=str_replace("@@br@@","<br>",$content);
	//过滤不健康的字
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);	
	$rss=$db->get_one(" SELECT * FROM {$_pre}company WHERE id='$id' ");
	if(!$rss){
		die("原数据不存在 SELECT * FROM {$_pre}company WHERE id='$id' ");
	}
	/*如果系统做了限制,那么有的评论将不给提交成功,但没做提示评论失败*/
	if($allow){
		if(is_utf8($content)||is_utf8($username)){
			$content=utf82gbk($content);
			$username=utf82gbk($username);
		}
		if(WEB_LANG=='utf-8'){
			$content=gbk2utf8($content);
			$username=gbk2utf8($username);
		}elseif(WEB_LANG=='big5'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","BIG5",$content,ROOT_PATH."./inc/gbkcode/");
			$content = $cnvert->ConvertIT();
			$cnvert = new Chinese("GB2312","BIG5",$username,ROOT_PATH."./inc/gbkcode/");
			$username = $cnvert->ConvertIT();
		}

		$db->query("INSERT INTO `{$_pre}dianping` (`cuid`, `type`, `id`,`fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `fen1`, `fen2`, `fen3`, `fen4`, `fen5`, `price`, `keywords`, `keywords2`, `fen6`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz','$fen1','$fen2','$fen3','$fen4','$fen5','$c_price','$c_keywords','$c_keywords2','$fen6')");

		$db->query(" UPDATE {$_pre}company SET comments=comments+1 WHERE id='$id' ");
	}
}

$rows=5;
$page||$page=1;
$min=($page-1)*$rows;
$listtype||$listtype=0;
if($listtype==1){
	$SQL="AND A.fen1>3";
}elseif($listtype==2){
	$SQL="AND A.fen1=3";
}elseif($listtype==3){
	$SQL="AND A.fen1<3 AND A.fen1>0";
}
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.icon FROM `{$_pre}dianping` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.id='$id' $SQL ORDER BY A.cid DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$dianpings=array(0=>'没有评论',1=>'很不满',2=>'不满',3=>'一般',4=>'满意',5=>'很满意');
$dianpingbg=array(0=>"style='background:#000;'",1=>"style='background:#999;'",2=>"style='background:#0FF;'",3=>"style='background:#F90;'",4=>"style='background:#F60;'",5=>"style='background:#F00;'");
$listshow="";
while( $rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$rs[icon]=$rs[icon]?tempdir($rs[icon]):"$webdb[www_url]/images/default/noface.gif";
	$rs[full_content]=$rs[content];
	$rs[content]=get_word($rs[content],$leng);
	if($rs[type]){
		$rs[content]="<img style='margin-top:3px;' src=$webdb[www_url]/images/default/good_ico.gif> ".$rs[content];
	}
	$rs[content]=str_replace("\n","<br>",$rs[content]);
	$rs[xingxing1]=ShowXingXing($rs[fen1]);
	//$listdb[]=$rs;
	if($showtype==2){
		$listshow.="<dl>
						<dt>
							<div class='img'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'><img src='$rs[icon]' onError=\"this.src='$webdb[www_url]/images/default/noface.gif'\"/></a></div>
						</dt>
						<dd>
							<span class='bg'></span>
							<div class='info'>
								<div class='userName'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'>$rs[username]</a></div>
								<div class='overallMerit'>
									$rs[xingxing1]
									<span class='total'>总体评价：<span class='red'>{$dianpings[$rs[fen1]]}</span></span>
								</div>
								<div class='commentContent'>$rs[content]</div>
								<div class='timeSupport'>
									<span class='time'>发布时间：$rs[posttime]</span>
									<span class='support' onClick='post_flowers($rs[cid]);' id='flowers$rs[cid]'><span>赞（<em>$rs[flowers]</em>）</span></span>
								</div>
							</div>
						</dd>
					</dl>";
	}else{
		$listshow.="<div class='comment_list'>
							<div class='img'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'><img src='$rs[icon]' onError=\"this.src='$webdb[www_url]/images/default/noface.gif'\"/></a></div>
							<div class='info'>
								<div class='title'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'>$rs[username]</a></div>
								<div class='comment_time'>
									$rs[xingxing1]
									<span class='type' {$dianpingbg[$rs[fen1]]}>{$dianpings[$rs[fen1]]}</span>
									<span class='state'>40分钟送达</span>
									<span class='time'>$rs[posttime]</span>
								</div>
								<div class='content'>$rs[content]</div>
							</div>
						</div>";
	}
}
$showpage=getpage("","","?fid=$fid&id=$id",$rows,$totalNum);
$showpage=preg_replace("/\?fid=([\d]+)&id=([\d]+)&page=([\d]+)/is","javascript:get_dianping('hydianping.php?fid=\\1&id=\\2&page=\\3&listtype=$listtype&showtype=$showtype')",$showpage);
if($showpage){
	$listshow.="<div class='showpage'>$showpage</div>";
}
if($listshow==''){
	$listshow.="<div style='text-align:center;padding:50px 20px;color:red;font-size:16px;'>当前商家暂无用户评价</div>";
}
die($listshow);
?>