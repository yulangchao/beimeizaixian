<?php
require(dirname(__FILE__)."/"."global.php");
@include(dirname(__FILE__)."/"."data{$webdb[web_dir]}/guide_fid.php");
require(ROOT_PATH."inc/hongbao.function.php");

//分享成功后
if( $lfjuid && ($job=='have_shareTimeline'||$job=='have_shareFriend') ){

	if(weixin_hongbao_putIn(2)){
		$Msg='恭喜你，红包已经放入你的帐户里，请注意查收！';
	}else{
		$Msg='一天内重复分享，不派发红包';
	}	
	//refreshto("alonepage.php?id=$id",$Msg,1);
}

if($lfjuid&&!$introducer_uid){	//分销系统宣传的UID
	header("location:$WEBURL&introducer_uid=$lfjuid");exit;
}elseif($introducer_uid && $introducer_uid!=$lfjuid){
	set_cookie('IntroducerUid',$introducer_uid,24*3600);	//后面注册要记录用到
}

$id=intval($id);

/**
*获取栏目与模块配置参数
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("栏目不存在");
}

$db->query("UPDATE {$_pre}content SET hits=hits+1,lastview='$timestamp' WHERE id=$id");



/**
*获取信息正文的内容
**/
if($page>0){
	$page=intval($page);
	$min=$page-1;
	$SQL="A.id='$id' ORDER BY orderid ASC,rid ASC LIMIT $min,1";
}elseif($rid){
	$SQL="A.id='$id' AND B.rid='$rid'";
}else{
	$SQL="A.id='$id' ORDER BY rid ASC LIMIT 1";
}
$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE $SQL");
if(!$rsdb){
	showerr("内容不存在");
}elseif($fid!=$rsdb[fid]){
	showerr("FID有误,不一致");
}

if(!$jobs && $rsdb[city_id]!=$city_id){
	if($city_DB[domain][$rsdb[city_id]]){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL={$city_DB[domain][$rsdb[city_id]]}/{$ModuleDB[$webdb[module_pre]][dirname]}/bencandy.php?fid=$fid&id=$id'>";
		exit;
	}
}


//SEO
$titleDB[title]		= filtrate("$rsdb[title]");
$titleDB[keywords]	= filtrate("$rsdb[keywords]");
$rsdb[description] || $rsdb[description] = get_word(str_replace(array(' ','　',"\t","\n","\r"),array(''),preg_replace('/<([^<]*)>/is',"",$rsdb[content])),200);
$titleDB[description] = filtrate($rsdb[description]);


$rsdb[content]=@preg_replace('/<img([^>]*)src="([^"]*)"([^>]*)>/is',"<img onclick='window.open(this.src)' src='\\2'/>",$rsdb[content]);	//把宽度代码过滤掉


/**
*文章检查
**/
check_article($rsdb);

$rsdb[content]=En_TruePath($rsdb[content],0,1);

$rsdb[posttime]=format_showtime($rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);

//内容页个性头部与尾部
get_show_tpl($head_tpl,$foot_tpl,$main_tpl);

//获取标签内容
$template_file=getTpl("wapbencandy",$main_tpl);
fetch_label_value(array('pagetype'=>'3','file'=>$template_file,'module'=>$webdb['module_id']));

$showpage=getpage("","","bencandy.php?fid=$fid&id=$id",1,$rsdb[pages]);

if($rsdb[iframeurl])
{
	$head_tpl=$foot_tpl="template/default/none.htm";
	$template_file="template/default/bencandy_iframe.htm";
}

$rsdb[content]=show_keyword($rsdb[content]);	//突出显示关键字

/**
*上一篇与下一篇,比较影响速度
**/
$nextdb=$db->get_one("SELECT title,id,fid FROM {$_pre}content WHERE id<'$id' AND fid='$fid' AND yz=1 ORDER BY id DESC LIMIT 1");
$nextdb[subject]=get_word($nextdb[title],34);
$backdb=$db->get_one("SELECT title,id,fid FROM {$_pre}content WHERE id>'$id' AND fid='$fid' AND yz=1 ORDER BY id ASC LIMIT 1");
$backdb[subject]=get_word($backdb[title],34);


$showpage = $rsdb[pages]>1?getpage('','',"bencandy.php?fid=$fid&id=$id",1,$rsdb[pages]):'';

if($showpage && !strstr(read_file($template_file),'$showpage')){	//旧模板中没有分页参数的话，就自动补上
	$rsdb[content].="<center>$showpage</center>";
}

