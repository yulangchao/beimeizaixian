<?php 
//seo
$titleDB[title]=$companydb[title].'-'.$webdb[webname];
$titleDB[keywords]=$companydb[title];
$titleDB[description]=$companydb[content];
$titleDB[description]=@preg_replace('/<([^<]*)>/is',"",$companydb[description]);	//��HTML������˵�
$titleDB[description]=get_word($titleDB[description],100);

$styledb0=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style1'");
$defaultstyle = unserialize(stripslashes($styledb0[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='1' AND stylename='style1'");
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
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/style.css" />
</head>
<body>
<div class="TopContent">
	<div class="TopContentBox">
<!--
EOT;
$mystyledb[logo]||$mystyledb[logo]=$defaultstyle[logo];
$logoimg=tempdir($mystyledb[logo][img]);
$set_logo=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style1&uid=$uid&tag=logo'>�����������</a>":"";
if(!$mystyledb[hidelogo]||($action=='setstyle')){
print <<<EOT
-->
		<div class="Logo"><a href="{$mystyledb[logo][url]}" title="{$mystyledb[logo][title]}"><span><img src="{$logoimg}" alt="{$mystyledb[logo][title]}"></span></a> $set_logo</div>	
<!--
EOT;
}
print <<<EOT
-->
		<div class="ActBox">
<!--
EOT;
$loginsword=$lfjid?"<a href='$webdb[www_url]/member/' target='_blank' class='member'>��Ա����</a> <a href='$webdb[www_url]/do/login.php?action=quit&fromurl=$thisurl' class='quit'>��ȫ�˳�</a>":"<a href='$webdb[www_url]/do/login.php?fromurl=$thisurl' class='login'>��¼</a> <a href='$webdb[www_url]/do/reg.php' class='reg'>ע��</a>";
print <<<EOT
-->
			$loginsword
			<a href="$webdb[www_url]/member/?main=$webdb[www_url]/shop/member/order.php?job=mylist" target="_blank" class='order'>�ҵĶ���</a>
			<a href="$webdb[www_url]/index.php">��վ��ҳ</a>
		</div>
	</div>
</div>
<div class="CompanyBaseInfoBox">
<div class="CompanyBaseInfo">
	<div class="BaseInfo">
<!--
EOT;
$memberinfo=$userDB->get_info($uid);
$company_yz=$companydb[renzheng]?"class='hy_yz'":"";
$idcard_yz=$memberinfo[idcard_yz]?"class='id_yz'":"";
$email_yz=$memberinfo[email_yz]?"class='mail_yz'":"";
$mob_yz=$memberinfo[mob_yz]?"class='mob_yz'":"";
$thiswxurl = urlencode("$webdb[www_url]/hy/waphomepage.php?uid=$uid");
print <<<EOT
-->
		<div class='title'>$companydb[title] <span $company_yz>��ҵ��֤</span> <span $idcard_yz>�����֤</span> <span $mob_yz>�ֻ���֤</span> <span $email_yz>������֤</span></div>
		<div class='address'>��ַ��{$companydb[qy_address]} <a href="contact.php?uid=$uid">[�鿴��ͼ]</a></div>
		<div class='time'>����ʱ�䣺{$companydb[qy_createtime]}</div>
		<div class='more'>������ҵ��{$companydb[my_trade]}</div>
		<div class='more'>��ҵ���ͣ�{$companydb[qy_cate]}</div>
		<div class='more'>��Ӫģʽ��{$companydb[qy_saletype]}</div>
		<div class='contact'>
			<span class='tel'>$companydb[qy_contact_tel]</span>
			<span class='mob'>$companydb[qy_contact_mobile]</span>
			<span class='qq'>$companydb[qq]</span>
		</div>
	</div>
	<div class="wxcode">
		<ul>
			<ol><img src="$webdb[www_url]/do/2codeimg.php?url=$thiswxurl"/></ol>
			<li>�����ֻ���</li>
		</ul>
		<div onclick="AddFavorite(window.location,document.title)" class='addhy'><span>�ղص���</span></div>
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
</div>
</div>
<!--
EOT;
if(!$mystyledb[hidemenus]||($action=='setstyle')){
print <<<EOT
-->
<div class="MainmenuBox">
	<div class="Mainmenu">
<!--
EOT;
$mystyledb[menus]||$mystyledb[menus]=$defaultstyle[menus];
$set_menus=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=style1&uid=$uid&tag=menus'>�����������</a>":"";
$listMenu="<ul>\r\n";
$thisfilename=basename($WEBURL);
foreach($mystyledb[menus] AS $key=>$rs){
	//$chooses=(strstr($rs[url],"index.php"))?"class='choose'":"";
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
</div>
<!--
EOT;
}
?>
-->