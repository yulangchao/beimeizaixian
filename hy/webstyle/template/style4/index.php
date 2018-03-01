<?php 
require(style_html("head"));

$listhynews='';
$query=$db->query("SELECT * FROM {$_pre}news WHERE uid='$uid' AND yz='1' ORDER BY posttime DESC LIMIT 3");
while($rs=$db->fetch_array($query)){
	$listhynews.="<div><a href='news.php?uid=$uid&id=$rs[id]'>$rs[title]</a></div>\r\n";
}

$listhyshops="<ul class='ListMyshop'>\r\n";
$query = $db->query("SELECT * FROM {$pre}shop_content WHERE uid='$uid' AND yz='1' ORDER BY posttime DESC LIMIT 10 ");
while($rs = $db->fetch_array($query)){
	@extract($db->get_one("SELECT market_price FROM {$pre}shop_content_$rs[mid]  WHERE id='$rs[id]'" ));
	$rs[market_price]=$market_price;
	$listhyshops.="<dl>
					<dt><a href='/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><span><img src='$rs[picurl]'></span></a></dt>
					<dd>
						<ul>
							<ol><a href='/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></ol>
							<li><span class='price'>&yen;{$rs[price]}</span> <span class='price0'>&yen;{$rs[market_price]}</em></li>
						</ul>
					</dd>
				</dl>";
}
$listhyshops.="</ul>\r\n";

$listhygifts="<ul class='ListMyshop'>\r\n";
$query = $db->query("SELECT * FROM {$pre}gift_content WHERE uid='$uid' AND yz='1' ORDER BY posttime DESC LIMIT 10 ");
while($rs = $db->fetch_array($query)){
	@extract($db->get_one("SELECT mart_price FROM {$pre}gift_content_1  WHERE id='$rs[id]'" ));
	$rs[mart_price]=$mart_price;
	$listhygifts.="<dl>
					<dt><a href='/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'><span><img src='$rs[picurl]'></span></a></dt>
					<dd>
						<ul>
							<ol><a href='/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]' target='_blank'>$rs[title]</a></ol>
							<li><span class='price'>兑换{$webdb[MoneyName]}:<font>$rs[money]</font>{$webdb[MoneyDW]}</span> <span class='price0'>&yen;{$rs[mart_price]}</em></li>
						</ul>
					</dd>
				</dl>";
}
$listhygifts.="</ul>\r\n";

//幻灯片代码
if(!$mystyledb[hideslide]||($action=='setstyle')){	
	$mystyledb[slide]||$mystyledb[slide]=$defaultstyle[slide];
	$mystyledb[slideHeight]||$mystyledb[slideHeight]=$defaultstyle[slideHeight];	
	$listSlides="<div class='MainSlide' style='height:{$mystyledb[slideHeight]}px;'>\r\n";
	$listSlides.="<ul class='slideimgs'>\r\n";
	$listnums="";
	$i=0;
	foreach($mystyledb[slide] AS $key=>$rs){		
		$i++;
		$rs[img]=$rs[img]?tempdir($rs[img]):"$WebStyleurl/images/style4/$i.png";
		$listSlides.="<li><a href='$rs[url]' title='$rs[title]' style='background:url($rs[img]) center center no-repeat;' target='_blank'><span>$rs[title]</span></a></li>\r\n";
		$listnums.="<li>$i</li>\r\n";
	}	
	$listSlides.="</ul>\r\n";
	$listSlides.="<ul class='listnum'>$listnums</ul>\r\n";
	$set_slides=($action=='setstyle')?"<a class='SetStyleBut' href='$WebStyleurl/setslide.php?type=1&stylename=style1&uid=$uid&tag=slide'>点击设置内容</a>":"";
	$listSlides.="<div class='prev'>上一张</div><div class='next'>下一张</div>";
	$listSlides.=$set_slides;
	$listSlides.="</div>\r\n";
	$listSlides.="<script type='text/javascript' src='$WebStyleurl/images/style4/slide.js'></script>";
}
 
?>
<!--
<?php
print <<<EOT
-->
<link type="text/css" rel="stylesheet" href="$WebStyleurl/images/style4/index.css" />
$listSlides
<div class='companyNews'>
	<ul>
		<li class='tag'>最新公告/NEWS</li>
		<li>
			<div class='List'>
				$listhynews
			</div>
		</li>
		<li class='more'><a href='news.php?uid=$uid'>更多&gt;&gt;</a></li>
	</ul>
</div>
<div class='ShowContent'>
	<div class='head'><span class='tag'>最新商品<em>/SHOP</em></span></div>
	<div class='cont'>
		$listhyshops
	</div>
</div>
<div class='ShowContent'>
	<div class='head'><span class='tag'>店铺礼品<em>/GIFT</em></span></div>
	<div class='cont'>
		$listhygifts
	</div>
</div>
$quit_setstyle
<!--
EOT;
?>
-->
<?php require(style_html("foot"));  ?>