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
if(!$mystyledb[hidecontact]||($action=='setstyle')){
	$mystyledb[contact]||$mystyledb[contact]=$defaultstyle[contact];
	$set_contact=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style3&uid=$uid&tag=contact'>点击设置内容</a>":"";
	$contactimg=tempdir($mystyledb[contact][img]);
	$contactinfo="<ul class='contact'>\r\n";
	$contactinfo.="<ol><a href='{$mystyledb[contact][url]}'><img src='$contactimg'/></a></ol>";
	$contactinfo.="<li>{$mystyledb[contact][title]}</li>";
	$contactinfo.=$set_contact;
	$contactinfo.="\r\n</ul>\r\n";
}
?>
<!--
<?php
print <<<EOT
-->
<div class='NewLeftContainers' style='display:none;'>
	<div class="LeftBaseInfo">
		<div class="head"><span>店铺档案</span></div>
		<div class="cont">
			<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
			<div class="title">$companydb[title]</div>
			<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
			<ul class="other">
				<li class='time'>登记时间：<span>$companydb[posttime]</span></li>
				<li class='guest'>访客留言共：<span>{$guestbookNUM}</span> 条</li>
				<li class='hits'>页面点击量：<span>{$companydb[hits]}</span> 次</li>
			</ul>
			<dl class='wxcode'>
				<dt>扫一扫</dt>
				<dd><img src="$webdb[www_url]/do/2codeimg.php?url=$thiswxurl"/></dd>
			</dl>
		</div>
		$contactinfo
	</div>
</div>
<div class='FootContainer'>
	$footlinks
	$footcopyright
</div>
<script>
//重新定义左边内容
var new_left_html=$('.NewLeftContainers').html();
$('.LeftContainers').html(new_left_html);
$('.MainContainers .LeftContainers').css({'width':'260px'});
$('.MainContainers .RightContainers').css({'width':'920px'});
</script>
</body>
</html>
<!--
EOT;
?>
-->