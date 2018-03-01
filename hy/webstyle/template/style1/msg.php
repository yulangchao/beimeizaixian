<?php 
header('Content-Type: text/html; charset='.WEB_LANG);
unset($listdb,$listdianping);
$lfjuid||$lfjuid=0;
$thisurl=urlencode($WEBURL);

if($action=="post"){
	if(!$lfjid){	
		die("登录后才能发表评论");
	}
	//检测多少时间内不能重复留言
	$ts=$db->get_one("SELECT * FROM {$_pre}guestbook WHERE cuid='$uid' AND uid='$lfjuid' ORDER BY id DESC LIMIT 1");

	if(!check_imgnum($yzimg)){
		die("验证码不符合");
	}

	if($ts[posttime]){
		if( $ts[posttime] + 30 > $timestamp ){
			die("1分钟内不能再次留言");
		}
	}

	if(!$content){
		die("内容不能为空");
	}
	if(strlen($content)>1000){
		die("内容不能超过500个字");
	}
	$content=filtrate($content);
	if(is_utf8($content)){
		$content=utf82gbk($content);
	}
	if(WEB_LANG=='utf-8'){
		$content=gbk2utf8($content);
	}elseif(WEB_LANG=='big5'){
		require_once(ROOT_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("GB2312","BIG5",$content,ROOT_PATH."./inc/gbkcode/");
		$content = $cnvert->ConvertIT();
	}
	$yz=1;
	$db->query("INSERT INTO `{$_pre}guestbook` (`cuid`,  `uid` , `username` , `ip` , `content` , `yz` , `posttime` , `list`) VALUES ('$uid','$lfjuid','$lfjid','$onlineip','$content','$yz','$timestamp','$timestamp')");
}

$rows=5;
$page||$page=1;
$min=($page-1)*$rows;
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.icon FROM `{$_pre}guestbook` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.cuid='$uid' AND A.yz=1 ORDER BY A.id DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while($rs=$db->fetch_array($query)){
	$rs[icon]=$rs[icon]?tempdir($rs[icon]):"$webdb[www_url]/images/default/noface.gif";
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listdb[]=$rs;
	$listdianping.="<div class='listdp'>\r\n";
	$listdianping.="<div class='img'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'><span><img onerror=\"this.src='$webdb[www_url]/images/default/noface.gif'\" src='$rs[icon]'/></span></a></div>\r\n";
	$listdianping.="<div class='info'>\r\n";
	$listdianping.="<div class='name'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'>$rs[username]</a></div>\r\n";
	$listdianping.="<p>$rs[content]</p>\r\n";
	$listdianping.="<div class='other'><span class='time'>$rs[posttime]</span></div>\r\n";
	$listdianping.="</div>\r\n</div>\r\n";
}
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:getHyDianping('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);
$listdianping.="<div class='ShowPage'>$showpage</div>\r\n";

if($job=='showdpNum'){
	die($totalNum);
}

if($showtype=='moreshow'){
	die($listdianping);
}

require(style_html("head"));

?>
<!--
<?php
print <<<EOT
-->
<style>body{background:#FFF;}</style>
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/base.css">
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/dianping.css">
<div class="MainContainers">
	<div class="LeftContainers">
		<div class="companyBase">
			<div class="head">店铺档案</div>
			<div class="cont">
				<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
				<div class="title">$companydb[title]</div>
				<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
				<dl class="other">
					<dt>通行证：$companydb[username]</dt>
					<dd>登记时间：$companydb[posttime]</dd>
				</dl>
			</div>
		</div>
		<div class="AddFavorite">
			<span onclick="AddFavorite(window.location,document.title)"><em>收藏本店</em></span>
<SCRIPT LANGUAGE="JavaScript">
<!--
function AddFavorite(sURL, sTitle){
    try{
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e){
        try{
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e){
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//-->
</SCRIPT>
		</div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<div class="tjInfo">
			<div class="head">统计信息</div>
			<ul>
				<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
				<li>页面点击量:<span>{$companydb[hits]}</span>次</li>
			</ul>
		</div>
	</div>
	<div class="RightContainers">
		<div class="RightMainBox">
			<div class="head"><span class="tag">访客留言<em id='dianpingnum'>(0)</em></span><a class='postdp' href='#PostDP'>发布留言</a></div>
			<div class="cont">
				<div class="ListHyDianping ListHyMsg">
$listdianping
				</div>
				<div class='PostDianPing' id='PostDP'>
					<div class='h'><span class='tag'>发布留言</span></div>
					<div class='PostCont'>
						<div class='yzcode'>
							<span>验证码：</span>
							<span><input type="text" name="yzimg" size="8"></span>
							<span>
								<img src="$webdb[www_url]/do/yzimg.php"/>
							</span>
						</div>
						<div class='c'>
							<textarea name='content' placeholder='请注意文明用语，留言内容不能超过500个字;'/></textarea>
						</div>
						<div class='submits'><span onClick='post_dianpings();'><em>提交留言</em></span></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function getHyDianping(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListHyDianping').html(d);
	});
	showHyDianpingnum(url+'&job=showdpNum');
}
function showHyDianpingnum(url){
	$.get(url+"&"+Math.random(),function(d){
		$('#dianpingnum').html('('+d+')');
	});
}
showHyDianpingnum('?uid=$uid&job=showdpNum');

var limitTime;
function limitComment1(){
	limitTime=limitTime-1;
	if(limitTime>0){
		$('.PostCont textarea').val('还剩'+limitTime+'秒,你才可以再发表评论');
		$('.PostCont textarea').attr("disabled",true);
		$('.PostCont .submits span').css({'display':'none'}); 
		setTimeout("limitComment1()",1000);
	}else if(limitTime==0){
		$('.PostCont textarea').val('');
		$('.PostCont textarea').attr("disabled",false);
		$('.PostCont .submits span').css({'display':'block'}); 
		changeyzcodeimg();
	}
	
}
function post_dianpings(){
	if($lfjuid==0){
		var msay = confirm("您还没有登录！要先登录才能留言！你是否要登录？");
		if(msay==true){
			window.location.href="$webdb[www_url]/do/login.php?fromurl=$thisurl";
		}
	}else{
		var content=$('.PostCont textarea').val();
		var yzimg=$('.PostCont .yzcode input').val();
		if(content==''){
			alert('评论内容不能为空！');
		}else{
			limitTime=10;
			limitComment1();
			$.ajax({
			   type: "POST",
			   url: "?",
			   data: "action=post&showtype=moreshow&uid=$uid&content="+content+"&yzimg="+yzimg,
			   success: function(msg){
				 $('.ListHyDianping').html(msg);
				 showHyDianpingnum('?uid=$uid&job=showdpNum');
			   }
			});
		}
	}
}
function changeyzcodeimg(){
	$('.yzcode img').attr("src","$webdb[www_url]/do/yzimg.php?"+Math.random());
	$('.yzcode input').val('');
}
$('.yzcode img').click(function(){
	changeyzcodeimg();
});
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php require(style_html("foot")); ?>