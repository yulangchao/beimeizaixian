<?php 

unset($listdb);
$rows=15;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz='1'";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}zhuangxiu_content $where ORDER BY posttime DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$listHr="<ul class='listHr listHrH'><li class='li1'>信息标题</li>\r\n<li class='li2'>所在栏目</li>\r\n<li class='li3' style='text-align:center;'>发布时间</li>\r\n</ul>\r\n";
while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listHr.="<ul class='listHr listHrC'><li class='li1'><a href='/zhuangxiu/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></li>\r\n<li class='li2'><a href='/zhuangxiu/list.php?fid=$rs[fid]' target='_blank'>$rs[fname]</a></li>\r\n<li class='li3'>$rs[posttime]</li>\r\n</ul>\r\n";
}

$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

$listHr.="<div class='ShowPage'>$showpage</div>\r\n";

if($showtype=='moreshow'){
	die($listHr);
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
			<div class="head"><span class="tag">装修信息<em>&nbsp;</em></span></div>
			<div class="cont">
				<div class="ListShops">
$listHr
				</div>				
			</div>
		</div>
	</div>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyNews(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListShops').html(d);
	});
}
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php require(style_html("foot")); ?>