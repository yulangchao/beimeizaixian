<?php 
unset($listdb);
$rows=5;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz=1 ";
$query=$db->query("SELECT * FROM {$_pre}news $where ORDER BY posttime DESC LIMIT $min,$rows");

$showlists="";
while($rs=$db->fetch_array($query)){
	$rs[posttime]="<ul><ol>".date("d",$rs[posttime])."</ol><li>".date("Y m",$rs[posttime])."</li></ul>";
	$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤
	$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
	$showlists.="<div class='list'>";
	$showlists.="<div class='time'>$rs[posttime]</div>";
	$showlists.="<div class='t'><a href=\"javascript:;showHyNews('?showtype=newsview&id=$rs[id]&uid=$uid')\">$rs[title]</a></div>";
	$showlists.="<div class='c'>{$rs[content]} <a href=\"javascript:;showHyNews('?showtype=newsview&id=$rs[id]&uid=$uid')\">[更多]</a></div>";
	$showlists.="</div>";
}
$showpage=getpage("{$_pre}news",$where,"?uid=$uid",$rows);

$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

$showlists.="<div class='ShowPage'>$showpage</div>";

if($showtype=='newsview'||$id){
	$data=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$id'");
	//真实地址还原
	$data[content]=En_TruePath($data[content],0,1);
	$data[posttime] =date("Y-m-d",$data[posttime] );
	if($data[uid]!=$lfjuid && !$data[yz]){
		die('<div style="text-align:center;line-height:80px;color:red;">信息正在审核中...</div>');
	}
	$showlists="<div class='abouttitle'>{$data[title]}</div>";
	$showlists.="<div class='aboutcontent'>{$data[content]}<div class='showmore'><a href=\"javascript:showHyNews('?showtype=moreshow&uid=$uid')\">【返回新闻列表】</a></div></div>";
}
if($showtype=='moreshow'||$showtype=='newsview'){
	die($showlists);
}
require(style_html("head"));
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/news.css" />
<div class='MainContainer'>
	<div class='hyOtherInfo'>
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>收藏本商铺</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<ul class='tjInfo'>
			<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
			<li>页面点击量:<em>{$companydb[hits]}</em>次</li>
		</ul>
	</div>
	<div class='Hydianping'>
		<div class='head'><span class='tag'>商家新闻</span></div>
		<div class="ListNews">
$showlists
		</div>	
	</div>	
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyNews(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListNews').html(d);
	});	
}
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php 
require(style_html("foot"));
?>