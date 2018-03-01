<?php 

unset($listdb);
$rows=12;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz=1 ";
$query=$db->query("SELECT * FROM {$pre}coupon_content $where ORDER BY posttime DESC LIMIT $min,$rows");
while($rs=$db->fetch_array($query)){
	$moredb=$db->get_one("SELECT price,mart_price FROM {$pre}coupon_content_1  WHERE id='$rs[id]'" );
	$rs[price]=$moredb[price];
	$rs[mart_price]=$moredb[mart_price];
	$rs[picurl]=$rs[picurl]?tempdir($rs[picurl]):"$webdb[www_url]/images/default/nopic.jpg";
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listdb[]=$rs;
}
$showpage=getpage("{$pre}coupon_content",$where,"?uid=$uid",$rows);

$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&uid=\\1&page=\\2')",$showpage);

if($showtype=='moreshow'){
	$showlists="";
	foreach($listdb AS $key=>$rs){
		$showlists.="<dl>
						<dt><a href='/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><span><img src='$rs[picurl]'></span></a></dt>
						<dd>
							<ul>
								<ol><a href='/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></ol>
								<li>&yen;{$rs[price]} <div class='gifnum'>市场价:<span>&yen;$rs[mart_price]</span></div></li>
							</ul>
						</dd>
					</dl>";
	}
	$showlists.="<div class='ShowPage'>$showpage</div>";
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
			<div class="head"><span class="tag">商家促销<em>&nbsp;</em></span></div>
			<div class="cont">
				<div class="ListShops">
<!--
EOT;
foreach($listdb AS $key=>$rs){
print <<<EOT
-->	
					<dl>
						<dt><a href="/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]" target="_blank"><span><img src='$rs[picurl]'></span></a></dt>
						<dd>
							<ul>
								<ol><a href="/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]" target="_blank">$rs[title]</a></ol>
								<li>&yen;{$rs[price]} <div class='gifnum'>市场价:<span>&yen;$rs[mart_price]</span></div></li>
							</ul>
						</dd>
					</dl>
<!--
EOT;
}
print <<<EOT
-->	
					<div class="ShowPage">$showpage</div>
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
<?php require(style_html("foot"));  ?>