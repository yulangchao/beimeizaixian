<?php 

$defaultstyle0[logo][img]="$WebStyleurl/images/style1/logo.png";
$defaultstyle0[logo][title]='店铺logo';
$defaultstyle0[logo][url]="#";

$defaultstyle0[menus]=$defaultmenu;

for($i=0;$i<3;$i++){
	$num=$i+1;
	$defaultstyle0[slide][$i][img]="$WebStyleurl/images/style1/$num.jpg";
	$defaultstyle0[slide][$i][url]="#";
	$defaultstyle0[slide][$i][title]=$num;
}
$defaultstyle0[slideHeight]=420;

$defaultstyle0[footlink][0][url]="hr.php";
$defaultstyle0[footlink][0][title]='人才招聘';
$defaultstyle0[footlink][1][url]="contact.php";
$defaultstyle0[footlink][1][title]='联系我们';
$defaultstyle0[footlink][2][url]="msg.php";
$defaultstyle0[footlink][2][title]='给我留言';
$defaultstyle0[footlink][3][url]="tg.php";
$defaultstyle0[footlink][3][title]='商家活动';

$defaultstyle0[copyright]="All Right Reserved 微商联盟 版权所有 京ICP备000000号";

$defaultconfig0 = addslashes(serialize($defaultstyle0));

$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style1'");
if(!$checkstyle){
	$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('0','1','style1','$defaultconfig0')");
}elseif($defaultconfig0!=$checkstyle[config]){
	$db->query("UPDATE `{$_pre}style` SET config='$defaultconfig0' WHERE uid='0' AND type='1' AND stylename='style1'");
}

$quit_setstyle=($action=='setstyle')?"<div class='quit_setstyle'><a href='$WebStyleurl/index.php?uid=$uid'>退出风格设置</a></div>":"";

require(style_html("head"));

//幻灯片代码
if(!$mystyledb[hideslide]||($action=='setstyle')){	
	$mystyledb[slide]||$mystyledb[slide]=$defaultstyle[slide];
	$mystyledb[slideHeight]||$mystyledb[slideHeight]=$defaultstyle[slideHeight];	
	$listSlides="<div class='MainSlide' style='height:{$mystyledb[slideHeight]}px;'>\r\n";
	$listSlides.="<ul class='slideimgs'>\r\n";
	$listnums="";
	$i=0;
	foreach($mystyledb[slide] AS $key=>$rs){		
		$i++;
		$rs[img]=$rs[img]?tempdir($rs[img]):"$WebStyleurl/images/style1/$i.jpg";
		$listSlides.="<li><a href='$rs[url]' title='$rs[title]' style='background:url($rs[img]) center center no-repeat;' target='_blank'><span>$rs[title]</span></a></li>\r\n";
		$listnums.="<li>$i</li>\r\n";
	}	
	$listSlides.="</ul>\r\n";
	$listSlides.="<ul class='listnum'>$listnums</ul>\r\n";
	$set_slides=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setslide.php?type=1&stylename=style1&uid=$uid&tag=slide'>点击设置内容</a>":"";
	$listSlides.="<div class='prev'>上一张</div><div class='next'>下一张</div>";
	$listSlides.=$set_slides;
	$listSlides.="</div>\r\n";
	$listSlides.="<script type='text/javascript' src='$WebStyleurl/images/style1/slide.js'></script>";
}

$companydb[content] = En_TruePath($companydb[content],0,1);
$companydb[content]=@preg_replace('/<([^>]*)>/is',"",$companydb[content]);
$companydb[content]=get_word($companydb[content],600);

$listhynews='';
$query=$db->query("SELECT * FROM {$_pre}news WHERE uid='$uid' AND yz='1' ORDER BY posttime DESC LIMIT 5");
while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	$listhynews.="<div class='listnews'><a href='news.php?uid=$uid&id=$rs[id]'>$rs[title]</a> <span>$rs[posttime]</span></div>\r\n";
}

$listhypicsort='';
$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC LIMIT 5");
while($rs=$db->fetch_array($query)){
	$rs[faceurl]=$rs[faceurl]?tempdir($rs[faceurl]):"$Murl/images/default/userpicsortdefault";
	$listhypicsort.="<ul class='picsort'>\r\n<ol><a href='hypic.php?uid=$uid&psid=$rs[psid]'><span><img src='$rs[faceurl].gif' onerror=\"this.src='$Murl/images/default/userpicsortdefault.gif';\"/></span></a></ol>\r\n<li>$rs[name]</li>\r\n</ul>\r\n";
}

