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
	$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//��HTML�������
	$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
	$showlists.="<div class='list'>";
	$showlists.="<div class='time'>$rs[posttime]</div>";
	$showlists.="<div class='t'><a href=\"javascript:;showHyNews('?showtype=newsview&id=$rs[id]&uid=$uid')\">$rs[title]</a></div>";
	$showlists.="<div class='c'>{$rs[content]} <a href=\"javascript:;showHyNews('?showtype=newsview&id=$rs[id]&uid=$uid')\">[����]</a></div>";
	$showlists.="</div>";
}
$showpage=getpage("{$_pre}news",$where,"?uid=$uid",$rows);

$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

$showlists.="<div class='ShowPage'>$showpage</div>";

if($showtype=='newsview'||$id){
	$data=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$id'");
	//��ʵ��ַ��ԭ
	$data[content]=En_TruePath($data[content],0,1);
	$data[posttime] =date("Y-m-d",$data[posttime] );
	if($data[uid]!=$lfjuid && !$data[yz]){
		die('<div style="text-align:center;line-height:80px;color:red;">��Ϣ���������...</div>');
	}
	$showlists="<div class='abouttitle'>{$data[title]}</div>";
	$showlists.="<div class='aboutcontent'>{$data[content]}<div class='showmore'><a href=\"javascript:showHyNews('?showtype=moreshow&uid=$uid')\">�����������б�</a></div></div>";
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
		<div onclick="AddFavorite(window.location,document.title)" class='Favorite'><span>�ղر�����</span></div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>����վ����Ϣ</span></a></div>
		<ul class='tjInfo'>
			<li>�ÿ����Թ�:<span>{$guestbookNUM}</span>��</li>
			<li>ҳ������:<em>{$companydb[hits]}</em>��</li>
		</ul>
	</div>
	<div class='Hydianping'>
		<div class='head'><span class='tag'>�̼�����</span></div>
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