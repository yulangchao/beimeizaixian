<?php 

unset($listdb);
$rows=12;
$page||$page=1;
$min=($page-1)*$rows;
$ordername=$types?$types:"id";
$where=" WHERE uid='$uid' AND yz=1 ";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}fenlei_content $where ORDER BY $ordername DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);
$listHr="<ul class='listHr listHrH'><li class='li1'>信息标题</li>\r\n<li class='li2'>所在栏目</li>\r\n<li class='li3' style='text-align:center;'>发布时间</li>\r\n</ul>\r\n";
while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$listHr.="<ul class='listHr listHrC'><li class='li1'><a href='/f/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></li>\r\n<li class='li2'><a href='/f/list.php?fid=$rs[fid]' target='_blank'>$rs[fname]</a></li>\r\n<li class='li3'>$rs[posttime]</li>\r\n</ul>\r\n";
}
$listHr.="<div class='ShowPage'>$showpage</div>";

if($showtype=='moreshow'){
	die($listHr);
}

require(style_html("head"));

?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/other.css" />
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style1/hr.css" />
<div class='MainContainer'>
	<div class='MinLefts'>
		<ul class='ShowType'>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=posttime&uid=$uid')">最新信息</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=hits&uid=$uid')">热门信息</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=levelstime&uid=$uid')">推荐信息</a></li>
		</ul>
	</div>
	<div class='MainRights'>
		<div class='head'><span class='tag'>分类信息</span></div>
		<div class='ListShopCont'>
			$listHr
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