$rows=3;
$page||$page=1;
$min=($page-1)*$rows;
$SQL="AND A.yz=1";
if($types){
	if($types==3){
		$SQL.=" AND A.fen5>'2'";
	}else{
		$SQL.=" AND A.fen5='$types'";
	}
}
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.icon FROM `{$_pre}dianping` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.cuid='$uid' $SQL ORDER BY A.cid DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while($rs=$db->fetch_array($query)){
	$rs[icon]=$rs[icon]?tempdir($rs[icon]):"$webdb[www_url]/images/default/noface.gif";
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listdianping.="<div class='listdp'>\r\n";
	$listdianping.="<div class='img'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'><span><img onerror=\"this.src='$webdb[www_url]/images/default/noface.gif'\" src='$rs[icon]'/></span></a></div>\r\n";
	$listdianping.="<div class='info'>\r\n";
	$listdianping.="<div class='name'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'>$rs[username]</a></div>\r\n";
	$fen1ps=($rs[fen1]/5)*100;
	if($rs[fen5]>2) $dpword="<span class='dp dp3'>好评</span>";
	elseif($rs[fen5]==2) $dpword="<span class='dp dp2'>中评</span>";
	elseif($rs[fen5]==1) $dpword="<span class='dp dp1'>差评</span>";
	else $dpword="";
	$listdianping.="<div class='fen'><span class='fen1'><em style='width:{$fen1ps}%'></em></span> $dpword </div>\r\n";
	$listdianping.="<p>$rs[content]</p>\r\n";
	$listdianping.="<div class='other'><span class='time'>$rs[posttime]</span><span class='good' onclick='ActGood($rs[cid])'>赞(<em class='good$rs[cid]'>$rs[flowers]</em>)</span></div>\r\n";
	$listdianping.="</div>\r\n</div>\r\n";
}
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:getHyDianping('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);
$listdianping.="<div class='ShowPage'>$showpage</div>\r\n";

if($showtype=='moreshow'){
	ob_end_clean();
	die($listdianping);
}
 
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/index.css" />
$listSlides
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<div class='MainContainer'>
	<div class='aboutHy'>
		<div class='head'><span class='tag'>商家介绍</span></div>
		<div class='showabout'>
			$companydb[content]
			<a href='about.php?uid=$uid'>[详细]</a>
		</div>
	</div>
	<div class='HyNews'>
		<div class='head'><span class='tag'>商家资讯</span><a href='news.php?uid=$uid' class='more'>MORE</a></div>
		<div class='newscont'>
			$listhynews
		</div>
	</div>
</div>
<div class='MainContainer'>
	<div class='head'><span class='tag'>商家相册</span><a href='hypic.php?uid=$uid' class='more'>MORE</a></div>
	<div class='hyPicSorts'>
		$listhypicsort
	</div>
</div>
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/dianping.css">
<div class='MainContainer'>
	<div class='Hydianping'>
		<div class='head'><span class='tag'>顾客点评</span><a href='hydianping.php?uid=$uid' class='more'>MORE</a></div>
		<div class="SelectDpType">
			<span onclick="getHyDianping('?showtype=moreshow&uid=$uid')" class='ck'>全部</span>
			<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=3')">好评</span>
			<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=2')">中评</span>
			<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=1')">差评</span>
		</div>
		<div class="ListHyDianping">
			$listdianping
		</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ActGood(cid){
	$.get("dianping_ajx.php?job=ActGood&uid=$uid&cid="+cid+"&"+Math.random(),function(d){
		if(d=='err'){
			alert('不可以重复操作');
		}else{
			$('.good'+cid).html(d);
		}
	});
}
function getHyDianping(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListHyDianping').html(d);
	});
}
$('.SelectDpType span').click(function(){
	$('.SelectDpType span').removeClass('ck');
	$(this).addClass('ck');
});
//-->
</SCRIPT>
	</div>
	<div class='hyOtherInfo'>
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>收藏本商铺</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<ul class='tjInfo'>
			<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
			<li>页面点击量:<em>{$companydb[hits]}</em>次</li>
		</ul>
	</div>
</div>
$quit_setstyle
<!--
EOT;
?>
-->
<?php require(style_html("foot"));  ?>