<?php
require("global.php");

if(!$lfjuid){
	showerr("������ǰ̨��¼");
}

if($action=='get')
{
	if(!$atc_passwd){
		showerr("�������ֵ������");
	}
	
	$SQL='';	
	$ts = $db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}moneycard WHERE passwd='$atc_passwd'");
	if($ts['NUM']>1){
		if(!$atc_money){
		
			require(ROOT_PATH."inc/head.php");
print <<<EOT
<br><br><br>
<CENTER><form id="form1" name="form1" method="post" action="?action=get">
  <label>
  �����뵱ǰ�㿨����ֵ��
  <input type="text" name="atc_money" size=10 />Ԫ
  </label>
  <label>
  <input type="submit" name="Submit" value="�ύ" />
  </label>
  <input type="hidden" name="atc_passwd" value="$atc_passwd" />
</form></CENTER>
<br><br><br>
EOT;
			require(ROOT_PATH."inc/foot.php");
			exit;
		}else{
			$SQL=" AND moneyrmb='$atc_money' ";
		}
	}
	
	$rsdb=$db->get_one("SELECT * FROM {$pre}moneycard WHERE passwd='$atc_passwd' $SQL");
	
	if(!$rsdb){
		showerr("��������");
	}
	if($rsdb[ifsell]){
		showerr("����ֵ����ʹ�ù�,�벻Ҫ�ظ���ֵ");
	}
	$db->query("UPDATE {$pre}moneycard SET ifsell='1',uid='$lfjuid',username='$lfjid',posttime='$timestamp' WHERE id='$rsdb[id]'");

	add_user($lfjuid,$rsdb[moneycard],'��ֵ��(�㿨)��ֵ');

	refreshto("$webdb[www_url]/","��ϲ��,��ֵ�ɹ�",2);
}
elseif($action=='yu_er'){

	$post_rmb = intval($post_rmb);
	if($post_rmb<1){
		showerr("֧�����������1Ԫ��");
	}elseif($post_rmb>$lfjdb[rmb]){
		showerr("֧�����ܴ����㵱ǰ�ʻ�������");
	}

	$floor = floor($post_rmb/10);
	$num=$post_rmb*$webdb[alipay_scale] + $floor*$webdb[alipay_give_scale];
	
	add_rmb($lfjuid,-$post_rmb,0,'�ʻ�����ֵ����');
	add_user($lfjuid,$num,'�ʻ�����ֵ����');

	refreshto("$webdb[www_url]/","��ϲ��,��ֵ�ɹ�",2);
}

$lfjdb[money]=get_money($lfjdb[uid]);

require(ROOT_PATH."inc/head.php");
require(html("moneycard"));
require(ROOT_PATH."inc/foot.php");

?>