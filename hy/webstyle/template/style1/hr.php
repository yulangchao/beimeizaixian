<?php 

unset($listdb);
$rows=8;
$page||$page=1;
$min=($page-1)*$rows;
$ordername=$types?"A.".$types:"A.id";
$where=" WHERE A.uid='$uid' AND A.yz=1 ";
$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.nums,B.workplace FROM {$pre}hr_content A LEFT JOIN {$pre}hr_content_1 B ON A.id=B.id $where ORDER BY $ordername DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?uid=$uid",$rows,$totalNum);
$showpage=preg_replace("/\?uid=([\d]+)&page=([\d]+)/is","javascript:showHyNews('?showtype=moreshow&types=$types&uid=\\1&page=\\2')",$showpage);
$listHr="<ul class='listHr listHrH'><li class='li1'>职位名称</li>\r\n<li class='li2'>招聘人数</li>\r\n<li class='li3'>工作地点</li>\r\n</ul>\r\n";
while($rs=$db->fetch_array($query)){
	$listHr.="<ul class='listHr listHrC'><li class='li1'><a href='/hr/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></li>\r\n<li class='li2'>$rs[nums]人</li>\r\n<li class='li3'>$rs[workplace]</li>\r\n</ul>\r\n";
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
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=posttime&uid=$uid')">最新招聘</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=hits&uid=$uid')">热门招聘</a></li>
			<li><a href="javascript:showHyNews('?showtype=moreshow&types=levelstime&uid=$uid')">推荐招聘</a></li>
		</ul>
	</div>
	<div class='MainRights'>
		<div class='head'><span class='tag'>招聘信息</span></div>
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