<?php
require(dirname(__FILE__)."/global.php");
if($action=="ckeck"){
	if(!$password){
		$echo_word="<font color=red>û�������������룡�������������룡</font>";
	}else{
		$rs = $db->get_one("SELECT A.title,J.* FROM `{$_pre}join` J LEFT JOIN {$_pre}content A ON J.cid=A.id WHERE J.password='$password'");
		if(!$rs){
			$echo_word="<font color=red>���������벻���ڣ�����������������룡</font>";
		}elseif(!$rs[ifpay]){
			$echo_word="<font color=red>�˶�����û�и�������������ѣ�</font> <a href=\"olpay.php?id=$rs[id]&fid=$rs[fid]\" target='_blank'>����</a>";			
		}elseif($rs[ifsend]){
			$echo_word="<font color=red>�˶����Ѿ����ѣ������������ѣ�</font>";			
		}else{
			$echo_word="<font style='color:blue;font-size:16px;font-weight:bold;'>�������ѳɹ���</font>";
			$echo_word.="<div style='padding:10px 10px 10px 50px;line-height:20px;text-align:left;'>";
			$echo_word.="��Ʒ��<a href=\"bencandy.php?fid=$rs[fid]&id=$rs[cid]\" target='_blank'>$rs[title]</a><br/>";
			$echo_word.="����������<font color=red>$rs[shopnum]</font>��<br/>";
			$echo_word.="���ѽ�<font color=red>$rs[totalmoney]</font>Ԫ<br/>";
			$echo_word.="</div>";
			$db->query("UPDATE {$_pre}join SET ifsend='1',sendtime='$timestamp' WHERE id='$rs[id]'");/**/
		}
	}
}

require(ROOT_PATH."inc/head.php");
require(getTpl("yz"));
require(ROOT_PATH."inc/foot.php");

?>