$plistdb='';
$rows=5;
$pages||$pages=1;
$min=($pages-1)*$rows;
$sowComments="";
$query = $db->query("SELECT * FROM {$_pre}comments WHERE id='$id' AND cids='0' ORDER BY cid DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[posttime]=format_showtime($rs[posttime]);
	$rs[icon]=get_member_icon($rs[uid]);
	$listrepeatcomment=get_comment_repeat($rs[cid],$rows);
	$delword=($rs[uid]==$lfjuid)?"<div class='delcomment' onClick='del_comments($id,$rs[cid],$rs[uid])'><span>删除</span></div>\r\n":"";
	$show="";
	$show.="<div class='Thecomment Thecomment$rs[cid]'>\r\n";
	$show.="<ul>\r\n";
	$show.="<ol><A HREF='$webdb[www_url]/member/userinfo.php?uid=$rs[uid]'><img src='$rs[icon]'/></A></ol>\r\n";
	$show.="<li><A HREF='$webdb[www_url]/member/userinfo.php?uid=$rs[uid]'>$rs[username]</A><span>$rs[posttime]</span></li>\r\n";
	$show.="</ul>\r\n";
	$show.="<div class='listcont'>\r\n$rs[content]\r\n<div class='repeat' onClick='add_comments($id,$rs[cid])'><span>回复</span></div>\r\n{$delword}</div>\r\n";
	$show.="<div class='Listrepeatcomment Listrepeatcomment$rs[cid]' title='$rs[cid]'>\r\n{$listrepeatcomment}\r\n</div>\r\n";
	$show.="</div>\r\n";
	$sowComments.=$show;
	$ra[htmls]=$show;
	$plistdb[]=$ra;
}
if($type=="date"){
	require(ROOT_PATH."inc/class.json.php");
	foreach($plistdb AS $key=>$rs){
		if(WEB_LANG!='utf-8'){
			$rs[htmls]=gbk2utf8($rs[htmls]);
		}
		$listdba[]=$rs;
	}	
	echo json_encode($listdba);
	exit;
}

//监控微信分享事件
unset($jssdk,$signPackage);
if(in_weixin()){
	require(ROOT_PATH."inc/weixin.jsdk.php");
	$jssdk = new JSSDK($webdb[wxpay_AppID], $webdb[wxpay_AppSecret]);
	$signPackage = $jssdk->GetSignPackage();
}

require(ROOT_PATH."inc/waphead.php");
require($template_file);
//require(ROOT_PATH."inc/wapfoot.php");


function show_keyword($content){
	global $Key_word,$webdb,$pre,$db;
	if(!$webdb[ifShowKeyword]){
		return $content;
	}
	@include(Mpath."data{$webdb[web_dir]}/keyword.php");
	//把图片描述去掉
	//$content=preg_replace("/ alt=([^ >]+)/is","",$content);
	foreach( $Key_word AS $key=>$value){
		if(!$value){
			$value="$webdb[www_url]/news/search.php?type=title&action=search&keyword=".urlencode($key);
		}
		//$rs = $db->get_one("SELECT num FROM {$pre}keyword WHERE `keywords` = '{$key}'");
		//$content=str_replace_limit($key,"<a href=$value style=text-decoration:underline;font-size:14px;color:{$webdb[ShowKeywordColor]}; target=_blank>$key</a>",$content,$rs[num]);
		//$content=str_replace_limit($key,"<a href=$value style=text-decoration:underline;font-size:14px;color:{$webdb[ShowKeywordColor]}; target=_blank>$key</a>",$content,2);	//重复的关键字只能显示两次

		$search[]=$key;
		$replace[]="<a href=$value style=text-decoration:underline;font-size:14px;color:{$webdb[ShowKeywordColor]}; target=_blank>$key</a>";
	}
	$search && $content=str_replace_limit($search,$replace,$content,1);
	return $content;
}

function str_replace_limit($search, $replace, $subject, $limit=-1) {
    if (is_array($search)) {
         foreach ($search as $k=>$v) {
             $search[$k] = "/(?!<[^>]+)".preg_quote($search[$k],'/')."(?![^<]*>)/";
        }
    }else{
         $search = "/(?!<[^>]+)".preg_quote($search,'/')."(?![^<]*>)/";
    }
    return preg_replace($search, $replace, $subject, $limit);
}

