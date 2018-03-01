<?php
define('Mpath',dirname(__FILE__).'/');
define( 'Mdirname' , preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );
define('SYS_TYPE','news');

require_once(Mpath."../inc/common.inc.php");
require_once(Mpath."inc/function.php");
require_once(Mpath."data{$webdb[web_dir]}/config.php");			//本系统全局变量值
require_once(Mpath."data{$webdb[web_dir]}/all_fid.php");			//部分栏目的名称变量值
@include_once(ROOT_PATH."data{$webdb[web_dir]}/ad_cache.php");	//全站广告变量缓存文件
require_once(ROOT_PATH."data{$webdb[web_dir]}/label_hf.php");	//标签的头与底的变量值
@include_once(ROOT_PATH."data{$webdb[web_dir]}/module.php");		//模块系统的参数变量值
require_once(ROOT_PATH."inc/self.tpl.php");		//个性模板函数

$_pre="{$pre}{$webdb[module_pre]}";					//数据表前缀
$Murl=$webdb[_www_url].'/'.Mdirname;					//本模块的访问地址
$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;


$Fid_db=array();
$query = $db->query("SELECT * FROM {$_pre}sort ORDER BY list DESC LIMIT 800");
while($rs = $db->fetch_array($query)){
	$Fid_db[$rs[fup]][$rs[fid]]=$rs[name];
	$Fid_db[name][$rs[fid]]=$rs[name];
	$Fid_db[mid][$rs[fid]]=intval($rs[mid]);
}
//@include_once(Mpath."biz/function.php");

/**
*系统默认风格
**/
//$STYLE=$webdb[Info_style]?$webdb[Info_style]:"default";

/**
*前台是否开放本系统
**/
if($webdb[module_close])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	showerr("当前系统暂时关闭:$webdb[Info_closeWhy]");
}


$fid=intval($fid);
$id=intval($id);
$page=intval($page);
$rows=intval($rows);
$leng=intval($leng);



/**
*获取信息内容
**/
function list_content($SQL,$leng=40){
	global $db,$_pre,$webdb;
	$query=$db->query("SELECT A.* FROM {$_pre}content A $SQL");
	while( $rs=$db->fetch_array($query) ){

		@extract($db->get_one("SELECT content FROM {$_pre}content_1 WHERE id='$rs[id]' LIMIT 1"));

		$rs[content]=@preg_replace('/<([^<]*)>/is',"",$content);	//把HTML代码过滤掉
		$rs[content]=get_word($rs[full_content]=$rs[content],100);
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		if($rs[titlecolor]||$rs[fonttype]){
			$titlecolor=$rs[titlecolor]?"color:$rs[titlecolor];":'';
			$font_weight=$rs[fonttype]==1?'font-weight:bold;':'';
			$rs[title]="<font style='$titlecolor$font_weight'>$rs[title]</font>";
		}
		//$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		$rs[posttime]=format_showtime($rs[posttime]);
		if($rs[picurl]){
			$rs[picurl]=tempdir($rs[picurl]);
		}
		$listdb[]=$rs;
	}
	return $listdb;
}


/**
*获取子栏目
**/
function Get_Fid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$F[$rs[fid]]=$rs;
	}
	return $F;
}

function GetSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$F[$rs[fid]]=$rs[fid];
	}
	return $F;
}

function GetAllSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT fid,fup FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$show.=",$rs[fid]";
		$show.=GetAllSonFid($rs[fid],$rows);
	}
	return $show;
}

function GetAll_SPSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT fid,fup FROM {$_pre}spsort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$show.=",$rs[fid]";
		$show.=GetAll_SPSonFid($rs[fid],$rows);
	}
	return $show;
}


/**
*获取信息内容
**/
function Get_Info($type,$rows=5,$leng=20,$fid=0,$mid=0,$getall=0,$cityid=0){
	if($mid){
		$SQL=" AND A.mid='$mid' ";
	}
	if($cityid){
		$SQL=" AND A.city_id='$cityid' ";
	}
	if($fid){
		if($getall){
			$SQL.=" AND A.fid IN ($fid".GetAllSonFid($fid).") ";
		}else{
			$SQL.=" AND A.fid='$fid' ";
		}
	}
	if($type=='hot'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.hits DESC LIMIT $rows";
	}elseif($type=='lastview'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.lastview DESC LIMIT $rows";
	}elseif($type=='comment'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.comments DESC LIMIT $rows";
	}elseif($type=='new'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='level'){
		$SQL=" WHERE A.yz=1 AND A.levels=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='pic'){
		$SQL=" WHERE A.yz=1 AND A.ispic=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}else{
		return ;
	}
	$listdb=list_content($SQL,$leng);
	return $listdb;
}


//统计栏目文章总数量
function COUNT_titles($fid){
	global $db,$_pre;
	@extract($db->get_one("SELECT COUNT(id) AS TotalNum FROM `{$_pre}content` WHERE fid='$fid'"));
	$TotalNum=($TotalNum<1)?0:$TotalNum;
	return $TotalNum;
}

//统计栏目评论总数量
function sum_comments($fid){
	global $db,$_pre;
	@extract($db->get_one("SELECT sum(comments) AS TotalNum FROM `{$_pre}content` WHERE fid='$fid'"));
	$TotalNum=($TotalNum<1)?0:$TotalNum;
	return $TotalNum;
}

