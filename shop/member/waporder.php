<?php
require_once("global.php");

//$webdb[ForbidDelOrder]
if($action=='del'){
	del_order($id);
	refreshto($FROMURL,'',0);
	
}elseif($job=='receive'){
	$rsdb=$db->get_one("SELECT B.*,A.* FROM {$_pre}join A LEFT JOIN {$_pre}content_2 B ON A.id=B.id WHERE A.id='$id'");
	if($rsdb[ifpay]!=1 && $rsdb[order_paytype]!=1 ){	//$rsdb[order_paytype]==1 �����������
		showerr('��û����,��Ȩ����!');
	}
	$SQL="";
	if($receive=='-1'){
		$SQL=",ifsend=0";
		send_msg($rsdb[cuid],"�̳����пͻ������˻���","������Ϊ:{$id},�����˻���",$rsdb[uid]);
	}

	$db->query("UPDATE {$_pre}join SET receive='$receive'$SQL WHERE id='$id' AND uid='$lfjuid'");

}elseif($action=='back_rmb'){	//����Ѹ����ֿ�,��û�м���ʹ��������ʽ��ȫ��Ļ�.�����������˿�

	$rs=$db->get_one("SELECT * FROM {$_pre}join WHERE id='$id'");

	if(!$rs[ifpay]&&$rs[rmb]){
		add_rmb($lfjuid,$rs[rmb],0,"�̳�����˿�");
		$db->query("UPDATE {$_pre}join SET rmb='0' WHERE id='$id' AND uid='$lfjuid'");
		refreshto("$webdb[www_url]/member/waprmb.php?job=list",'�˿�ɹ�,��鿴����ʻ����',3);
	}else{
		showerr('������!');
	}	
}

$rows=15;
if(!$page){
	$page=1;
}
$min=($page-1)*$rows;

unset($listdb,$i,$SQLS);

if($pay=='yes'){
	$SQLS="AND A.ifpay !=''";
}elseif($pay=='no'){
	$SQLS="AND A.ifpay =''";
}elseif($ifsend=='yes'){
	$SQLS="AND A.ifsend ='1'";
}elseif($ifsend=='no'){
	$SQLS="AND A.ifsend !='1'";
}

$query = $db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$_pre}join A LEFT JOIN {$_pre}content_2 B ON A.id=B.id WHERE A.uid='$lfjuid' $SQLS ORDER BY A.id DESC LIMIT $min,$rows");

$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?job=$job",$rows,$totalNum);

while($rs = $db->fetch_array($query)){

	$id_array=array(intval($rs[cid]));
	$detail = explode(',',$rs[products]);
	foreach($detail AS $value){
		list($pid,$pnum) = explode('=',$value);
		$ordernum[$pid]=$pnum;
		$id_array[] = intval($pid);

	}
	$query2 = $db->query("SELECT * FROM {$_pre}content WHERE id IN (".implode(',',$id_array).")");
	while($rs2 = $db->fetch_array($query2)){
		$rs2[ordernum]=$ordernum[$rs2[id]];
		$rs['plist'][]=$rs2;
	}

	$rs[posttime]=date("m-d H:i",$rs[posttime]);

	unset($rs[moreact],$rs[moreshow]);

	$rs[editurl]="../wapjoin.php?job=edit&id=$rs[id]&fid=$rs[fid]&cid=$rs[cid]' target='_blank";
	if($rs[ifpay]){
		$rs[pay]="<A style='color:red;'>�Ѹ�</A>";
		$rs[moreshow].="<span>�Ѹ���</span> ";
	}elseif($rs[totalmoney]){
		if($rs[rmb] && (!$webdb[rmb_late_pay] || $lfjdb[rmb]>=$rs[rmb]) ){
			$rs[pay]="�Ѹ�{$rs[rmb]}Ԫ<A HREF='../wapolpay.php?id=$rs[id]' target='_blank'><u>����</u></A>";
			$rs[moreshow].="<span>�Ѹ�{$rs[rmb]}Ԫ</span> ";
			$rs[moreact].="<A HREF='../wapolpay.php?id=$rs[id]'>����</A> ";
		}else{
			$rs[pay]="<A HREF='../wapolpay.php?id=$rs[id]' target='_blank'><u>����</u></A>";

			$rs[moreact].="<A HREF='../wapolpay.php?id=$rs[id]'>����</A> ";			
		}		
	}else{
		$rs[pay]='';
	}
	$rs[send]=$rs[ifsend]?"<A style='color:red;'>�ѷ�</A>":"δ��";

	$rs[moreshow].=$rs[ifsend]?"<span style='color:red;'>�ѷ�</span>":"<span>δ��</span>";
	
	//����Ա����,��ֹɾ���Ѹ���Ķ���
	if($rs[ifpay] &&  $webdb['ForbidDelPayOrder']){
		$rs[nodel] = 'none';
	}
	
	//if($rs[ifsend]){
		if($rs[receive]==1){
			$rs[_receive] = "<A HREF='javascript:' click=1 editcode='<A HREF=\"?job=receive&id=$rs[id]&receive=-1\">��Ҫ�˻�</A>' style='color:red;'>��ǩ��</A>";
			$rs[moreshow].="<span>��ǩ��</span> ";
			$rs[moreact].="<A HREF='?job=receive&id=$rs[id]&receive=-1'>�˻�</A> ";
		}elseif($rs[receive]==-1){
			if($rs[ifsend]){
				$rs[_receive] = "<A HREF='javascript:' click=1 editcode='<A HREF=\"?job=receive&id=$rs[id]&receive=1\">�»�ǩ��</A>' title='�������˻�' style='color:blue;'>�˻���</A>";
				$rs[moreshow].="<span>�˻���</span> ";
				$rs[moreact].="<A HREF='?job=receive&id=$rs[id]&receive=1'>ǩ��</A> ";
			}else{
				$rs[_receive] = "<font color=blue>�˻���</font>";
				$rs[moreshow].="<span>�˻���</span> ";
			}
			
		}elseif($rs[ifsend]){
			$rs[_receive] = "<A HREF='javascript:' click=1 editcode=''>����</A><div style='display:none;'><A HREF=\"?job=receive&id=$rs[id]&receive=1\">ǩ��</A><br><A HREF=\"?job=receive&id=$rs[id]&receive=-1\" onclick=\"return confirm('��ȷ��Ҫ�˻���?\\n���˻�,����ȡ��');\">�˻�</A></div>";
			$rs[moreact].="<A HREF='?job=receive&id=$rs[id]&receive=1'>ǩ��</A> ";
			$rs[moreact].="<A HREF='?job=receive&id=$rs[id]&receive=-1' onclick=\"return confirm('��ȷ��Ҫ�˻���?\\n���˻�,����ȡ��');\">�˻�</A> ";
		}
	//}
	if($rs[ifsend]){
		$rs[noedit] = 'none';
	}
	$rs[back_rmb]="";
	if(!$rs[ifpay]&&$rs[rmb]){	//����Ѹ����ֿ�,��û�м���ʹ��������ʽ��ȫ��Ļ�.�����������˿�
		$rs[back_rmb]="<A HREF='?action=back_rmb&id=$rs[id]'>�����</A><br>";
		$rs[moreact].="<A HREF='?action=back_rmb&id=$rs[id]'>�����</A> ";
	}

	$listdb[]=$rs;
}

require(ROOT_PATH."member/waphead.php");
require(dirname(__FILE__)."/template/waporder.htm");
require(ROOT_PATH."member/wapfoot.php");
?>