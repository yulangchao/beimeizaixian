<?php 
require(style_html("head"));
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<style>
.companycontent{background:#FFF;padding:15px;min-height:500px;line-height:25px;font-size:16px;color:#666;word-break:break-all;word-wrap:break-word;}
</style>
<div class='MainContainer'>
	<div class='hyOtherInfo' style='float:left;'>
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>�ղر�����</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>����վ����Ϣ</span></a></div>
		<ul class='tjInfo'>
			<li>�ÿ����Թ�:<span>{$guestbookNUM}</span>��</li>
			<li>ҳ������:<em>{$companydb[hits]}</em>��</li>
		</ul>
	</div>
	<div class='Hydianping' style='float:right;'>
		<div class='head'><span class='tag'>�̼�����</span></div>
		<div class='companycontent'>
			$companydb[content]
		</div>
	</div>	
</div>
<!--
EOT;
?>
-->
<?php 
require(style_html("foot"));
?>