//获取用户头像
function get_member_icon($uid){
	global $db,$pre,$webdb;
	@extract($db->get_one("SELECT icon FROM `{$pre}memberdata` WHERE uid='$uid'"));
	$icon&&$icon=tempdir($icon);
	$icon||$icon="$webdb[www_url]/images/default/nobody.gif";
	return $icon;
}

//获取用户基本信息
function get_members_baseInfo($uid){
	global $userDB,$webdb;
	$memberinfo=$userDB->get_info($uid);
	$memberinfo[icon]&&$memberinfo[icon]=tempdir($memberinfo[icon]);
	$memberinfo[icon]||$memberinfo[icon]="$webdb[www_url]/images/default/noface.gif";
	$groupdb=@include(ROOT_PATH."data{$webdb[web_dir]}/group/$memberinfo[groupid].php");
	$memberinfo[regdate]=date("Y-m-d",$memberinfo[regdate]);
	$memberinfo[lastvist]=date("Y-m-d",$memberinfo[lastvist]);
	$memberinfo[grouptitle]=$groupdb[grouptitle];
	return $memberinfo;
}

//格式化时间输出
function format_showtime($posttime){
	global $timestamp;
	$times=$timestamp-$posttime;
	if($times<3600){
		$showtime=ceil($times/60).'分钟前';
	}elseif($times<3600*24){
		$showtime=ceil($times/3600).'小时前';
	}elseif($times<3600*24*30){
		$showtime=ceil($times/(3600*24)).'天前';
	}else{
		$showtime=date("m-d H:i",$posttime);
	}
	return $showtime;
}

//获取主题的图片
function get_this_pics($id){
	global $db,$_pre;
	$query = $db->query("SELECT * FROM {$_pre}pic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		if($rs[imgurl]){
			$rs[imgurl]=tempdir($rs[imgurl]);
			$picdb[]=$rs;
		}		
	}
	return $picdb;
}

//获取主题赞
function get_this_digguser($id){
	global $db,$_pre,$webdb;
	$show="";
	$query = $db->query("SELECT * FROM {$_pre}digguser WHERE id='$id' ORDER BY did DESC");
	while($rs = $db->fetch_array($query)){
		$rs[icon]=get_member_icon($rs[uid]);
		$show.="<div><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'><img src='$rs[icon]' onerror=\"this.src='$webdb[www_url]/images/default/nobody.gif'\"/></A></div>";		
	}
	return $show;
}

//web获取主题赞
function get_this_digguser1($id){
	global $db,$_pre,$webdb;
	$show="";
	$query = $db->query("SELECT * FROM {$_pre}digguser WHERE id='$id' ORDER BY did DESC");
	while($rs = $db->fetch_array($query)){
		$memberinfo=get_members_baseInfo($rs[uid]);
		$show.="<div><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'><img src='$memberinfo[icon]' onerror=\"this.src='$webdb[www_url]/images/default/nobody.gif'\"/></A><span>$memberinfo[username]</span></div>";		
	}
	return $show;
}

//获取主题的评论
function get_this_comments($id,$num){
	global $db,$_pre,$webdb,$lfjuid;
	$show="";
	$query = $db->query("SELECT * FROM {$_pre}comments WHERE id='$id' AND cids='0' ORDER BY cid DESC LIMIT $num");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=format_showtime($rs[posttime]);
		$rs[icon]=get_member_icon($rs[uid]);
		$delword=($rs[uid]==$lfjuid)?"<div class='delcomment' onClick='del_comments($id,$rs[cid],$rs[uid])'><span>删除</span></div>\r\n":"";
		$listrepeatcomment=get_comment_repeat($rs[cid],$num);
		$show.="<div class='Thecomment Thecomment$rs[cid]'>\r\n";
		$show.="<ul>\r\n";
		$show.="<ol><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'><img src='$rs[icon]' onerror=\"this.src='$webdb[www_url]/images/default/nobody.gif'\"/></A></ol>\r\n";
		$show.="<li><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'>$rs[username]</A><span>$rs[posttime]</span></li>\r\n";
		$show.="</ul>\r\n";
		$show.="<div class='listcont'>\r\n$rs[content]\r\n<div class='repeat' onClick='add_comments($id,$rs[cid])'><span>回复</span></div>\r\n{$delword}</div>\r\n";
		$show.="<div class='Listrepeatcomment Listrepeatcomment$rs[cid]'>$listrepeatcomment</div>\r\n";
		$show.="</div>\r\n";
	}
	return $show;
}

//获取评论的回复
function get_comment_repeat($cid,$num){
	global $db,$_pre,$webdb,$lfjuid;
	$show="";
	if($num>0){
		$Limits="LIMIT $num";
	}
	$query = $db->query("SELECT * FROM {$_pre}comments WHERE cids='$cid' ORDER BY cid DESC $Limits");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=format_showtime($rs[posttime]);
		$rs[icon]=get_member_icon($rs[uid]);
		$delword=($rs[uid]==$lfjuid)?"<div class='delcomment1' onClick='del_comments1($rs[cids],$rs[cid],$rs[uid])'><span>删除</span></div>\r\n":"";
		$show.="<div class='listrepeat'><div class='img'><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'><img src='$rs[icon]' onerror=\"this.src='$webdb[www_url]/images/default/nobody.gif'\"/></A></div><A HREF='$webdb[www_url]/member/waphomepage.php?uid=$rs[uid]'><span class='name'>$rs[username]</span></A><span class='cont'>$rs[content]</span><span class='time'>$rs[posttime]</span>$delword</div>\r\n";
	}
	$show.="<div class='ShowMoreRepaly'></div>\r\n";
	return $show;
}
?>