<?php 

//seo
$titleDB[title]=$companydb[title].'-'.$webdb[webname];
$titleDB[keywords]=$companydb[title];
$titleDB[description]=$companydb[content];
$titleDB[description]=@preg_replace('/<([^<]*)>/is',"",$companydb[description]);	//把HTML代码过滤掉
$titleDB[description]=get_word($titleDB[description],100);

$styledb0=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='0' AND type='1' AND stylename='default'");
$defaultstyle = unserialize(stripslashes($styledb0[config]));

$styledb1=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='1' AND stylename='default'");
$mystyledb = unserialize(stripslashes($styledb1[config]));

require(ROOT_PATH."inc/head.php"); 

?>
<!--
<?php
print <<<EOT
-->
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/index.css">
<div class="MainBanner">
<!--
EOT;
$mystyledb[banner]||$mystyledb[banner]=$defaultstyle[banner];
$bannerpicurl=tempdir($mystyledb[banner][img]);
$set_banner=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=$stylename&uid=$uid&tag=banner'>点击设置内容</a>":"";
if(!$mystyledb[hidebanner]||($action=='setstyle')){
print <<<EOT
-->
	<div class="Banner"><a href="{$mystyledb[banner][url]}" title="{$mystyledb[banner][title]}"><img src="{$bannerpicurl}" alt="{$mystyledb[banner][title]}"></a> $set_banner</div>	
<!--
EOT;
}
if(!$mystyledb[hidemenus]||($action=='setstyle')){
print <<<EOT
-->
	<div class="Mainmenu">
<!--
EOT;
$mystyledb[menus]||$mystyledb[menus]=$defaultstyle[menus];
$set_menus=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=$stylename&uid=$uid&tag=menus'>点击设置内容</a>":"";
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
<!--
EOT;
}
print <<<EOT
-->
</div>
<!--
EOT;
?>
-->