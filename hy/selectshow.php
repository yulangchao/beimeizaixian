<?php
require(dirname(__FILE__).'/global.php');
header('Content-Type: text/html; charset='.WEB_LANG);

$page>1 || $page=1;
$rows=5;
$min=($page-1)*$rows;

if($selects=='index'){	
	$query = $db->query("SELECT B.*,A.* FROM {$pre}shoptg_content A LEFT JOIN {$pre}shoptg_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT 5");
	$show="<div class='moreCont'>\r\n	<h3>商家团购</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/shoptg/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/shoptg/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>市场价：</span><strike>￥{$rs[market_price]}</strike></div>\r\n";
		$show.="		<div><span>优惠价：</span><em>￥{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>结束日期：</span>{$rs[end_time]}</div>\r\n";
		$show.="		</dd>\r\n";
		$show.="	</dl>\r\n";
	}
	$show.="</div>\r\n";

	$query = $db->query("SELECT B.*,A.* FROM {$pre}gift_content A LEFT JOIN {$pre}gift_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT 5");
	$show.="<div class='moreCont'>\r\n	<h3>商家礼品</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>市场价：</span><strike>￥{$rs[mart_price]}</strike></div>\r\n";
		$show.="		<div><span>所需积分：</span><em>{$rs[money]}</em>{$webdb[MoneyDW]}</div>\r\n";
		$show.="		<div><span>发布日期：</span>{$rs[posttime]}</div>\r\n";
		$show.="		</dd>\r\n";
		$show.="	</dl>\r\n";
	}
	$show.="</div>\r\n";

	echo $show;
	exit;
}
elseif($selects=='about'){
	$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid'");
	$rsdb[content] = En_TruePath($rsdb[content],0,1);
	$show="<div class='moreCont'>\r\n	<h3>商家简介</h3>\r\n";
	$show.="	<div class='conts'>$rsdb[content]</div>\r\n";
	$show.="</div>\r\n";

	$show.="<ul class='contacts'>\r\n";
	$show.="	<li><span>主营分类：</span>$rsdb[fname]</li>\r\n";
	$show.="	<li><span>所属行业：</span>$rsdb[my_trade]</li>\r\n";
	$show.="	<li><span>企业类型：</span>$rsdb[qy_cate]</li>\r\n";
	$show.="	<li><span>注册资本：</span>$rsdb[qy_regmoney]万</li>\r\n";
	$show.="	<li><span>经营模式：</span>$rsdb[qy_saletype]</li>\r\n";
	$show.="	<li><span>注册地址：</span>$rsdb[qy_regplace]</li>\r\n";
	$show.="	<li><span>主营产品或服务：</span>$rsdb[qy_pro_ser]</li>\r\n";
	$show.="	<li><span>主要采购产品：</span>$rsdb[my_buy]</li>\r\n";
	$show.="</ul>\r\n";
	echo $show;
	exit;
}
if($selects=='news'){	
	$where=" WHERE uid='$uid' AND yz=1 ";
	$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}news $where ORDER BY posttime DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n	<h3>商家新闻</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤
		$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
		$show.="	<div class='listnews'>\r\n";
		$show.="		<h4><a href=\"javascript:showmorpage('./selectshow.php?uid=$uid&selects=shownews&id=$rs[id]')\">$rs[title]</a><span>$rs[posttime]</span></h4>\r\n";
		$show.="		<p>\r\n		{$rs[content]}\r\n</p>\r\n";
		$show.="	</div>\r\n";
	}	
	$showpage=getpage("","","?uid=$uid",$rows,$RS['FOUND_ROWS()']);
	$showpages=preg_replace("/\?uid=$uid&page=([\d]+)/is","javascript:showmorpage('./selectshow.php?uid=$uid&selects=$selects&page=\\1')",$showpage);
	$show.="	<div class='page'>$showpages</div>\r\n";/**/
	$show.="</div>\r\n";
	echo $show;
	exit;
}
elseif($selects=='shop'){	
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS B.*,A.* FROM {$pre}shop_content A LEFT JOIN {$pre}shop_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n	<h3>商家产品</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>市场价：</span><strike>￥{$rs[market_price]}</strike></div>\r\n";
		$show.="		<div><span>优惠价：</span><em>￥{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>库存量：</span>{$rs[storage]}</div>\r\n";
		$show.="		</dd>\r\n";
		$show.="	</dl>\r\n";
	}	
	$showpage=getpage("","","?uid=$uid",$rows,$RS['FOUND_ROWS()']);
	$showpages=preg_replace("/\?uid=$uid&page=([\d]+)/is","javascript:showmorpage('./selectshow.php?uid=$uid&selects=$selects&page=\\1')",$showpage);
	$show.="	<div class='page'>$showpages</div>\r\n";/**/
	$show.="</div>\r\n";
	echo $show;
	exit;
}
elseif($selects=='coupon'){	
	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS B.*,A.* FROM {$pre}coupon_content A LEFT JOIN {$pre}coupon_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n	<h3>优惠促销</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>市场价：</span><strike>￥{$rs[mart_price]}</strike></div>\r\n";
		$show.="		<div><span>优惠价：</span><em>￥{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>截止日期：</span>{$rs[end_time]}</div>\r\n";
		$show.="		</dd>\r\n";
		$show.="	</dl>\r\n";
	}	
	$showpage=getpage("","","?uid=$uid",$rows,$RS['FOUND_ROWS()']);
	$showpages=preg_replace("/\?uid=$uid&page=([\d]+)/is","javascript:showmorpage('./selectshow.php?uid=$uid&selects=$selects&page=\\1')",$showpage);
	$show.="	<div class='page'>$showpages</div>\r\n";/**/
	$show.="</div>\r\n";
	echo $show;
	exit;
}
elseif($selects=='hr'){	
	$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$pre}hr_content A LEFT JOIN {$pre}hr_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n<h3>招聘信息</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$show.="<ul class='listjob'>\r\n";
		$show.="<li><a href=\"$webdb[www_url]/hr/bencandy.php?fid=$rs[fid]&id=$rs[id]\">$rs[title]</a></li>\r\n";
		$show.="<li>招聘：<span>{$rs[nums]}</span>人</li>\r\n";
		$show.="<li>工作地点：<span>{$rs[workplace]}</span></li>\r\n";
		$show.="</ul>\r\n";
	}	
	$showpage=getpage("","","?uid=$uid",$rows,$RS['FOUND_ROWS()']);
	$showpages=preg_replace("/\?uid=$uid&page=([\d]+)/is","javascript:showmorpage('./selectshow.php?uid=$uid&selects=$selects&page=\\1')",$showpage);
	$show.="	<div class='page'>$showpages</div>\r\n";/**/
	$show.="</div>\r\n";
	echo $show;
	exit;
}
elseif($selects=='pic'){
	$rows=6;
	$min=($page-1)*$rows;
	$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}pic WHERE uid='$uid' ORDER BY pid DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n<h3>商家图片</h3>\r\n";
	$show.="<ul class='listpics'>\r\n";
	$i=0;
	while($rs = $db->fetch_array($query)){
		$rs[url]=tempdir($rs[url]);
		$show.="<li>\r\n";
		$show.="<a href=\"javascript:showbigpic($i)\"><img src=\"$rs[url]\" onerror=\"this.src='$Murl/images/default/userpicdefault.gif';\" alt='$rs[title]'/></a>\r\n";
		$show.="</li>\r\n";
		$i++;
	}
	$show.="</ul>\r\n";
	$showpage=getpage("","","?uid=$uid",$rows,$RS['FOUND_ROWS()']);
	$showpages=preg_replace("/\?uid=$uid&page=([\d]+)/is","javascript:showmorpage('./selectshow.php?uid=$uid&selects=$selects&page=\\1')",$showpage);
	$show.="	<div class='page'>$showpages</div>\r\n";/**/
	$show.="</div>\r\n";
	echo $show;
	exit;
}
elseif($selects=='cotact'){
	$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid'");
	require(Mpath.'/bd_pics.php');
	$rsdb[show_qq]=getOnlinecontact('qq',$rsdb[qq]);
	$rsdb[show_msn]=getOnlinecontact('msn',$rsdb[msn]);
	$rsdb[show_ww]=getOnlinecontact('ww',$rsdb[ww]);
	$rsdb[qy_contact_email] =str_replace("@","#",$rsdb[qy_contact_email]);
	$contact="<ul class='contacts'>\r\n";
	$contact.="	<li><span>单位名称：</span>$rsdb[title]</li>\r\n";
	$contact.="	<li><span>联系人：</span>$rsdb[qy_contact]</li>\r\n";
	$contact.="	<li><span>职位：</span>$rsdb[qy_contact_zhiwei]</li>\r\n";
	$contact.="	<li><span>电话号码：</span><a href='tel:$rsdb[qy_contact_tel]'>$rsdb[qy_contact_tel]</a></li>\r\n";
	$contact.="	<li><span>传真号码：</span>$rsdb[qy_contact_fax]</li>\r\n";
	$contact.="	<li><span>移动号码：</span><a href='tel:$rsdb[qy_contact_mobile]'>$rsdb[qy_contact_mobile]</a></li>\r\n";
	$contact.="	<li><span>单位主页：</span><a href='$rsdb[qy_website]'>$rsdb[qy_website]</a></li>\r\n";
	$contact.="	<li><span>邮箱地址：</span>$rsdb[qy_contact_email]</li>\r\n";
	$contact.="	<li><span>所在区域：</span>{$area_DB[name][$rsdb[province_id]]} {$city_DB[name][$rsdb[city_id]]} {$zone_DB[name][$rsdb[zone_id]]} {$street_DB[name][$rsdb[street_id]]}</li>\r\n";
	$contact.="	<li><span>邮政编码：</span>$rsdb[qy_postnum]</li>\r\n";
	$contact.="	<li><span>详细地址：</span>$rsdb[qy_address]</li>\r\n";
	$contact.="	<li><span>Q Q：</span>$rsdb[show_qq]</li>\r\n";
	$contact.="	<li><span>MSN：</span>$rsdb[show_msn]</li>\r\n";
	$contact.="	<li><span>阿里旺旺：</span>$rsdb[show_ww]</li>\r\n";
	$contact.="</ul>\r\n";
	if($rsdb['gg_maps']){ 
		$contact.="<div class='ShowMap'>\r\n";
		$contact.="<iframe src=\"$Mdomain/job.php?job=show_ggmaps&position=$rsdb[gg_maps]&title=$rsdb[title]\"  width=\"100%\" height='300' scrolling='no' frameborder='0' ></iframe>\r\n";
		$contact.="</div>\r\n";
	}
	echo $contact;
	exit;
}
elseif($selects=='shownews'){
	$data=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$id'");
	//真实地址还原
	$data[content]=En_TruePath($data[content],0,1);
	$data[posttime] =date("Y-m-d",$data[posttime] );

	//得到绑定的图片
	$show_bd_pics=show_news_pics("{$_pre}news"," WHERE id=$id");
	$db->query("UPDATE `{$_pre}news` SET hits=hits + 1  WHERE id='$id'");
	$show="<div class='moreCont'>\r\n<h3>商家新闻</h3>\r\n";
	$show.="<div class='ShowNews'>\r\n";
	if($data[uid]!=$lfjuid && !$data[yz]){
		$show.="<div class='nopass'>信息正在审核中...</div>\r\n";
	}else{
		$show.="<div class='title'>$data[title]</div>\r\n";
		$show.="<div class='info'>时间：$data[posttime] 点击：$data[hits]次</div>\r\n";
		$show.="<div class='content'>$data[content]</div>\r\n";
		$show.="<ul class='listpics'>$show_bd_pics</ul>\r\n";
	}
	$show.="</div>\r\n";	
	$show.="</div>\r\n";
	echo $show;
	exit;
}
/**
*展示用户绑定的图片
**/

function show_news_pics($table,$where){	
	global $db,$webdb,$lfjid,$lfjuid,$_pre,$pre,$user_picdir,$Mdomain,$Murl;	
	if(!$where) return "";
	$rsdb=$db->get_one("SELECT bd_pics FROM  $table  $where LIMIT 1");
	if($rsdb[bd_pics]){
		$query=$db->query("SELECT * FROM {$_pre}pic WHERE pid in($rsdb[bd_pics])");
		$i=0;
		while($rs=$db->fetch_array($query)){
			$rs[url]=tempdir($rs[url]);
			$show.='<li><a href="javascript:showbigpic('.$i.')"><img src="'.$rs[url].'" alt="'.$rs[title].'" title="'.$rs[title].'"/></a></li>';
			$i++;
		}
		return $show;
	}else{
		return "";
	}

}
?>