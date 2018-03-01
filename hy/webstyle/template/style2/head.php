<?php 

$defaultstyle0[logo][img]="$WebStyleurl/images/style2/logo.png";
$defaultstyle0[logo][title]='店铺logo';
$defaultstyle0[logo][url]="#";

$defaultstyle0[menus]=$defaultmenu;

for($i=0;$i<3;$i++){
	$num=$i+1;
	$defaultstyle0[slide][$i][img]="$WebStyleurl/images/style2/$num.jpg";
	$defaultstyle0[slide][$i][url]="#";
	$defaultstyle0[slide][$i][title]=$num;
}
$defaultstyle0[slideHeight]=280;

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

$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style2'");
if(!$checkstyle){
	$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('0','1','style2','$defaultconfig0')");
}elseif($defaultconfig0!=$checkstyle[config]){
	$db->query("UPDATE `{$_pre}style` SET config='$defaultconfig0' WHERE uid='0' AND type='1' AND stylename='style2'");
}

$quit_setstyle=($action=='setstyle')?"<div class='quit_setstyle'><a href='$WebStyleurl/index.php?uid=$uid'>退出风格设置</a></div>":"";

//seo
$titleDB[title]=$companydb[title].'-'.$webdb[webname];
$titleDB[keywords]=$companydb[title];
$titleDB[description]=$companydb[content];
$titleDB[description]=@preg_replace('/<([^<]*)>/is',"",$companydb[description]);	//把HTML代码过滤掉
$titleDB[description]=get_word($titleDB[description],100);

$styledb0=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='style2'");
$defaultstyle = unserialize(stripslashes($styledb0[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='1' AND stylename='style2'");
$mystyledb = unserialize(stripslashes($styledb1[config]));

$thisurl = urlencode($WEBURL);

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
	$listSlides.="<script type='text/javascript' src='$WebStyleurl/images/style2/slide.js'></script>";
}
?>
<!--
<?php
print <<<EOT
-->
<!doctype html>
<html lang="zh_CN">
<head>
<meta charset="gb2312" />
<meta http-equiv="X-UA-Compatible" content="IE=8"><!-- 强制ie8,for 360 -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="black"  />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>$titleDB[title]</title>
<meta name="keywords" content="$titleDB[keywords]">
<meta name="description" content="$titleDB[description]">
<script type="text/javascript" src="$WebStyleurl/images/jquery-v1.7.2.js"></script>
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/base.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style2/style.css" />
</head>
<body>
<div class="TopContent">
	<div class="TopContentBox">
		<div class='UserAct'>
<!--
EOT;
if($lfjid){print <<<EOT
-->
			<div class='info'>
				欢迎你回来:<span>$lfjid</span> 
				<a href="$webdb[_www_url]/member/homepage.php?uid=$lfjuid" target="_blank">个人信息</a> 
				<a href="$webdb[_www_url]/member/" target="_blank">会员中心</a>
				<a href="$webdb[_www_url]/member/index.php?main=userinfo.php?job=edit" target="_blank">修改资料</a> 
				<a href="$webdb[www_url]/do/login.php?action=quit" class='quit'>安全退出</a>
			</div>
<!--
EOT;
}else{print <<<EOT
-->
			<form method="post" action="$webdb[www_url]/do/login.php">
			<ul>
				<li>帐号:</li>
				<li><input type="text" name="username" placeholder="输入帐号"/></li>
				<li>密码:</li>
				<li><input type="password" name="password" placeholder="输入密码"/></li>
				<li><input type="submit" name="Submit" value="登录"/></li>
				<li><a href="$webdb[www_url]/do/reg.php">注册</a> | <a href="$webdb[www_url]/do/login.php">登录</a></li>
				<input type="hidden" name="step" value="2">
				<input type="hidden" name="cookietime" value="86400" >
			</ul>
			</form>
<!--
EOT;
}print <<<EOT
-->
		</div>
		<div class="ActBox">
			<a href="$webdb[www_url]/index.php">网站首页</a>
			<a href="javascript:void(0);" onclick="AddFavorite(window.location,document.title)">收藏本站</a>
<script type="text/javascript">
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
</script>
		</div>
	</div>
</div>
<div class='Logo_Menu'>
<!--
EOT;
$mystyledb[logo]||$mystyledb[logo]=$defaultstyle[logo];
$logoimg=tempdir($mystyledb[logo][img]);
$set_logo=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style2&uid=$uid&tag=logo'>点击设置内容</a>":"";
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
if(!$mystyledb[hidemenus]||($action=='setstyle')){
print <<<EOT
-->
	<div class="Menu">
<!--
EOT;
$mystyledb[menus]||$mystyledb[menus]=$defaultstyle[menus];
$set_menus=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=style2&uid=$uid&tag=menus'>点击设置内容</a>":"";
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
print <<<EOT
-->
</div>
$listSlides
<!--
EOT;
?>
-->