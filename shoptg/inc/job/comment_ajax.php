<?php
if(!function_exists('html')){
	die('F');
}

header('Content-Type: text/html; charset='.WEB_LANG);

/**
*�����û��ύ������
**/
if($action=="post")
{

	
	/*��֤�봦��*/
	if(!$web_admin&&!$groupdb[comment_img])
	{
		if(!check_imgnum($yzimg))
		{
			die("��֤�벻����,����ʧ��");
		}
	}

	if(!$content)
	{
		die("���ݲ���Ϊ��");
	}


	$web_admin && $groupdb[comment_num]<1 && $groupdb[comment_num]=10000;

	$groupdb[comment_num] = intval($groupdb[comment_num]);

	if($groupdb[comment_num]<1){
		die("�������û��鲻�ܷ�������");
	}else{
		$time=$timestamp-3600*24;
		if(!$lfjuid){
			$SQL="ip='$onlineip' AND posttime>$time";
		}else{
			$SQL="uid='$lfjuid' AND posttime>$time";
		}
		$_rt=$db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}comments WHERE $SQL");
		if($_rt[NUM]>$groupdb[comment_num]){
			die("�������û���ÿ�췢���������������ܴ��� $groupdb[comment_num] ��");
		}
	}
	
	

	/*�Ƿ����������Զ�ͨ������жϴ���*/
	
	$yz = $web_admin ? 1 : intval($groupdb[comment_yz]);


	$username=filtrate($username);
	$content=filtrate($content);

	$content=str_replace("@@br@@","<br>",$content);

	//���˲���������
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);

	//�������˶����������ʺ���������
	if($username)
	{
		$rs=$userDB->get_passport($username,'username');
		if($rs[uid]!=$lfjuid)
		{
			$username="����";
		}
	}
	
	$rss=$db->get_one(" SELECT * FROM {$_pre}content WHERE id='$id' ");
	if(!$rss){
		die("ԭ���ݲ�����");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;

		if(is_utf8($content)||is_utf8($username)){
			$content=utf82gbk($content);
			$username=utf82gbk($username);
		}
		if(WEB_LANG=='utf-8'){
			$content=gbk2utf8($content);
			$username=gbk2utf8($username);
		}elseif(WEB_LANG=='big5'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","BIG5",$content,ROOT_PATH."./inc/gbkcode/");
			$content = $cnvert->ConvertIT();

			$cnvert = new Chinese("GB2312","BIG5",$username,ROOT_PATH."./inc/gbkcode/");
			$username = $cnvert->ConvertIT();
		}

	$db->query("INSERT INTO `{$_pre}comments` (`cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz')");

	$db->query(" UPDATE {$_pre}content SET comments=comments+1 WHERE id='$id' ");
	set_user_log(6);	//�û�������־

}

/*ɾ������*/
elseif($action=="del")
{

	$rs=$db->get_one("SELECT * FROM `{$_pre}comments` WHERE cid='$cid'");
	if(!$lfjuid)
	{
		die("�㻹û��¼,��Ȩ��");
	}
	elseif(!$web_admin&&$rs[uid]!=$lfjuid&&$rs[cuid]!=$lfjuid)
	{
		die("��ûȨ��");
	}

	//ɾ������Ҫ�۳�����
	if(!$web_admin&&$rs[uid]!=$lfjuid&&$webdb[DelOtherCommentMoney]){
		$lfjdb[money]=get_money($lfjdb[uid]);
		if(abs($webdb[DelOtherCommentMoney])>$lfjdb[money]){
			die("���{$webdb[MoneyName]}����");
		}
		add_user($lfjdb[uid],-abs($webdb[DelOtherCommentMoney]),'ɾ���ٷ�����Ϣ���ۿ۷�');
	}

	$db->query(" DELETE FROM `{$_pre}comments` WHERE cid='$cid' ");
	$db->query("UPDATE {$_pre}content SET comments=comments-1 WHERE id='$rs[id]' ");
}
elseif($action=="flowers"||$action=="egg")
{
	if(get_cookie("{$action}_$cid")){
		echo "err<hr>";
	}else{
		set_cookie("{$action}_$cid",1,3600);
		$db->query("UPDATE `{$_pre}comments` SET `$action`=`$action`+1 WHERE cid='$cid'");
	}
}
/**
*�Ƿ�ֻ��ʾͨ����֤������,������ȫ����ʾ
**/
if(!$webdb[showNoPassComment])
{
	$SQL=" AND A.yz=1 ";
}
else
{
	$SQL="";
}

/**
*ÿҳ��ʾ��������
**/
$rows=$webdb[showCommentRows]?$webdb[showCommentRows]:8;

if($page<1)
{
	$page=1;
}
$min=($page-1)*$rows;


/*���������ٶ�Ҳֻ������ʾ1000����*/
$leng=1000;

$query=$db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.icon FROM `{$_pre}comments` A LEFT JOIN `{$pre}memberdata` B ON A.uid = B.uid WHERE A.id=$id $SQL ORDER BY cid DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while( $rs=$db->fetch_array($query) )
{
	if(!$rs[username]){
		$detail=explode(".",$rs[ip]);
		$rs[username]="$detail[0].$detail[1].$detail[2].*";
	}

	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);

	$rs[full_content]=$rs[content];

	$rs[content]=get_word($rs[content],$leng);

	if($rs[type]){
		$rs[content]="<img style='margin-top:3px;' src=$webdb[www_url]/images/default/good_ico.gif> ".$rs[content];
	}

	$rs[content]=str_replace("\n","<br>",$rs[content]);

	if($rs[icon]) $rs[icon] = tempdir($rs[icon]);

	$listdb[]=$rs;
}


$showpage=getpage('','',"job.php?job=comment_ajax&fid=$fid&id=$id",$rows,$totalNum);



require_once(getTpl('comment_ajax'));

?>