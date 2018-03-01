<?php 

unset($listdb);
$rows=6;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz=1 ";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}news_content $where ORDER BY posttime DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showlists='';
while($rs=$db->fetch_array($query)){
	$rs[posttime]="<ul><ol>".date("d",$rs[posttime])."</ol><li>".date("Y m",$rs[posttime])."</li></ul>";
	$moredb=$db->get_one("SELECT * FROM {$pre}news_content_1  WHERE id='$rs[id]'" );
	$rs[content]=@preg_replace('/<([^>]*)>/is',"",$moredb[content]);	//把HTML代码过滤
	$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
	
	$showlists.="<div class='list'>";
	$showlists.="<div class='time'>$rs[posttime]</div>";
	$showlists.="<div class='t'><a href='/news/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></div>";
	$showlists.="<div class='c'>{$rs[content]} <a href='/news/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>[更多]</a></div>";
	$showlists.="</div>";
}
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

$showlists.="<div class='ShowPage'>$showpage</div>\r\n";

if($showtype=='moreshow'){
	die($showlists);
}

require(style_html("head"));

?>
<!--
<?php
print <<<EOT
-->
<link rel="stylesheet" type="text/css" href="$WebStyleurl/images/default/base.css">
<div class="MainContainers">
	<div class="LeftContainers">
		<div class="companyBase">
			<div class="head">店铺档案</div>
			<div class="cont">
				<div class="icon"><span><em><img src="$companydb[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg';"/></em></span></div>
				<div class="title">$companydb[title]</div>
				<div class="renzhengicon"><img src="$webdb[www_url]/images/default/renzheng/{$companydb[renzheng]}.png"/></div>
				<dl class="other">
					<dt>通行证：$companydb[username]</dt>
					<dd>登记时间：$companydb[posttime]</dd>
				</dl>
			</div>
		</div>
		<div class="AddFavorite">
			<span onclick="AddFavorite(window.location,document.title)"><em>收藏本店</em></span>
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
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}
//-->
</SCRIPT>
		</div>
		<div class="sendmsg"><a href='$webdb[_www_url]/member/?main=pm.php?job=send&username=$companydb[username]' target="_blank"><span>发送站内信息</span></a></div>
		<div class="tjInfo">
			<div class="head">统计信息</div>
			<ul>
				<li>访客留言共:<span>{$guestbookNUM}</span>条</li>
				<li>页面点击量:<span>{$companydb[hits]}</span>次</li>
			</ul>
		</div>
	</div>
	<div class="RightContainers">
		<div class="RightMainBox">
			<div class="head"><span class="tag">商家资讯<em>&nbsp;</em></span></div>
			<div class="cont">
				<div class="ListNews">
$showlists
				</div>				
			</div>
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
<?php require(style_html("foot")); ?>