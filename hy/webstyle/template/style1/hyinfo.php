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
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>收藏本商铺</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<ul class='tjInfo'>
			<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
			<li>页面点击量:<em>{$companydb[hits]}</em>次</li>
		</ul>
	</div>
	<div class='Hydianping' style='float:right;'>
		<div class='head'><span class='tag'>商家新闻</span></div>
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