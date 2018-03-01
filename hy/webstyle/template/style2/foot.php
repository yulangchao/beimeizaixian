<?php
if(!$mystyledb[hidemenus]||($action=='setstyle')){
	$mystyledb[footlink]||$mystyledb[footlink]=$defaultstyle[footlink];
	$set_footlink=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=style2&uid=$uid&tag=footlink'>点击设置内容</a>":"";
	$footlinks="<ul class='footlink'>\r\n";
	foreach($mystyledb[footlink] AS $key=>$rs){
		$rs[url]=(strstr($rs[url],"?"))?$rs[url]:$rs[url]."?uid=$uid";
		$footlinks.="<li><a href='$rs[url]'>$rs[title]</a></li>\r\n";
	}
	$footlinks.=$set_footlink;
	$footlinks.="\r\n</ul>\r\n";
}
if(!$mystyledb[hidecopyright]||($action=='setstyle')){
	$mystyledb[copyright]||$mystyledb[copyright]=$defaultstyle[copyright];
	$set_copyright=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setonlyone.php?type=1&stylename=style2&uid=$uid&tag=copyright'>点击设置内容</a>":"";
	$footcopyright="<div class='copyright'>\r\n";
	$footcopyright.=$mystyledb[copyright];
	$footcopyright.=$set_copyright;
	$footcopyright.="\r\n</div>\r\n";
}
?>
<!--
<?php
print <<<EOT
-->
<div class='FootContainer'>
	$footlinks
	$footcopyright
</div>
</body>
</html>
<!--
EOT;
?>
-->