<?php 

unset($listdb);

$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC");

$listhypics='';
while($rs=$db->fetch_array($query)){
	$rs[faceurl]=$rs[faceurl]?tempdir($rs[faceurl]):"$Murl/images/default/userpicsortdefault";
	$listhypics.="<dl class='picsort'>
				<dt><a href=\"javascript:;showHyPics('?job=piclist&uid=$uid&psid=$rs[psid]','$rs[name]')\"><span><img src='$rs[faceurl].gif' onerror=\"this.src='$Murl/images/default/userpicsortdefault.gif';\"/></span></a></dt>
				<dd><a href=\"javascript:;showHyPics('?job=piclist&uid=$uid&psid=$rs[psid]','$rs[name]')\">$rs[name]</a></dd>
			</dl>";
}

if($psid){
	@extract($db->get_one("SELECT name AS sortname  FROM {$_pre}picsort  WHERE psid='$psid'" ));
	$listhypics='';
	$rows=9;
	$page||$page=1;
	$min=($page-1)*$rows;
	$query=$db->query("SELECT * FROM {$_pre}pic WHERE uid='$uid' AND psid='$psid' ORDER BY orderlist DESC LIMIT $min,$rows;");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[url]=$rs[url]?tempdir($rs[url]):"$Murl/images/default/userpicdefault.gif";
		$listhypics.="<div class='piclist'><a href=\"javascript:;showThisPics('$rs[url]')\"><span><em><img src='$rs[url].gif' onerror=\"this.src='$Murl/images/default/userpicsortdefault.gif';\" alt='$rs[url]'/></em></span></a></div>";
	}	
	$showpage=getpage("{$_pre}pic","WHERE uid='$uid' AND psid='$psid'","?uid=$uid&psid=$psid",$rows);
	$showpage=preg_replace("/\?uid=([\d]+)&psid=([\d]+)&page=([\d]+)/is","javascript:showHyPics('?job=piclist&uid=\\1&psid=\\2&page=\\3','$sortname')",$showpage);
	$listhypics.="<div class='ShowPage'>$showpage</div>";
	$listhypics.="<div class='returnpicsort'><a href=\"javascript:;showHyPics('?job=showpicsort&uid=$uid','�̼����<em>PhotoAlbum</em>')\">�������</a></div>";
}

if($job=='showpicsort'||$job=='piclist'){
	echo $listhypics;
	exit;
}

require(style_html("head"));

?>
<!--
<?php
print <<<EOT
-->
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/base.css">
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/hypic.css">
<div class="MainContainers">
	<div class="LeftContainers">
		<div class="companyBase">
			<div class="head">���̵���</div>
			<div class="cont">
				<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
				<div class="title">$companydb[title]</div>
				<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
				<dl class="other">
					<dt>ͨ��֤��$companydb[username]</dt>
					<dd>�Ǽ�ʱ�䣺$companydb[posttime]</dd>
				</dl>
			</div>
		</div>
		<div class="AddFavorite">
			<span onclick="AddFavorite(window.location,document.title)"><em>�ղر���</em></span>
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
            alert("�����ղ�ʧ�ܣ���ʹ��Ctrl+D�������");
        }
    }
}
//-->
</SCRIPT>
		</div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>����վ����Ϣ</span></a></div>
		<div class="tjInfo">
			<div class="head">ͳ����Ϣ</div>
			<ul>
				<li>�ÿ����Թ�:<span>{$guestbookNUM}</span>��</li>
				<li>ҳ������:<span>{$companydb[hits]}</span>��</li>
			</ul>
		</div>
	</div>
	<div class="RightContainers">
		<div class="RightMainBox">
			<div class="head"><span class="tag">�̼����<em>PhotoAlbum</em></span></div>
			<div class="cont">
				<div class="ListHyPics">
$listhypics
				</div>				
			</div>
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
		<dt onclick="ShowBigImgsPrev()"><span>��һ��</span></dt>
		<dd onclick="ShowBigImgsNext()"><span>��һ��</span></dd>
	</dl>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyPics(url,name){
   $.get(url+"&"+Math.random(),function(d){
	    $('.RightMainBox .head .tag').html(name);
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

//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php require(style_html("foot")); ?>