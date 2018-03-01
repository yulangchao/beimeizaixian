<?php
function_exists('html') OR exit('ERR');
require_once(Mpath.'inc/function.php');
ck_power('list');
$linkdb=array(
			"ȫ����Ϣ"=>"$admin_path&job=list",
			"����˵���Ϣ"=>"$admin_path&job=list&type=yz&fid=$fid&cityid=$cityid",
			"δ��˵���Ϣ"=>"$admin_path&job=list&type=unyz&fid=$fid&cityid=$cityid",
			"�Ƽ���Ϣ"=>"$admin_path&job=list&type=levels&fid=$fid&cityid=$cityid",
			"������Ϣ"=>"$admin_path&job=list&type=del&fid=$fid&cityid=$cityid",
			);

$fid=intval($fid);

if($job=="list")
{
	$SQL=" 1 ";
	if($fid>0){
		$SQL.=" AND fid=$fid ";
	}
	
	if($type=="yz"){
		$SQL.=" AND yz=1 ";
	}
	elseif($type=="unyz"){
		$SQL.=" AND yz=0 ";
	}
	elseif($type=="del"){
		$SQL.=" AND yz=2 ";
	}
	elseif($type=="levels"){
		$SQL.=" AND levels=1 ";
	}
	elseif($type=="unlevels"){
		$SQL.=" AND levels=0 ";
	}
	elseif($type=="pic"){
		$SQL.=" AND ispic=1 ";
	}
	elseif($type=="my"){
		$SQL.=" AND uid='$userdb[uid]' ";
	}
	elseif($type=="title"){
		$SQL.=" AND binary title LIKE '%$keyword%' ";
	}
	elseif($type=="id"){
		$SQL.=" AND id='$keyword' ";
	}
	elseif($type=="content"){
		$SQL.=" AND binary content LIKE '%$keyword%' ";
	}
	elseif($type=="username"){
		$SQL.=" AND binary username LIKE '%$keyword%' ";
	}

	if($cityid){
		$SQL.=" AND city_id=$cityid ";
	}

	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$order="list";
	$desc="DESC";
	$showpage=getpage("{$_pre}content","WHERE $SQL","$admin_path&job=list&fid=$fid&type=$type&cityid=$cityid&keyword=".urlencode($keyword),$rows,"");
	//$sort_fid=$Guidedb->Select("{$_pre}sort","fid",$fid,"?lfj=$lfj&job=list");
	$query=$db->query("SELECT * FROM {$_pre}content WHERE $SQL ORDER BY $order $desc LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		if(!$rs[yz]){
			$rs[ischeck]="<A HREF='$admin_path&action=work&id=$rs[id]&jobs=yz' style='color:black;'>δ���</A>";
		}elseif($rs[yz]==1){
			$rs[ischeck]="<A HREF='$admin_path&action=work&id=$rs[id]&jobs=unyz' style='color:blue;'>�����</A>";
		}elseif($rs[yz]==2){
			$rs[ischeck]="<A HREF='$admin_path&action=work&id=$rs[id]&jobs=yz' style='color:red;'>����</A>";
		}
		if(!$rs[levels]){
			$rs[iscom]="<A HREF='$admin_path&action=work&id=$rs[id]&jobs=com&levels=1' style=''>δ�Ƽ�</A>";
		}else{
			$rs[iscom]="<A HREF='$admin_path&action=work&id=$rs[id]&jobs=com&levels=0' style='color:red;'>���Ƽ�</A>";
		}
		$rs[pages]<1 && $rs[pages]=1;
		$rs[title2]=urlencode($rs[title]);
		$rs[posttime]=date("m-d H:i",$rs[posttime]);
		$listdb[$rs[id]]=$rs;
	}

	get_admin_html('list');
}
elseif($job=="work")
{
	if(!$listdb){
		showmsg("��ѡ��һƪ����");
	}
	if($jobs=="move"){
		$sort_fid=$Guidedb->Select("{$_pre}sort","fid");
	}elseif($jobs=='movesp'){
		$select_spfid=$Guidedb->Checkbox("{$_pre}spsort",'spfiddb[]',$spfiddb,$fup=0,$w='100%',$h='100px');
	}

	get_admin_html('work');
}
elseif($action=="work")
{
	if(!$listdb&&!$id){
		showmsg("��ѡ��һ��");
	}
	elseif(is_array($listdb))
	{
		foreach($listdb AS $key=>$value){
			dowork($key,$jobs);
		}
	}
	elseif($id){
		dowork($id,$jobs);
	}
	$url=$fromurl?$fromurl:$FROMURL;
	refreshto($url,"�����ɹ�",1);
}