/**
*文章检查
**/
function check_article($rsdb){
	global $fidDB,$web_admin,$groupdb,$timestamp,$lfjid,$lfjuid,$fid,$id,$buy,$lfjdb,$webdb,$pre,$_pre,$db;
	
	if($lfjid&&($web_admin||$lfjid==$rsdb[uid]||in_array($lfjid,explode(",",$fidDB[admin]))))
	{
		$power=1;
	}
	if(!$rsdb)
	{
		showerr("内容不存在");
	}
	if( $fidDB[allowviewcontent]&&!$power&&!in_array($groupdb[gid],explode(",",$fidDB[allowviewcontent])) )
	{
		showerr("本栏目设置,你所在用户组不允许浏览内容");
	}

	if( $rsdb[allowview]&&!$power&&!in_array($groupdb[gid],explode(",",$rsdb[allowview])) )
	{
		showerr("本文设置,你所在用户组不允许浏览内容");
	}

	//设置了开始浏览日期限制
	if($rsdb[begintime]&&$timestamp<$rsdb[begintime]&&!$power)
	{
		$rsdb[begintime]=date("Y-m-d H:i:s",$rsdb[begintime]);
		showerr("<font color='red' ><u>很抱歉,作者设置了本文内容只有到了“{$rsdb[begintime]}”那个时间才可以查看</u></font>");
	}

	//设置了失效浏览日期限制
	if($rsdb[endtime]&&$timestamp>$rsdb[endtime]&&!$power)
	{
		$rsdb[endtime]=date("Y-m-d H:i:s",$rsdb[endtime]);
		showerr("<font color='red' ><u>很抱歉,发布者设置了本文内容最后查看期限是“{$rsdb[endtime]}”，现在已超过了这个期限，所以不能查看</u></font>");
	}

	if($rsdb[yz]==2&&!$web_admin)
	{
		showerr("回收站的内容只有管理员才可以查看");
	}
	//未审核
	if(!$rsdb[yz]&&!$webdb[viewNoPassArticle]&&!$power)
	{
		showerr("<font color='red' ><u>很抱歉,本文还没通过验证,你不能查看</u></font>");
	}

	//跳转到外面
	if($rsdb[jumpurl])
	{
		echo "页面正在跳转中，请稍候...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$rsdb[jumpurl]'>";
		exit;
	}

	//文章密码
	if($rsdb[passwd])
	{
		if( $_POST[password] && $_POST[TYPE] == 'article'  )
		{
			if( $_POST[password] != $rsdb[passwd] )
			{
				echo "<A HREF=\"?fid=$fid&id=$id\">密码不正确,点击返回</A>";
				exit;
			}
			else
			{
				setcookie("article_passwd_$_pre$id",$rsdb[passwd]);
				$_COOKIE["article_passwd_$_pre$id"]=$rsdb[passwd];
			}
		}
		if( $_COOKIE["article_passwd_$_pre$id"] != $rsdb[passwd] )
		{
			echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">请输入文章密码:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"article\"><input type=\"submit\" name=\"Submit\" value=\"提交\"></form></CENTER>";
			exit;
		}
	}

	//栏目密码
	if($fidDB[passwd])
	{
		if( $_POST[password] && $_POST[TYPE] == 'sort' )
		{
			if( $_POST[password] != $fidDB[passwd] )
			{
				echo "<A HREF=\"?fid=$fid&aid=$aid\">密码不正确,点击返回</A>";
				exit;
			}
			else
			{
				setcookie("sort_passwd_$_pre$fid",$fidDB[passwd]);
				$_COOKIE["sort_passwd_$_pre$fid"]=$fidDB[passwd];
			}
		}
		if( $_COOKIE["sort_passwd_$_pre$fid"] != $fidDB[passwd] )
		{
			echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">请输入栏目密码:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"sort\"><input type=\"submit\" name=\"Submit\" value=\"提交\"></form></CENTER>";
			exit;
		}
	}

	//积分处理
	if( !$power && $rsdb[money]=abs($rsdb[money])){
		if(!$lfjuid)
		{
			showerr("请先登录,需要支付{$rsdb[money]}{$webdb[MoneyName]}才能查看");
		}
		elseif(!strstr($rsdb[buyuser],",$lfjid,"))
		{
			$lfjdb[money]=get_money($lfjuid);
			if($lfjdb[money]<$rsdb[money])
			{
				showerr("你的{$webdb[MoneyName]}不足$rsdb[money]");
			}
			elseif($buy==1)
			{
				add_user($lfjuid,"-$rsdb[money]",'查看资讯内容扣分');
				add_user($rsdb[uid],"$rsdb[money]",'用户查看我的资讯得分');
				$rsdb[buyuser]=$rsdb[buyuser]?",{$lfjid}{$rsdb[buyuser]}":",$lfjid,";
				$db->query("UPDATE {$_pre}content SET buyuser='$rsdb[buyuser]' WHERE id=$id");
				refreshto("?fid=$fid&id=$id","购买成功,你刚刚消耗了{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}",3);
			}
			else
			{
				showerr("你需要消耗{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}才有权限查看,是否继续<br><br>[<A HREF='?fid=$fid&id=$id&buy=1'>我要继续</A>]");
			}
		}
	}
}

?>