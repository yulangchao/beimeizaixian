<?php
require(dirname(__FILE__).'/global.php');
header('Content-Type: text/html; charset='.WEB_LANG);

$page>1 || $page=1;
$rows=5;
$min=($page-1)*$rows;

if($selects=='index'){	
	$query = $db->query("SELECT B.*,A.* FROM {$pre}shoptg_content A LEFT JOIN {$pre}shoptg_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT 5");
	$show="<div class='moreCont'>\r\n	<h3>�̼��Ź�</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/shoptg/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/shoptg/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>�г��ۣ�</span><strike>��{$rs[market_price]}</strike></div>\r\n";
		$show.="		<div><span>�Żݼۣ�</span><em>��{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>�������ڣ�</span>{$rs[end_time]}</div>\r\n";
		$show.="		</dd>\r\n";
		$show.="	</dl>\r\n";
	}
	$show.="</div>\r\n";

	$query = $db->query("SELECT B.*,A.* FROM {$pre}gift_content A LEFT JOIN {$pre}gift_content_1 B ON A.id=B.id WHERE A.uid='$uid' ORDER BY A.id DESC LIMIT 5");
	$show.="<div class='moreCont'>\r\n	<h3>�̼���Ʒ</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/gift/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>�г��ۣ�</span><strike>��{$rs[mart_price]}</strike></div>\r\n";
		$show.="		<div><span>������֣�</span><em>{$rs[money]}</em>{$webdb[MoneyDW]}</div>\r\n";
		$show.="		<div><span>�������ڣ�</span>{$rs[posttime]}</div>\r\n";
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
	$show="<div class='moreCont'>\r\n	<h3>�̼Ҽ��</h3>\r\n";
	$show.="	<div class='conts'>$rsdb[content]</div>\r\n";
	$show.="</div>\r\n";

	$show.="<ul class='contacts'>\r\n";
	$show.="	<li><span>��Ӫ���ࣺ</span>$rsdb[fname]</li>\r\n";
	$show.="	<li><span>������ҵ��</span>$rsdb[my_trade]</li>\r\n";
	$show.="	<li><span>��ҵ���ͣ�</span>$rsdb[qy_cate]</li>\r\n";
	$show.="	<li><span>ע���ʱ���</span>$rsdb[qy_regmoney]��</li>\r\n";
	$show.="	<li><span>��Ӫģʽ��</span>$rsdb[qy_saletype]</li>\r\n";
	$show.="	<li><span>ע���ַ��</span>$rsdb[qy_regplace]</li>\r\n";
	$show.="	<li><span>��Ӫ��Ʒ�����</span>$rsdb[qy_pro_ser]</li>\r\n";
	$show.="	<li><span>��Ҫ�ɹ���Ʒ��</span>$rsdb[my_buy]</li>\r\n";
	$show.="</ul>\r\n";
	echo $show;
	exit;
}
if($selects=='news'){	
	$where=" WHERE uid='$uid' AND yz=1 ";
	$query=$db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$_pre}news $where ORDER BY posttime DESC LIMIT $min,$rows");
	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$show="<div class='moreCont'>\r\n	<h3>�̼�����</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//��HTML�������
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
	$show="<div class='moreCont'>\r\n	<h3>�̼Ҳ�Ʒ</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/shop/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>�г��ۣ�</span><strike>��{$rs[market_price]}</strike></div>\r\n";
		$show.="		<div><span>�Żݼۣ�</span><em>��{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>�������</span>{$rs[storage]}</div>\r\n";
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
	$show="<div class='moreCont'>\r\n	<h3>�Żݴ���</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$show.="	<dl>\r\n";
		$show.="		<dt><a href=\"$webdb[www_url]/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\"><img src=\"$rs[picurl]\" onerror=\"this.src='$webdb[www_url]/images/default/nopic.jpg'\"/></a></dt>\r\n";
		$show.="		<dd>\r\n";
		$show.="		<h4><a href=\"$webdb[www_url]/coupon/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]\">$rs[title]</a></h4>\r\n";
		$show.="		<div><span>�г��ۣ�</span><strike>��{$rs[mart_price]}</strike></div>\r\n";
		$show.="		<div><span>�Żݼۣ�</span><em>��{$rs[price]}</em></div>\r\n";
		$show.="		<div><span>��ֹ���ڣ�</span>{$rs[end_time]}</div>\r\n";
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
	$show="<div class='moreCont'>\r\n<h3>��Ƹ��Ϣ</h3>\r\n";
	while($rs = $db->fetch_array($query)){
		$show.="<ul class='listjob'>\r\n";
		$show.="<li><a href=\"$webdb[www_url]/hr/bencandy.php?fid=$rs[fid]&id=$rs[id]\">$rs[title]</a></li>\r\n";
		$show.="<li>��Ƹ��<span>{$rs[nums]}</span>��</li>\r\n";
		$show.="<li>�����ص㣺<span>{$rs[workplace]}</span></li>\r\n";
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
	$show="<div class='moreCont'>\r\n<h3>�̼�ͼƬ</h3>\r\n";
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
	$contact.="	<li><span>��λ���ƣ�</span>$rsdb[title]</li>\r\n";
	$contact.="	<li><span>��ϵ�ˣ�</span>$rsdb[qy_contact]</li>\r\n";
	$contact.="	<li><span>ְλ��</span>$rsdb[qy_contact_zhiwei]</li>\r\n";
	$contact.="	<li><span>�绰���룺</span><a href='tel:$rsdb[qy_contact_tel]'>$rsdb[qy_contact_tel]</a></li>\r\n";
	$contact.="	<li><span>������룺</span>$rsdb[qy_contact_fax]</li>\r\n";
	$contact.="	<li><span>�ƶ����룺</span><a href='tel:$rsdb[qy_contact_mobile]'>$rsdb[qy_contact_mobile]</a></li>\r\n";
	$contact.="	<li><span>��λ��ҳ��</span><a href='$rsdb[qy_website]'>$rsdb[qy_website]</a></li>\r\n";
	$contact.="	<li><span>�����ַ��</span>$rsdb[qy_contact_email]</li>\r\n";
	$contact.="	<li><span>��������</span>{$area_DB[name][$rsdb[province_id]]} {$city_DB[name][$rsdb[city_id]]} {$zone_DB[name][$rsdb[zone_id]]} {$street_DB[name][$rsdb[street_id]]}</li>\r\n";
	$contact.="	<li><span>�������룺</span>$rsdb[qy_postnum]</li>\r\n";
	$contact.="	<li><span>��ϸ��ַ��</span>$rsdb[qy_address]</li>\r\n";
	$contact.="	<li><span>Q Q��</span>$rsdb[show_qq]</li>\r\n";
	$contact.="	<li><span>MSN��</span>$rsdb[show_msn]</li>\r\n";
	$contact.="	<li><span>����������</span>$rsdb[show_ww]</li>\r\n";
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
	//��ʵ��ַ��ԭ
	$data[content]=En_TruePath($data[content],0,1);
	$data[posttime] =date("Y-m-d",$data[posttime] );

	//�õ��󶨵�ͼƬ
	$show_bd_pics=show_news_pics("{$_pre}news"," WHERE id=$id");
	$db->query("UPDATE `{$_pre}news` SET hits=hits + 1  WHERE id='$id'");
	$show="<div class='moreCont'>\r\n<h3>�̼�����</h3>\r\n";
	$show.="<div class='ShowNews'>\r\n";
	if($data[uid]!=$lfjuid && !$data[yz]){
		$show.="<div class='nopass'>��Ϣ���������...</div>\r\n";
	}else{
		$show.="<div class='title'>$data[title]</div>\r\n";
		$show.="<div class='info'>ʱ�䣺$data[posttime] �����$data[hits]��</div>\r\n";
		$show.="<div class='content'>$data[content]</div>\r\n";
		$show.="<ul class='listpics'>$show_bd_pics</ul>\r\n";
	}
	$show.="</div>\r\n";	
	$show.="</div>\r\n";
	echo $show;
	exit;
}
/**
*չʾ�û��󶨵�ͼƬ
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