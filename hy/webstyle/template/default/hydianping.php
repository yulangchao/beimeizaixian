<?php 
header('Content-Type: text/html; charset='.WEB_LANG);
unset($listdb,$listdianping);
$lfjuid||$lfjuid=0;
$thisurl=urlencode($WEBURL);

if($action=="post"){
	if(!$lfjid){	
		die("��¼����ܷ�������");
	}
	if(!$content){	
		die("���ݲ���Ϊ��");
	}
	$content=filtrate($content);
	//���˲���������
	$username=replace_bad_word($lfjid);
	$content=replace_bad_word($content);
	$rss=$db->get_one(" SELECT * FROM {$_pre}home WHERE uid='$uid' ");
	if(!$rss){
		die("ԭ���ݲ�����");
	}
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
	$yz=1;//Ĭ��ͨ�����
	$db->query("INSERT INTO `{$_pre}dianping` (`cuid`, `type`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`, `fen1`, `fen2`, `fen3`, `fen4`, `fen5`, `price`, `keywords`, `keywords2`, `fen6`) VALUES ('$uid','0','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz','$fen1','$fen2','$fen3','$fen4','$fen5','$c_price','$c_keywords','$c_keywords2','$fen6')");

	$db->query(" UPDATE {$_pre}company SET dianping=dianping+1,`dianpingtime`='$timestamp' WHERE uid='$uid' ");

	set_user_log(6);	//�û�������־
}

if($job=='ActGood'){
	if(get_cookie("flowers_$cid")){
		die("err");
	}else{
		set_cookie("flowers_$cid",1,3600);
		$db->query("UPDATE `{$_pre}dianping` SET `flowers`=`flowers`+1 WHERE cid='$cid'");		
	}
	@extract($db->get_one("SELECT flowers FROM {$_pre}dianping  WHERE cid='$cid'" ));
	die($flowers);
}

