<?php 

$defaultstyle0[logo][img]="$WebStyleurl/images/style3/logo.png";
$defaultstyle0[logo][title]='����logo';
$defaultstyle0[logo][url]="#";

$defaultstyle0[topinfo][img]="$WebStyleurl/images/style3/tel.png";
$defaultstyle0[topinfo][word]="7X24Сʱ�ͷ��绰<br/><span>020-28998648</span>";

$defaultstyle0[menus]=$defaultmenu;

$defaultstyle0[banner][img]="$WebStyleurl/images/style3/banner.png";
$defaultstyle0[banner][title]='���ͼƬ';
$defaultstyle0[banner][url]="#";

$defaultstyle0[contact][img]="$WebStyleurl/images/style3/com.png";
$defaultstyle0[contact][title]='020-28998648';
$defaultstyle0[contact][url]="#";

$defaultstyle0[footlink][0][url]="hr.php";
$defaultstyle0[footlink][0][title]='�˲���Ƹ';
$defaultstyle0[footlink][1][url]="contact.php";
$defaultstyle0[footlink][1][title]='��ϵ����';
$defaultstyle0[footlink][2][url]="msg.php";
$defaultstyle0[footlink][2][title]='��������';
$defaultstyle0[footlink][3][url]="tg.php";
$defaultstyle0[footlink][3][title]='�̼һ';

$defaultstyle0[copyright]="All Right Reserved ΢������ ��Ȩ���� ��ICP��000000��";

$defaultconfig0 = addslashes(serialize($defaultstyle0));

$thiswxurl = urlencode("$webdb[www_url]/hy/waphomepage.php?uid=$uid");

$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style3'");
if(!$checkstyle){
	$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('0','1','style3','$defaultconfig0')");
}elseif($defaultconfig0!=$checkstyle[config]){
	$db->query("UPDATE `{$_pre}style` SET config='$defaultconfig0' WHERE uid='0' AND type='1' AND stylename='style3'");
}

$quit_setstyle=($action=='setstyle')?"<div class='quit_setstyle'><a href='$WebStyleurl/index.php?uid=$uid'>�˳��������</a></div>":"";

//seo
$titleDB[title]=$companydb[title].'-'.$webdb[webname];
$titleDB[keywords]=$companydb[title];
$titleDB[description]=$companydb[content];
$titleDB[description]=@preg_replace('/<([^<]*)>/is',"",$companydb[description]);	//��HTML������˵�
$titleDB[description]=get_word($titleDB[description],100);

$styledb0=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style3'");
$defaultstyle = unserialize(stripslashes($styledb0[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='1' AND stylename='style3'");
$mystyledb = unserialize(stripslashes($styledb1[config]));

$thisurl = urlencode($WEBURL);

?>
<!--
<?php
print <<<EOT
-->
<!doctype html>
<html lang="zh_CN">
<head>
<meta charset="gb2312" />
<meta http-equiv="X-UA-Compatible" content="IE=8"><!-- ǿ��ie8,for 360 -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="black"  />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>$titleDB[title]</title>
<meta name="keywords" content="$titleDB[keywords]">
<meta name="description" content="$titleDB[description]">
<script type="text/javascript" src="$WebStyleurl/images/jquery-v1.7.2.js"></script>
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/base.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style3/style.css" />
</head>
<body>
<div class="TopContent">
<!--
EOT;
$mystyledb[logo]||$mystyledb[logo]=$defaultstyle[logo];
$logoimg=tempdir($mystyledb[logo][img]);
$set_logo=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style3&uid=$uid&tag=logo'>�����������</a>":"";
if(!$mystyledb[hidelogo]||($action=='setstyle')){
print <<<EOT
-->
	<div class='Logo'>
		<a href="{$mystyledb[logo][url]}" title="{$mystyledb[logo][title]}"><span><img src="{$logoimg}" alt="{$mystyledb[logo][title]}"></span></a> 
		$set_logo
	</div>
<!--
EOT;
}
print <<<EOT
-->
	<div class='SearchCont'>
		<form  name="TopSearch" method="POST" action="news.php?uid=$uid" onSubmit="return checkInput()">
			<div class="keyword"><input type="text" name="keyword" placeholder="������Ҫ����������"/></div>
			<div class="submit"><input  type="submit" value="����"/></div>
			<dl class="Select">
				<dt>����</dt>
				<dd>
					<div onClick="change_search('����','news')">����</div>
					<div onClick="change_search('��Ʒ','shop')">��Ʒ</div>
				</dd>
			</dl>
		</form>
	</div>
<script>
function checkInput(){
	if($('.SearchCont .keyword input').val()==''){
		alert('�ؼ��ֲ���Ϊ��!');
		return false;
	}
}
function change_search(name,dir){
	$(".SearchCont form").attr("action", dir+".php?uid=$uid");
	$(".SearchCont .Select dt").html(name);
}
$(".SearchCont .Select").hover(
	function(){
		$(this).find('dd').show();
	},
	function(){
		$(this).find('dd').hide();
	}
);
</script>
<!--
EOT;
$mystyledb[topinfo]||$mystyledb[topinfo]=$defaultstyle[topinfo];
$topinfoimg=tempdir($mystyledb[topinfo][img]);
$set_topinfo=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgword.php?type=1&stylename=style3&uid=$uid&tag=topinfo'>�����������</a>":"";
if(!$mystyledb[hidetopinfo]||($action=='setstyle')){
print <<<EOT
-->
	<div class='topinfo'>
		<dl>
			<dt><span><img src="{$topinfoimg}"/></span></dt>
			<dd>{$mystyledb[topinfo][word]}</dd>
		</dl> 
		$set_topinfo
	</div>
<!--
EOT;
}
print <<<EOT
-->
</div>
<!--
EOT;
if(!$mystyledb[hidemenus]||($action=='setstyle')){
print <<<EOT
-->
<div class="MainMenu">
<!--
EOT;
$mystyledb[menus]||$mystyledb[menus]=$defaultstyle[menus];
$set_menus=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=style3&uid=$uid&tag=menus'>�����������</a>":"";
$listMenu="<ul>\r\n";
$thisfilename=basename($WEBURL);
foreach($mystyledb[menus] AS $key=>$rs){
	$rs[url]=(strstr($rs[url],"?"))?$rs[url]:$rs[url]."?uid=$uid";
	$chooses=($thisfilename==$rs[url])?"class='choose'":"";
	$listMenu.="<li $chooses><a href='$rs[url]'>$rs[title]</a></li>\r\n";
}
$listMenu.="</ul>\r\n";
print <<<EOT
-->	
	$listMenu
	$set_menus	
</div>
<!--
EOT;
}
$mystyledb[banner]||$mystyledb[banner]=$defaultstyle[banner];
if(!$mystyledb[hidebanner]||($action=='setstyle')){
$bannerimg=tempdir($mystyledb[banner][img]);
$set_banner=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style3&uid=$uid&tag=banner'>�����������</a>":"";
print <<<EOT
-->
<div class='MainBanner'>
	<a href="{$mystyledb[banner][url]}" title="{$mystyledb[banner][title]}"><span><img src="{$bannerimg}" alt="{$mystyledb[banner][title]}"></span></a> 
	$set_banner
</div>
<!--
EOT;
}
?>
-->