<?php
require_once("global.php");
//require_once(Mpath."inc/join.inc.php");

if( !is_table("{$_pre}car") ){
	$db->query("CREATE TABLE `{$_pre}car` (
  `id` mediumint(7) NOT NULL auto_increment,
  `cid` mediumint(10) NOT NULL default '0',
  `joins` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=1");
}

if(($action||$job=="edit") && !$lfjuid){
	showerr('�㻹û�е�¼!!');
}

if($atc=='join' && $lfjuid && $cid){
	//$joins = $db->get_one("SELECT * FROM `{$_pre}car` WHERE uid='$lfjuid' AND cid='$cid'");
	$query = $db->query("SELECT * FROM `{$_pre}car` WHERE uid='$lfjuid' AND cid='$cid' ORDER BY id DESC");
	$i=0;
	while($rs = $db->fetch_array($query)){
		$i++;
		if($i>1){
			$db->query("DELETE FROM `{$_pre}car` WHERE id='$rs[id]'");
		}
	}
	if($i>0){
		$db->query("UPDATE {$_pre}car SET joins='$num',type='$type' WHERE uid='$lfjuid' AND cid='$cid'");
	}else{
		$db->query("INSERT INTO `{$_pre}car` ( `cid` , `joins` , `uid` , `type`) VALUES ( '$cid', '$num', '$lfjuid', '$type')");
	}
	exit;
}
if($atc=='deljoin' && $lfjuid && $cid){
	$db->query("DELETE FROM `{$_pre}car` WHERE uid='$lfjuid' AND cid='$cid'");
	exit;
}

$mid=2;

/**
*ģ����������ļ�
**/
$field_db = $module_DB[$mid][field];


/**�����ύ���·�������**/
if($action=="postnew"){
	$query = $db->query("SELECT A.joins,A.type,B.* FROM {$_pre}car A LEFT JOIN `{$_pre}content` B ON A.Cid=B.id WHERE A.uid='$lfjuid' AND A.type=1 ORDER BY A.id DESC");
	while($rs = $db->fetch_array($query)){
		$rs[price] = str_replace(',','',$rs[price]);
		$rs[picurl] = tempdir($rs[picurl]);
		$listdb[$rs[username]][] = $rs;
	}
	if(!$listdb){
		showerr('��û��ѡ����Ʒ���޷�����!!');
	}
	if($postdb[address_type]>0){//ʹ�þɵ�ַ
		$ts = $db->get_one("SELECT * FROM `{$_pre}address` WHERE uid='$lfjuid' AND rid='$postdb[address_type]'");
		$postdb[order_username] = addslashes($ts[order_username]);
		$postdb[order_phone] = addslashes($ts[order_phone]);
		$postdb[order_mobphone] = addslashes($ts[order_mobphone]);
		$postdb[order_email] = addslashes($ts[order_email]);
		$postdb[order_qq] = addslashes($ts[order_qq]);
		$postdb[order_postcode] = addslashes($ts[order_postcode]);
		$postdb[order_address] = addslashes($ts[order_address]);
	}elseif($postdb[address_type]=='-1'){	//�µ�ַҪ��⴦��
		$db->query("INSERT INTO `{$_pre}address` ( `uid` , `order_username` , `order_phone` , `order_mobphone` , `order_email` , `order_qq` , `order_postcode` , `order_address` ) VALUES ('$lfjuid' , '$postdb[order_username]' , '$postdb[order_phone]' , '$postdb[order_mobphone]' , '$postdb[order_email]' , '$postdb[order_qq]' , '$postdb[order_postcode]' , '$postdb[order_address]')");
	}
	//�Զ����ֶεĺϷ���������ݴ���
	$Module_db->checkpost($field_db,$postdb,'');
	
	unset($id_array);
	foreach($listdb AS $uid=>$array){
		$totalmoney = 0;
		$detail='';
		foreach($array AS $_rs){
			$totalmoney+=floatval($_rs[price])*floatval($_rs[joins]);
			$detail[] = "$_rs[id]={$_rs[joins]}";
		}
		$products = addslashes( implode(',',$detail) );

		$rs=$db->get_one("SELECT * FROM `{$pre}purse` WHERE uid='$uid'");
		$parray=unserialize($rs[config]);
		
		if($postdb[order_sendtype]==2){			//ƽ��
			$totalmoney+=floatval($parray[slow_send]);
		}elseif($postdb[order_sendtype]==3){	//���
			$totalmoney+=floatval($parray[norm_send]);
		}elseif($postdb[order_sendtype]==4){	//EMS
			$totalmoney+=floatval($parray[ems_send]);
		}
		$infodb = $db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
		//$totalmoney = number_format($totalmoney,2);
		/*������Ϣ���������*/
		$db->query("INSERT INTO `{$_pre}join` ( `mid` , `cid` , `cuid` ,  `posttime` ,  `uid` , `username` , `yz` , `ip` ,  `totalmoney`,`products`) 
		VALUES (
		'$mid' , '$_rs[id]' , '$_rs[uid]', '$timestamp','$lfjdb[uid]','$lfjdb[username]','0','$onlineip','$totalmoney','$products')");		

		$id = $id_array[] = $db->insert_id();		

		unset($sqldb);
		$sqldb[]="id='$id'";
		$sqldb[]="uid='$lfjuid'";
		
		/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
		foreach( $field_db AS $key=>$value){
			isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
		}
		$sql=implode(",",$sqldb);
		$db->query("INSERT INTO `{$_pre}content_$mid` SET $sql");
		
		//ɾ���û����ﳵ����Ʒ
		$db->query("DELETE FROM `{$_pre}car` WHERE uid='$lfjuid' AND cid='$_rs[id]'");
		
		if($webdb[order_send_mail]){
			send_mail($infodb[email],"���пͻ��¶�����","�뾡��鿴<A HREF='$Murl/member/wapjoinshow.php?id=$id' target='_blank'>$Murl/member/wapjoinshow.php?id=$id</A>",0);
		}
		if($webdb[order_send_msg]){
			send_msg($infodb[uid],"���пͻ��¶�����","�뾡��鿴<A HREF='$Murl/member/wapjoinshow.php?id=$id' target='_blank'>$Murl/member/wapjoinshow.php?id=$id</A>");
		}
		if($webdb[order_send_sms] && $infodb[mobphone]){
			sms_send($infodb[mobphone],"���пͻ��¶�����");
		}
	}
	//set_cookie('products','');
	//����֧��
	if($postdb['order_paytype']==4){
		if($webdb['daili_receive']){	//����Ա���ջ���
			$ids = implode(',',$id_array);
			if( $webdb['rmb_pay'] && $lfjdb[rmb]>0 ){	//����
				$url = "waprmb_pay.php?ids=$ids&to_url=olpay2";
			}else{
				$url = "wapolpay2.php?ids=$ids";
			}
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
			exit;
		}else{
			$url = "wapolpay.php?id=$id_array[0]";
			if(count($id_array)>1){	//����ж����ͬ�̻��Ļ�,��Ҫ�ֶ������֧��.
				unset($id_array[0]);
				set_cookie('olpay_id',implode(',',$id_array));
			}
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
			exit;
		}
	}else{
		refreshto("./","�����ɹ�,��ȴ�����!");
	}	
}

/*ɾ������,ֱ��ɾ��,������*/
elseif($action=="del")
{
	del_order($id);
	refreshto("./","ɾ���ɹ�");
}

/*�༭����*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ�޸�");
	}

	$query = $db->query("SELECT * FROM `{$_pre}address` WHERE uid='$lfjuid' ORDER BY rid DESC");
	$address_list="";
	$i=0;
	while($rs = $db->fetch_array($query)){
		$i++;
		$checkeds=($i==1)?"checked='checked'":"";
		$address_list.="<ul><li><input class='seladdress' name='postdb[address_type]' type='radio' value='$rs[rid]' $checkeds /></li>\r\n<li><div><span>�ջ���:{$rs[order_username]}</span><em>�绰:{$rs[order_mobphone]}</em></div>\r\n<p>�ջ���ַ:{$rs[order_address]}</p></li>\r\n</ul>\r\n";
		
	}

	/*��Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";	

	require(ROOT_PATH."inc/waphead.php");
	require(getTpl("wappost_$mid",$FidTpl['post']));
	require(ROOT_PATH."inc/wapfoot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}

	//�Զ����ֶεĺϷ���������ݴ���
	$Module_db->checkpost($field_db,$postdb,$rsdb);


	/*��������Ϣ������*/
	//$db->query("UPDATE `{$_pre}join` SET title='$postdb[title]' WHERE id='$id'");


	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/

	if($postdb[address_type]>0){//ʹ�þɵ�ַ
		$ts = $db->get_one("SELECT * FROM `{$_pre}address` WHERE uid='$lfjuid' AND rid='$postdb[address_type]'");
		$postdb[order_username] = addslashes($ts[order_username]);
		$postdb[order_phone] = addslashes($ts[order_phone]);
		$postdb[order_mobphone] = addslashes($ts[order_mobphone]);
		$postdb[order_email] = addslashes($ts[order_email]);
		$postdb[order_qq] = addslashes($ts[order_qq]);
		$postdb[order_postcode] = addslashes($ts[order_postcode]);
		$postdb[order_address] = addslashes($ts[order_address]);
	}elseif($postdb[address_type]=='-1'){	//�µ�ַҪ��⴦��
		$db->query("INSERT INTO `{$_pre}address` ( `uid` , `order_username` , `order_phone` , `order_mobphone` , `order_email` , `order_qq` , `order_postcode` , `order_address` ) VALUES ('$lfjuid' , '$postdb[order_username]' , '$postdb[order_phone]' , '$postdb[order_mobphone]' , '$postdb[order_email]' , '$postdb[order_qq]' , '$postdb[order_postcode]' , '$postdb[order_address]')");
	}
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	$db->query("UPDATE `{$_pre}content_$mid` SET $sql WHERE id='$id'");
	
	refreshto("$FROMURL","�޸ĳɹ�");
}
else{
	$query = $db->query("SELECT A.joins,A.type,B.* FROM {$_pre}car A LEFT JOIN `{$_pre}content` B ON A.Cid=B.id WHERE A.uid='$lfjuid' ORDER BY A.id DESC");
	while($rs = $db->fetch_array($query)){
		$rs[price] = str_replace(',','',$rs[price]);
		$rs[picurl] = tempdir($rs[picurl]);
		$listdb[$rs[username]][] = $rs;
	}
	$query = $db->query("SELECT * FROM `{$_pre}address` WHERE uid='$lfjuid' ORDER BY rid DESC");
	$address_list="";
	$i=0;
	while($rs = $db->fetch_array($query)){
		$i++;
		$checkeds=($i==1)?"checked='checked'":"";
		$address_list.="<ul><li><input class='seladdress' name='postdb[address_type]' type='radio' value='$rs[rid]' $checkeds /></li>\r\n<li><div><span>�ջ���:{$rs[order_username]}</span><em>�绰:{$rs[order_mobphone]}</em></div>\r\n<p>�ջ���ַ:{$rs[order_address]}</p></li>\r\n</ul>\r\n";
		
	}
	//��Ĭ�ϱ���������
	$Module_db->formGetVale($field_db,$rsdb);
	$atc="postnew";
	require(ROOT_PATH."inc/waphead.php");
	require(getTpl("wappost_$mid"));
	require(ROOT_PATH."inc/wapfoot.php");
}
?>