function dowork($id,$job){
	global $db,$_pre,$timestamp,$userdb,$webdb;
	$rsdb=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id' ");
	$array = array('title'=>$rsdb['title'],'fid'=>$rsdb['fid'],'id'=>$rsdb['id']);
	if($job=="delete")
	{
		$rsdb[picurl]=tempdir($rsdb[picurl]);
		delete_attachment($rsdb[uid],$rsdb[picurl]);
		$ts = $db->get_one("SELECT * FROM {$_pre}content_1 WHERE id='$id' ");
		delete_attachment($ts[uid],$ts[content]);
		$db->query("DELETE FROM {$_pre}content WHERE id='$id' ");
		$db->query("DELETE FROM {$_pre}content_1 WHERE id='$id' ");
		$db->query("DELETE FROM {$_pre}comments WHERE id='$id' ");

		Give_News_money($rsdb[uid],'del',$array);
	}
	elseif($job=="move")
	{
		global $fid;
		if($fid){
			$rs=$db->get_one("SELECT name,mid FROM {$_pre}sort WHERE fid='$fid'");
			//if($rs[mid]==$rsdb[mid]){
				$db->query("UPDATE {$_pre}content SET fid='$fid',fname='$rs[name]',lastfid='$rsdb[fid]' WHERE id='$id' ");
				$db->query("UPDATE {$_pre}content_1 SET fid='$fid' WHERE id='$id' ");
				//$db->query("UPDATE `{$_pre}sort` SET contents=contents-1 WHERE fid='$rsdb[fid]'");
				//$db->query("UPDATE `{$_pre}sort` SET contents=contents+1 WHERE fid='$fid'");
			//}
		}
	}
	
	elseif($job=="color")
	{
		global $color;
		$db->query("UPDATE {$_pre}content SET titlecolor='$color' WHERE id='$id' ");
	}
	elseif($job=="yz")
	{
		$db->query("UPDATE {$_pre}content SET yz='1',yzer='$userdb[username]',yztime='$timestamp' WHERE id='$id' ");
		Give_News_money($rsdb[uid],'yz',$array);
	}
	elseif($job=="unyz")
	{
		$db->query("UPDATE {$_pre}content SET yz='0',yzer='$userdb[username]',yztime='$timestamp' WHERE id='$id' ");
	}
	elseif($job=="com")
	{
		global $levels;
		if($levels==1){
			$SQL=",yz=1";
		}
		$db->query("UPDATE {$_pre}content SET levels='$levels',levelstime='$timestamp'$SQL WHERE id='$id' ");
		Give_News_money($rsdb[uid],'com',$array);
	}
	elseif($job=="uncom")
	{
		$db->query("UPDATE {$_pre}content SET levels='0',levelstime='0' WHERE id='$id' ");
	}
	elseif($job=="top")
	{
		global $toptime;
		$db->query("UPDATE {$_pre}content SET list='$timestamp'+'$toptime' WHERE id='$id' ");
		Give_News_money($rsdb[uid],'top',$array);
	}
	elseif($job=="untop")
	{
		$db->query("UPDATE {$_pre}content SET list=posttime WHERE id='$id' ");
	}
	elseif($job=="front")
	{
		global $topid;
		if($topid)
		{
			$rs=$db->get_one("SELECT list FROM {$_pre}content WHERE id='$topid' ");
			$list=$rs["list"]+1;
			$db->query("UPDATE {$_pre}content SET list='$list' WHERE id='$id' ");
		}
		else
		{
			$db->query("UPDATE {$_pre}content SET list='$timestamp' WHERE id='$id' ");
		}
		Give_News_money($rsdb[uid],'front',$array);
	}
	elseif($job=="bottom")
	{
		global $bottomid;
		if($bottomid)
		{
			$rs=$db->get_one("SELECT list FROM {$_pre}content WHERE id='$bottomid' ");
			$list=$rs["list"]-1;
			$db->query("UPDATE {$_pre}content SET list='$list' WHERE id='$id' ");
		}
		else
		{
			$db->query("UPDATE {$_pre}content SET list='0' WHERE id='$id' ");
		}
	}
}

?>