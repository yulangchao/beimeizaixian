<?php
!function_exists('html') && exit('ERR');

if($job=="list"&&$Apower[weixin])
{
	!$page&&$page=1;
	$rows=200;
	$min=($page-1)*$rows;
	
	$showpage=getpage("{$pre}weixinword","","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query(" SELECT * FROM {$pre}weixinword ORDER BY list DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[answer]=get_word($rs[answer],80);
		$listdb[]=$rs;
	}
	$kill_badword[intval($webdb[kill_badword])]=' checked ';

	hack_admin_tpl('list');
}
elseif($action=="set"&&$Apower[weixin])
{
	write_config_cache($webdbs);
	jump("���óɹ�","index.php?lfj=weixin&job=list&",1);
}
elseif($action=="delete"&&$Apower[weixin])
{
	if(!$iddb){
		showmsg("��ѡ��һ��");
	}
	foreach($iddb AS $key=>$rs){
		$db->query("DELETE FROM {$pre}weixinword WHERE id='$key'");
	}
	jump("ɾ���ɹ�","index.php?lfj=weixin&job=list&",1);
}
elseif($action=="addword"&&$Apower[weixin])
{
	if(!$ask){
		showmsg("�ؼ��ʲ���Ϊ��");
	}elseif(!$answer){
		showmsg("�ظ����ݲ���Ϊ��");
	}
	$db->query("INSERT INTO {$pre}weixinword SET ask='$ask',answer='$answer' ");
	write_weixinword_cache();
	jump("��ӳɹ�","index.php?lfj=weixin&job=list&",1);
}
elseif($action=="edit"&&$Apower[weixin])
{
	$db->query("UPDATE {$pre}weixinword SET ask='$ask',answer='$answer',list='$list' WHERE id='$id' ");
	write_weixinword_cache();
	jump("�޸ĳɹ�","index.php?lfj=weixin&job=list&",1);
}
elseif($job=="edit"&&$Apower[weixin])
{
	$rsdb=$db->get_one(" SELECT * FROM {$pre}weixinword WHERE id='$id' ");
	
	hack_admin_tpl('edit');
}

function write_weixinword_cache(){
	global $db,$pre;
	$query=$db->query(" SELECT * FROM {$pre}weixinword ORDER BY list DESC ");
	while($rs=$db->fetch_array($query)){
		$rs[ask] = str_replace('��',' ',$rs[ask]);
		$detail = explode(' ',$rs[ask]);
		foreach($detail AS $key=>$value){
			if($value){
				$array[$value]=$rs[id];
			}
		}		
	}
	write_file(ROOT_PATH.'data/weixin.php',"<?php\r\n return ".var_export($array,true).';?>');
}
?>