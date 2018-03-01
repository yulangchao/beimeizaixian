<?php
define('WebStyleDir',dirname(__FILE__).'/');
require_once(WebStyleDir."../global.php");
$WebStyleurl=$Murl.'/webstyle';					//访问地址

$uid||$uid=$lfjuid;
$companydb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid'");
if(!$companydb){
	showerr("没有相应的店铺！");
}

if($action=='setstyle'){
	if($uid && $uid!=$lfjuid){
		showerr("您只能进入自己的铺面设置界面！");
	}
	if($stylename){
		$stylename=filtrate($stylename);
		$db->query("UPDATE `{$_pre}company` SET webstyle='$stylename' WHERE uid='$uid'");
		$companydb[webstyle]=$stylename;
	}
}

if($companydb[webstyle]){
	$stylename=$companydb[webstyle];
}else{
	header("location:/home/?uid=$uid");
	exit;
}


$conf=$db->get_one("SELECT * FROM {$_pre}home WHERE uid='$uid'");
//访客
if($lfjuid)
{
	if($lfjuid!=$conf[uid]){
		$conf[visitor]="{$lfjuid}\t{$lfjid}\t{$timestamp}\r\n$conf[visitor]";
	}
}
else
{
	$conf[visitor]="0\t{$onlineip}\t{$timestamp}\r\n$conf[visitor]";
}
$detail=explode("\r\n",$conf[visitor]);
foreach( $detail AS $key=>$value)
{
	if($key>0&&(strstr($value,"{$lfjuid}\t{$lfjid}\t")||strstr($value,"0\t$onlineip")))
	{
		unset($detail[$key]);
	}
	if($key>20||!$value)
	{
		unset($detail[$key]);
	}
}
$conf[visitor]=implode("\r\n",$detail);

function style_html($html){
	global $stylename;
	if(file_exists(WebStyleDir."template/".$stylename."/".$html.".php")){
		return WebStyleDir."template/".$stylename."/".$html.".php";
	}elseif(file_exists(WebStyleDir."template/default/".$html.".php")){
		return WebStyleDir."template/default/".$html.".php";
	}
}
unset($defaultmenu);
$defaultmenu=array(
	array(
		'title' => '商家首页',
		'url' => 'index.php',
	),
	array(
		'title' => '商家介绍',
		'url' => 'about.php',
	),
	array(
		'title' => '商家新闻',
		'url' => 'news.php',
	),
	array(
		'title' => '商家产品',
		'url' => 'shop.php',
	),
	array(
		'title' => '图片展示',
		'url' => 'hypic.php',
	),
	array(
		'title' => '顾客点评',
		'url' => 'hydianping.php',
	),
	array(
		'title' => '商家礼品',
		'url' => 'gift.php',
	),
	array(
		'title' => '促销信息',
		'url' => 'coupon.php',
	),
	array(
		'title' => '联系方式',
		'url' => 'contact.php',
	),
);

//修改样式参数
function edit_styles($config){
	global $db,$_pre,$uid,$type,$stylename;
	$checkstyle=$db->get_one("SELECT * FROM {$_pre}style WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
	if($checkstyle){
		$db->query("UPDATE `{$_pre}style` SET config='$config' WHERE uid='$uid' AND type='$type' AND stylename='$stylename'");
	}else{
		$db->query("INSERT INTO  `{$_pre}style` (`uid`,`type`,`stylename`,`config`) VALUES ('$uid','$type','$stylename','$config')");
	}
}

$companydb[picurl]=$companydb[picurl]?tempdir($companydb[picurl]):"$webdb[www_url]/images/default/nopic.jpg";
$companydb[posttime]=date("Y-m-d",$companydb[posttime]);

@extract($db->get_one("SELECT COUNT(*) AS guestbookNUM  FROM {$_pre}guestbook  WHERE cuid='$uid'" ));
?>