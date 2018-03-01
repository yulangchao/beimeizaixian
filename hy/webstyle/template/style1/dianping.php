<?php 

unset($listdb);
$rows=8;
$page||$page=1;
$min=($page-1)*$rows;
$ordername=$types?$types:"id";
$where=" WHERE uid='$uid' AND yz=1 ";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}dianping_content $where ORDER BY $ordername DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);
$showlists="";
while($rs=$db->fetch_array($query)){
	$rs[picurl]=$rs[picurl]?tempdir($rs[picurl]):"$webdb[www_url]/images/default/nopic.jpg";
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$showlists.="<dl>
					<dt><a href='/dianping/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><span><img src='$rs[picurl]'></span></a></dt>
					<dd>
						<ul>
							<ol><a href='/dianping/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></ol>
							<li style='font-size:13px;color:#999;'>地址：$rs[address]</li>
						</ul>
					</dd>
				</dl>";
}
$showlists.="<div class='ShowPage'>$showpage</div>";

if($showtype=='moreshow'){
	die($showlists);
}

require(style_html("head"));
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/shop.css" />
<div class='MainContainer'>
	<div class='MinLefts'>
		<ul class='ShowType'>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=posttime&uid=$uid')">最新店铺</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=hits&uid=$uid')">热门店铺</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=levelstime&uid=$uid')">推荐店铺</a></li>
		</ul>
	</div>
	<div class='MainRights'>
		<div class='head'><span class='tag'>店铺信息</span></div>
		<div class='ListShopCont'>
			$showlists
		</div>
	</div>	
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function showHyNews(url){
   $.get(url+"&"+Math.random(),function(d){
		$('.ListShopCont').html(d);
	});
}
function changeShow(num){
	$('.ShowType li').removeClass('ck');
	$('.ShowType li').eq(num).addClass('ck');
}
changeShow(0);
$('.ShowType li').click(function(){
	var thisnum=$(this).index();
	changeShow(thisnum);
});
//-->
</SCRIPT>
<!--
EOT;
?>
-->
<?php 
require(style_html("foot"));
?>