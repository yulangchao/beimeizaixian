<?php
require_once(dirname(__FILE__)."/global.php");

if(!table_field("{$_pre}company",'zone_id')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `zone_id` mediumint(7) NOT NULL default '0'");
}
if(!table_field("{$_pre}company",'street_id')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `street_id` mediumint(7) NOT NULL default '0'");
}
if(!table_field("{$_pre}company",'telphoto')){
	$db->query("ALTER TABLE `{$_pre}company` ADD `telphoto` varchar(50) NOT NULL default ''");
}

if(!$lfjid){
	showerr('���ȵ�¼!');
}
//elseif($webdb['postShopNeedQy']&&!$web_admin&&$lfjdb['grouptype']!=1){
	//showerr('ֻ��ͨ����˵���ҵ�û����ܷ�����Ʒ!');
//}

/**
*��ȡ��Ŀ�����ļ�

$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid'");

if(!$fidDB){
	showerr('��Ŀ����!');
}

$rs=$db->get_one("SELECT admin FROM {$pre}city WHERE fid='$_COOKIE[admin_cityid]'");
$detail=explode(',',$rs[admin]);
if($lfjid && in_array($lfjid,$detail)){
	$web_admin=1;
}
**/
/**
*ģ�Ͳ��������ļ�

$field_db = $module_DB[$fidDB[mid]][field];
$ifdp = $module_DB[$fidDB[mid]][ifdp];
$m_config[moduleSet][useMap] = $module_DB[$fidDB[mid]][config][moduleSet][useMap];

if($fidDB[type]){
	showerr("�����,������������");
}elseif( $fidDB[allowpost] && $action!="del" && in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
	showerr("�������û���,��Ȩ�ڱ���Ŀ����");
}
**/

/*ģ��
$FidTpl=unserialize($fidDB[template]);

$lfjdb[money]=$lfjdb[_money]=intval(get_money($lfjuid));
*/



if($action=="postnew"||$action=="edit"){
	
	$titledb = $_POST['titledb'];
	$postdb['title']=filtrate($postdb['title']);
	$postdb['price']=filtrate($postdb['price']);
	$postdb['keywords']=filtrate($postdb['keywords']);
	if(!$postdb[title]){
		showerr("�̼����Ʋ���Ϊ��");
	}elseif(strlen($postdb[title])>80){
		showerr("�̼����Ʋ��ܴ���40������.");
	}
	
	if($postdb[money]){
		if($postdb[money]<0){
			showerr("����{$webdb[MoneyName]}����С��0");
		}elseif(!is_numeric($postdb[money]) ){
			showerr("����{$webdb[MoneyName]}ֻ��������");
		}elseif(!$webdb[giveMoneyFromSystem] && $postdb[money]>get_money($lfjuid)){
			showerr("����{$webdb[MoneyName]}���ܴ����������");
		}
	}
	
	if( count($city_DB[name])==1 )$postdb[city_id]=$city_id;
}

