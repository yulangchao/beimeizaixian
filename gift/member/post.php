<?php
require_once(dirname(__FILE__)."/global.php");

/**
*��ȡ��Ŀ�����ļ�
**/
$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid'");

if(!$fidDB){
	showerr('��Ŀ�����ڣ�');
}



/**
*ģ�Ͳ��������ļ�
**/
$field_db = $module_DB[$fidDB[mid]][field];

if(!$lfjuid){
	showerr('�㻹û�е�¼!');
}elseif($fidDB[type]){
	showerr("�����,��������������");
}


if($action=="postnew"||$action=="edit"){
	$postdb['title']=filtrate($postdb['title']);
}

/**�����ύ���·�������**/
if($action=="postnew")
{
	if(!$web_admin){
		if($groupdb[post_gift_num]<1){
			showerr('�������û��鲻��������������Ϣ,�������û����');
		}
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}content` WHERE uid='$lfjuid'");
		if($_rs[NUM]>$groupdb[post_gift_num]){
			showerr("�������û��鷢������Ʒ��Ϣ���ܳ���{$groupdb[post_gift_num]}��,�������û����");
		}
	}

	if(!$postdb[title]){
		showerr("���ⲻ��Ϊ��");
	}elseif(strlen($postdb[title])>80){
		showerr("���ⲻ�ܴ���40������.");
	}
	if(eregi("[a-z0-9]{15,}",$postdb[title])){
		showerr("������д�ñ���!");
	}

	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	$Module_db->checkpost($field_db,$postdb,'');


	$postdb[ispic]=$postdb[picurl]?1:0;



	$postdb[yz]=1;

	/*���������������*/
	$db->query("INSERT INTO `{$_pre}content` (`title` , `mid` , `fid` , `fname` , `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` ,  `keywords` , `ip` ,  `money` ) VALUES  ('$postdb[title]','$fidDB[mid]','$fid','$fidDB[name]','$timestamp','$timestamp','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$onlineip','$postdb[money]')");

	$id = $db->insert_id();

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	/*������Ϣ����������*/
	$db->query("INSERT INTO `{$_pre}content_$fidDB[mid]` SET $sql");

	set_user_log(4);	//�û�������־

	refreshto("list.php?job=list","<a href='list.php?job=list'>�����б�</a> <a target='_blank' href='../bencandy.php?fid=$fid&id=$id'>�鿴Ч��</a>",600);

}

/*ɾ������,ֱ��ɾ��,������*/
elseif($action=="del")
{

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid])
	{
		showerr("��Ŀ������");
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("��ûȨ��!");
	}

	del_info($id,$rsdb);


	refreshto("list.php?job=list","ɾ���ɹ�");
}

/*�༭����*/
elseif($job=="edit")
{

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin){	
		showerr('��ûȨ��!');
	}
	
	/*����Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";


	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$fidDB[mid]"));
	require(ROOT_PATH."member/foot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ�޸�");
	}

	if(!$postdb[title]){
		showerr("���ⲻ��Ϊ��");
	}

	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	$Module_db->checkpost($field_db,$postdb,$rsdb);


	/*�ж��Ƿ�ΪͼƬ����*/
	$postdb[ispic]=$postdb[picurl]?1:0;


	/*��������Ϣ������*/
	$db->query("UPDATE `{$_pre}content` SET title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',money='$postdb[money]' WHERE id='$id'");



	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	$db->query("UPDATE `{$_pre}content_$fidDB[mid]` SET $sql WHERE id='$id'");

	set_user_log(5);	//�û�������־


	refreshto("list.php?job=list","<a href='list.php?job=list'>�����б�</a> <a target='_blank' href='../bencandy.php?fid=$fid&id=$id'>�鿴Ч��</a>",600);
}
else
{
	/*ģ������ʱ,��Щ�ֶ���Ĭ��ֵ*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]!=''){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*����Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="postnew";

	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$fidDB[mid]"));
	require(ROOT_PATH."member/foot.php");
}

?>