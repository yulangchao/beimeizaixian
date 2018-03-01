<?php 

$rows=4;
$page||$page=1;
$min=($page-1)*$rows;
$where=" WHERE uid='$uid' AND yz=1 ";
$ordername=$types?$types:"id";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}fuwu_content $where ORDER BY $ordername DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);

$showlists="";
while($rs=$db->fetch_array($query)){
	$moredb=$db->get_one("SELECT * FROM {$pre}fuwu_content_$rs[mid]  WHERE id='$rs[id]'" );
	$rs[content]=@preg_replace('/<([^>]*)>/is',"",$moredb[content]);	//把HTML代码过滤
	$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
	$rs[picurl]=$rs[picurl]?tempdir($rs[picurl]):"$webdb[www_url]/images/default/nopic.jpg";
	$showlists.="<div class='listfuwu'><ul>";
	$showlists.="<li class='img'><a href='/fuwu/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><span><img src='$rs[picurl]'></span></a></li>";
	$showlists.="<li class='t'><a href='/fuwu/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></li>";
	$showlists.="<li class='c'>{$rs[content]}</li>";
	$showlists.="</ul></div>";
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
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/fuwu.css" />
<div class='MainContainer'>
	<div class='MinLefts'>
		<ul class='ShowType'>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=posttime&uid=$uid')">最新服务</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=hits&uid=$uid')">热门服务</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=levelstime&uid=$uid')">推荐服务</a></li>
		</ul>
	</div>
	<div class='MainRights'>
		<div class='head'><span class='tag'>上门服务</span></div>
		<div class='ListShopCont ListFuwuBox'>
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
<?php require(style_html("foot"));  ?>