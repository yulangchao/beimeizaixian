<?php
if(!$mystyledb[hidemenus]||($action=='setstyle')){
	$mystyledb[footlink]||$mystyledb[footlink]=$defaultstyle[footlink];
	$set_footlink=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setmenus.php?type=1&stylename=style2&uid=$uid&tag=footlink'>�����������</a>":"";
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
	$set_copyright=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setonlyone.php?type=1&stylename=style2&uid=$uid&tag=copyright'>�����������</a>":"";
	$footcopyright="<div class='copyright'>\r\n";
	$footcopyright.=$mystyledb[copyright];
	$footcopyright.=$set_copyright;
	$footcopyright.="\r\n</div>\r\n";
}
if(!$mystyledb[hidecontact]||($action=='setstyle')){
	$mystyledb[contact]||$mystyledb[contact]=$defaultstyle[contact];
	$set_contact=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setimgs.php?type=1&stylename=style3&uid=$uid&tag=contact'>�����������</a>":"";
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
		<div class="head"><span>���̵���</span></div>
		<div class="cont">
			<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
			<div class="title">$companydb[title]</div>
			<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
			<ul class="other">
				<li class='time'>�Ǽ�ʱ�䣺<span>$companydb[posttime]</span></li>
				<li class='guest'>�ÿ����Թ���<span>{$guestbookNUM}</span> ��</li>
				<li class='hits'>ҳ��������<span>{$companydb[hits]}</span> ��</li>
			</ul>
			<dl class='wxcode'>
				<dt>ɨһɨ</dt>
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
//���¶����������
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