$rows=3;
$page||$page=1;
$min=($page-1)*$rows;
$SQL=$webdb[showNoPassComment]?"":"AND A.yz=1";
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
	$listdb[]=$rs;
	$listdianping.="<div class='listdp'>\r\n";
	$listdianping.="<div class='img'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'><span><img onerror=\"this.src='$webdb[www_url]/images/default/noface.gif'\" src='$rs[icon]'/></span></a></div>\r\n";
	$listdianping.="<div class='info'>\r\n";
	$listdianping.="<div class='name'><a href='$webdb[www_url]/member/homepage.php?uid=$rs[uid]'  target='_blank'>$rs[username]</a></div>\r\n";
	$fen1ps=($rs[fen1]/5)*100;
	if($rs[fen5]>2) $dpword="<span class='dp dp3'>����</span>";
	elseif($rs[fen5]==2) $dpword="<span class='dp dp2'>����</span>";
	elseif($rs[fen5]==1) $dpword="<span class='dp dp1'>����</span>";
	else $dpword="";
	$listdianping.="<div class='fen'><span class='fen1'><em style='width:{$fen1ps}%'></em></span> $dpword </div>\r\n";
	$listdianping.="<p>$rs[content]</p>\r\n";
	$listdianping.="<div class='other'><span class='time'>$rs[posttime]</span><span class='good' onclick='ActGood($rs[cid])'>��(<em class='good$rs[cid]'>$rs[flowers]</em>)</span></div>\r\n";
	$listdianping.="</div>\r\n</div>\r\n";
}
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:getHyDianping('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);
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
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/base.css">
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/dianping.css">
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
			<div class="head"><span class="tag">�˿͵���<em id='dianpingnum'>(0)</em></span><a class='postdp' href='#PostDP'>��������</a></div>
			<div class="SelectDpType">
				<span onclick="getHyDianping('?showtype=moreshow&uid=$uid')" class='ck'>ȫ��</span>
				<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=3')">����</span>
				<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=2')">����</span>
				<span onclick="getHyDianping('?showtype=moreshow&uid=$uid&types=1')">����</span>
			</div>
			<div class="cont">
				<div class="ListHyDianping">
$listdianping
				</div>
				<div class='PostDianPing' id='PostDP'>
					<div class='h'><span class='tag'>��������</span></div>
					<div class='PostCont'>
						<div class='fen5'>
							�������ۣ�
							<span class='dp3'><input type="radio" name="fen5" value="3" checked>����</span>
							<span class='dp2'><input type="radio" name="fen5" value="2">����</span>
							<span class='dp1'><input type="radio" name="fen5" value="1">����</span>
						</div>
						<div class='fen1'>
							<input name='fen1' type='hidden' value='5' />
							<span>�� ����</span>
							<ul>
								<li></li>
								<li></li>
								<li></li>
								<li></li>
								<li></li>
							</ul>
						</div>
						<div class='c'>
							<textarea name='content' placeholder='��������������'/></textarea>
						</div>
						<div class='submits'><span onClick='post_dianpings();'><em>�ύ����</em></span></div>
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
showHyDianpingnum('?uid=$uid&job=showdpNum&types=$types');
function ActGood(cid){
	$.get("?job=ActGood&uid=$uid&cid="+cid+"&"+Math.random(),function(d){
		if(d=='err'){
			alert('�������ظ�����');
		}else{
			$('.good'+cid).html(d);
		}
	});
}
$('.SelectDpType span').click(function(){
	$('.SelectDpType span').removeClass('ck');
	$(this).addClass('ck');
});

var limitTime;
function limitComment1(){
	limitTime=limitTime-1;
	if(limitTime>0){
		$('.PostCont textarea').val('��ʣ'+limitTime+'��,��ſ����ٷ�������');
		$('.PostCont textarea').attr("disabled",true);
		$('.PostCont .submits span').css({'display':'none'}); 
		setTimeout("limitComment1()",1000);
	}else if(limitTime==0){
		$('.PostCont textarea').val('');
		$('.PostCont textarea').attr("disabled",false);
		$('.PostCont .submits span').css({'display':'block'}); 
	}
	
}
function post_dianpings(){
	if($lfjuid==0){
		var msay = confirm("����û�е�¼��Ҫ�ȵ�¼�������ۣ����Ƿ�Ҫ��¼��");
		if(msay==true){
			window.location.href="$webdb[www_url]/do/login.php?fromurl=$thisurl";
		}
	}else{
		var content=$('.PostCont textarea').val();
		var fen1=$('.PostCont .fen1 input').val();
		var fen5=$('.PostCont .fen5 input:checked').val();
		if(content==''){
			alert('�������ݲ���Ϊ�գ�');
		}else{
			limitTime=10;
			limitComment1();
			$.ajax({
			   type: "POST",
			   url: "?",
			   data: "action=post&showtype=moreshow&uid=$uid&content="+content+"&fen1="+fen1+"&fen5="+fen5,
			   success: function(msg){
				 $('.ListHyDianping').html(msg);
				 showHyDianpingnum('?uid=$uid&job=showdpNum');
			   }
			});
		}
	}
}
function changefen5(num){
	$('.PostCont .fen5 span').removeClass('ck');
	$('.PostCont .fen5 span').eq(num).addClass('ck');
	$('.PostCont .fen5 input').attr('checked',false);
	$('.PostCont .fen5 input').eq(num).attr('checked',true);
}
changefen5(0);
$('.PostCont .fen5 span').click(function(){
	var thisnum=$(this).index();
	changefen5(thisnum);
});
function changefen1(num){
	var fennum=num+1;
	$('.PostCont .fen1 input').val(fennum);
	$('.PostCont .fen1 ul li').each(function(){
		if($(this).index()<=num){
			$(this).addClass('ck');
		}else{
			$(this).removeClass('ck');
		}
	});
}
changefen1(4);
$('.PostCont .fen1 ul li').hover(function(){
	var thisnum=$(this).index();
	changefen1(thisnum);
});
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php require(style_html("foot"));  ?>