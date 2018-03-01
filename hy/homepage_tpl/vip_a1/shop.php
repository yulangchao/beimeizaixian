<?php

if($m){
	$titleDB['title']=$titleDB['keywords']=$titleDB['description']=$rsdb['title'].' 商家的产品介绍信息';
}

unset($array);
$page>1 || $page=1;
$rows=16;
$min=($page-1)*$rows;

$SQL="SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}shop_content WHERE uid='$uid' $SQL ORDER BY id DESC LIMIT $min,$rows";
if($myfid>0){
	$mysoft = $db->get_one("SELECT * FROM {$pre}shop_mysort WHERE uid='$uid' AND fid='$myfid' ");
	if($mysoft){
		if($mysoft['fup'] || !$db->get_one("SELECT * FROM {$pre}shop_mysort WHERE uid='$uid' AND fup='$myfid' ") ){
			$SQL="SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}shop_content WHERE myfid='$myfid' ORDER BY id DESC LIMIT $min,$rows";
		}else{
			$SQL="SELECT SQL_CALC_FOUND_ROWS A.* FROM {$pre}shop_content A LEFT JOIN {$pre}shop_mysort B ON A.myfid=B.fid WHERE B.fup='$myfid' OR B.fid='$myfid' ORDER BY id DESC LIMIT $min,$rows";
		}
	}
}

$query = $db->query($SQL);
$RS=$db->get_one("SELECT FOUND_ROWS()");
while($rs = $db->fetch_array($query)){
	$rs[picurl]=tempdir($rs[picurl]);
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$array[]=$rs;
}
$showpage=getpage("","","?m=$m&uid=$uid",$rows,$RS['FOUND_ROWS()']);
?>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<!--
<?php
print <<<EOT
-->
<div class="maincont1">
	<div class="head">
		<div class="tag">商品展示</div>
		<div class="more">&nbsp;</div>
	</div>
	<div class="cont">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td>
<!--
EOT;
foreach($array AS $rs){
$rs[title]=get_word($rs[title],26);
print <<<EOT
-->
			<div class="listShop">
				<div class="img"><a href="$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]" target="_blank"><img src="$rs[picurl]" onerror="this.src='$webdb[www_url]/images/default/nopic.jpg'" height="120"/></a></div>
				<div class="p">价格:{$rs[price]}元</div>
				<div class="t"><a href="$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]" target="_blank">$rs[title]</a></div>
			</div>
<!--
EOT;
}
print <<<EOT
-->			
		</td>
	  </tr>
	</table>
	<div class="Shoppage">$showpage</div>
	</div>
</div>
<!--
EOT;
?>
-->