if($job=='set'){

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.uid='$lfjuid' ORDER BY id DESC LIMIT 1");

	if(!$rsdb){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=post_company.php'>";
		exit;
	}else{
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$rsdb[fid]&id=$rsdb[id]'>";
		exit;
	}

}
elseif($action=="postnew")
{
	$ts = $db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
	if(!$web_admin && $ts){	//�ǹ���Ա���ܴ������
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$ts[fid]&id=$ts[id]'>";
		exit;
	}

	/*��֤�봦��*/
	if(!$web_admin&&$groupdb[postShopYzImg]){
		if(!check_imgnum($yzimg)){		
			showerr("��֤�벻����,����ʧ��");
		}
	}

	if(!$web_admin){
		if($groupdb[post_shop_num]<1){
			showerr('�������û��鲻��������Ʒ,�������û����');
		}
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE uid='$lfjuid'");
		if($_rs[NUM]>$groupdb[post_shop_num]){
			showerr("�������û��鷢������Ʒ��Ϣ���ܳ���{$groupdb[post_shop_num]}��,�������û����");
		}
	}

	if($postdb['storage']<1){
		//showerr("���������Ҫ���� 1");
	}

	$postdb['list']=$timestamp;
	if($iftop){		//�Ƽ��ö�
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("��ǰ��Ŀ�ö���Ϣ�Ѵﵽ����!");
		}
		$postdb['list']+=3600*24*$webdb[Info_TopDay];
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("���{$webdb[MoneyName]}����:$webdb[Info_TopMoney],����ѡ���ö�");
		}
		$lfjdb[money]=$lfjdb[money]-$webdb[Info_TopMoney];	//Ϊ�������жϻ����Ƿ��㹻
	}



	
	if(eregi("[a-z0-9]{15,}",$postdb[title])){
		showerr("������д�ñ���!");
	}
	if(eregi("[a-z0-9]{25,}",$postdb[content])){
		//showerr("��������д����!");
	}
	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	//$Module_db->checkpost($field_db,$postdb,'');

	//�ϴ�����ͼƬ
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		if($titledb[$key]>100){
			showerr("���ⲻ�ܴ���50������");
		}
		$num++;
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	$postdb[ispic]=$postdb[picurl]?1:0;

	//�Ƿ��Զ�ͨ�����
	$web_admin && $groupdb[shop_postauto_yz]=1;
	$postdb[yz] = $groupdb[shop_postauto_yz];

	if($postdb[yz]==1){
		//add_user($lfjdb[uid],$webdb[PostInfoMoney],'������Ʒ��������');
	}

	//�ö��۷�
	if($iftop){
		add_user($lfjuid,-intval($webdb[Info_TopMoney]),'������Ʒ�ö��ۻ���');
	}
	

	
	$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$postdb[fid]'");
	/*��������������*/
	$db->query("INSERT INTO `{$_pre}company` (`title` , `mid` ,`fid` , `fname` ,  `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` , `keywords` , `ip` , `money` , `city_id`, `zone_id`, `street_id`,`picnum`,`price`,`sendprice`,`arrive_time`,`address`,`ranges`,`maps`,`content`,`telphoto`) 
	VALUES (
	'$postdb[title]','$fidDB[mid]','$postdb[fid]','$fidDB[name]','$timestamp','$postdb[list]','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$onlineip','$postdb[money]','$postdb[city_id]','$postdb[zone_id]','$postdb[street_id]','$num','$postdb[price]','$postdb[sendprice]','$postdb[arrive_time]','$postdb[address]','$postdb[ranges]','$postdb[maps]','$postdb[content]','$postdb[telphoto]')");

	$id=$db->insert_id();

	//����ͼƬ
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=addslashes(filtrate($titledb[$key]));
		$value=filtrate($value);
		$db->query("INSERT INTO `{$_pre}companypic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
	}

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	/*������Ϣ���������*/
	//$db->query("INSERT INTO `{$_pre}company_$fidDB[mid]` SET $sql");
	
	set_user_log(4);	//�û�������־

	refreshto("?job=edit&fid=$fid&id=$id","<a target='_blank' href='../listhy.php?fid=$fid&id=$id'>�����б�</a> <a target='_blank' href='../showhy.php?city_id=$rsdb[city_id]&fid=$fid&id=$id'>�鿴Ч��</a>",600);

}

/*ɾ������,ֱ��ɾ��,������*/
elseif($action=="del")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid])
	{
		showerr("��Ŀ������");
	}
	
	elseif($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin])))
	{
		showerr("��ûȨ��!");
	}

	del_company_info($id,$rsdb);

	if($rsdb[yz]){
	//	add_user($lfjdb[uid],-$webdb[PostInfoMoney],'ɾ���̳���Ϣ�۷�');
	}
	
	refreshto($FROMURL,"ɾ���ɹ�");
}

