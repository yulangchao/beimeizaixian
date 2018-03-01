<?php 
$listsort="<li><a href=\"javascript:;showHyPics('?job=piclist&uid=$uid','全部相片')\">全部相片</a></li>\r\n";
$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC");
while($rs=$db->fetch_array($query)){
	$listsort.="<li><a href=\"javascript:;showHyPics('?job=piclist&uid=$uid&psid=$rs[psid]','$rs[name]')\">$rs[name]</a></li>\r\n";
}

$rows=8;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid'";
if($psid){
	@extract($db->get_one("SELECT name AS sortname  FROM {$_pre}picsort  WHERE psid='$psid'" ));
	$where.=" AND psid='$psid'";
}
$sortname||$sortname='全部相片';
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}pic $where ORDER BY orderlist DESC LIMIT $min,$rows;");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyPics('?job=piclist&psid=$psid&uid=\\1&page=\\2','$sortname')",$showpage);
$showlists="";
while($rs=$db->fetch_array($query)){
	$rs[url]=$rs[url]?tempdir($rs[url]):"$Murl/images/default/userpicdefault.gif";
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$showlists.="<div class='piclist'><a href=\"javascript:;showThisPics('$rs[url]')\"><span><em><img src='$rs[url].gif' onerror=\"this.src='$Murl/images/default/userpicsortdefault.gif';\" alt='$rs[url]'/></em></span></a></div>";
}
$showlists.="<div class='ShowPage'>$showpage</div>";

if($job=='piclist'){
	die($showlists);
}

require(style_html("head"));

?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/hypic.css" />
<div class='MainContainer'>
	<div class='MinLefts'>
		<ul class='ShowType'>
			$listsort
		</ul>
	</div>
	<div class='MainRights'>
		<div class='head'><span class='tag'>全部相片</span></div>
		<div class="ListHyPics">
			$showlists
		</div>
	</div>	
</div>
<div class="backContBox" onclick="hideThisPics()"></div>
<div class="ShowFunctions">
	<div class="BigimgBoxs">
		<table>
		  <tr>
			<td><img id="chagneshowBigImg" src="http://www.qibosoft.com/images/default/ico_loading3.gif"/></td>
		  </tr>
		</table>
	</div>
	<dl class="changShowPic">
		<dt onclick="ShowBigImgsPrev()"><span>上一张</span></dt>
		<dd onclick="ShowBigImgsNext()"><span>下一张</span></dd>
	</dl>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyPics(url,name){
   $.get(url+"&"+Math.random(),function(d){
	    $('.MainRights .head .tag').html(name);
		$('.ListHyPics').html(d);
	});
}

var showBigimgUrl = new Array(),bigImgs=0,nowShownum=0,showMaxWhidth=$('.ShowFunctions').width(),showMaxHeight=$('.ShowFunctions').height();
function ShowBigImgsPrev(){
	nowShownum--;
	if(nowShownum<0){
		hideThisPics();
	}else{
		$('.ShowFunctions .BigimgBoxs').css({'opacity':0,'left':'-'+showMaxWhidth+'px'});
		$('#chagneshowBigImg').attr("src",showBigimgUrl[nowShownum]);
		$('.ShowFunctions .BigimgBoxs').stop().animate({'opacity':1},100,function(){
			$('.ShowFunctions .BigimgBoxs').stop().animate({'left':'0px'},500);
		});
	}
}
function ShowBigImgsNext(){
	nowShownum++;
	if(nowShownum>(bigImgs-1)){
		hideThisPics();
	}else{
		$('.ShowFunctions .BigimgBoxs').css({'opacity':0,'left':showMaxWhidth+'px'});
		$('#chagneshowBigImg').attr("src",showBigimgUrl[nowShownum]);
		$('.ShowFunctions .BigimgBoxs').stop().animate({'opacity':1},100,function(){
			$('.ShowFunctions .BigimgBoxs').stop().animate({'left':'0px'},500);
		});
	}
}
function showThisPics(picurl){
	$('#chagneshowBigImg').attr("src",picurl);
	var listimgs=0;
	$('.ListHyPics .piclist img').each(function(){
		var thispicurl=$(this).attr("alt");
		showBigimgUrl[listimgs]=thispicurl;
		if(picurl==thispicurl){
			nowShownum=listimgs;
		}
		listimgs++;		
	});
	bigImgs=listimgs;
	$('.backContBox').show();
	$('.ShowFunctions').show();
	$('.ShowFunctions table td').css({'height':showMaxHeight+'px'});
	$('.ShowFunctions table td img').css({'max-height':showMaxHeight+'px'});
	var changebutH=showMaxHeight/2-30;
	$('.ShowFunctions .changShowPic dt').css({'top':changebutH+'px'});
	$('.ShowFunctions .changShowPic dd').css({'top':changebutH+'px'});
	$('.ShowFunctions').css({'opacity':0.3});
	$('.backContBox').stop().animate({'opacity':0.7},200,function(){
		$('.ShowFunctions').stop().animate({'opacity':1},200);
	});
}
function hideThisPics(){	
	$('.ShowFunctions').stop().animate({'opacity':0},200,function(){
		$('.backContBox').stop().animate({'opacity':0},200,function(){
			$('.backContBox').hide();
			$('.ShowFunctions').hide();
		});
	});
};
function changeShow(num){
	$('.ShowType li').removeClass('ck');
	$('.ShowType li').eq(num).addClass('ck');
}
changeShow(0);
$('.ShowType li').click(function(){
	var thisnum=$(this).index();
	changeShow(thisnum);
});
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php 
require(style_html("foot"));
?>