/*�༭����*/
elseif($job=="edit")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){	
		showerr('��ûȨ��!');
	}
	
	/*��Ĭ�ϱ���������*/
	//$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";



	$rsdb['list']>$timestamp?($ifTop[1]=' checked '):($ifTop[0]=' checked ');

	$rsdb[price]==0 && $rsdb[price]='';
	
	$listdb = array();
	$query = $db->query("SELECT * FROM {$_pre}companypic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		$listdb[$rs[pid]]=$rs;
	}

	$city_id=$rsdb[city_id];
	$zone_id=$rsdb[zone_id];
	$street_id=$rsdb[street_id];

	$city_fid=select_where("{$pre}city","'postdb[city_id]'  onChange=\"choose_where('getzone',this.options[this.selectedIndex].value,'','1','')\"",$city_id);
	
	require(ROOT_PATH."member/head.php");
	require(getTpl("post_company"));
	require(ROOT_PATH."member/foot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}company` A WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){
		showerr("����Ȩ�޸�");
	}


	if($rsdb['yz']==2 && $postdb['storage']>1){
		$rsdb['yz'] = 1;
	}


	if($iftop&&$rsdb['list']<$timestamp){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}company` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("��ǰ��Ŀ�ö���Ϣ�Ѵﵽ����!");
		}
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("��Ļ��ֲ���:$webdb[Info_TopMoney],����ѡ���ö�");
		}
	}
	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	//$Module_db->checkpost($field_db,$postdb,$rsdb);

	//�ϴ�����ͼƬ
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){

		if(!$value&&$piddb[$key]){
			$db->query("DELETE FROM `{$_pre}companypic` WHERE pid='{$piddb[$key]}' AND id='$id'");
		}

		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=addslashes(filtrate($titledb[$key]));
		$value=filtrate($value);
		if($titledb[$key]>100){
			showerr("���ⲻ�ܴ���50������");
		}
		$num++;
		if($piddb[$key]){		
			$db->query("UPDATE `{$_pre}companypic` SET name='{$titledb[$key]}',imgurl='$value' WHERE pid='{$piddb[$key]}' AND id='$id'");
		}else{
			$db->query("INSERT INTO `{$_pre}companypic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
		}
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	/*�ж��Ƿ�ΪͼƬ����*/
	$postdb[ispic]=$postdb[picurl]?1:0;


	if($iftop){
		if($rsdb['list']<$timestamp){
			$list=$timestamp+3600*24*$webdb[Info_TopDay];
			$SQL=",list='$list'";
			add_user($lfjuid,-intval($webdb[Info_TopMoney]),'�ö��̳���Ϣ�۷�');
		}	
	}else{
		if($rsdb['list']>$timestamp){
			$SQL=",list='$rsdb[posttime]'";
		}
	}
	$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$postdb[fid]'");
	/*��������Ϣ������*/
	$db->query("UPDATE `{$_pre}company` SET fid='$postdb[fid]',fname='$fidDB[name]',title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',picnum='$num',price='$postdb[price]',city_id='$postdb[city_id]',zone_id='$postdb[zone_id]',street_id='$postdb[street_id]',yz='$rsdb[yz]',sendprice='$postdb[sendprice]',arrive_time='$postdb[arrive_time]',ranges='$postdb[ranges]',address='$postdb[address]',maps='$postdb[maps]',content='$postdb[content]',telphoto='$postdb[telphoto]' WHERE id='$id'");

	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	//$db->query("UPDATE `{$_pre}company_$fidDB[mid]` SET $sql WHERE id='$id'");

	set_user_log(5);	//�û�������־

	refreshto("?job=edit&fid=$fid&id=$id","<a target='_blank' href='../listhy.php?fid=$fid&id=$id'>�����б�</a> <a target='_blank' href='../showhy.php?city_id=$rsdb[city_id]&fid=$fid&id=$id'>�鿴Ч��</a>",600);
}
else
{
	$checkdb=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
	if(!$web_admin && $checkdb){	//�ǹ���Ա���ܴ������
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=edit&fid=$checkdb[fid]&id=$checkdb[id]'>";
		exit;
	}

	/*ģ������ʱ,��Щ�ֶ���Ĭ��ֵ*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]!=''){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*��Ĭ�ϱ���������*/
	//$Module_db->formGetVale($field_db,$rsdb);


	$atc="postnew";

	$ifTop[0]=' checked ';

	$listdb=array('');

	if(!$city_id){
		$city_id=$_COOKIE[city_id];
	}
	$city_fid=select_where("{$pre}city","'postdb[city_id]'  onChange=\"choose_where('getzone',this.options[this.selectedIndex].value,'','1','')\" ",$city_id);
	
	//$select_mysort = select_mysort($lfjuid,'postdb[myfid]');

	require(ROOT_PATH."member/head.php");
	require(getTpl("post_company"));
	require(ROOT_PATH."member/foot.php");
}

?>