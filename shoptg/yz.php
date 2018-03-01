<?php
require(dirname(__FILE__)."/global.php");
if($action=="ckeck"){
	if(!$password){
		$echo_word="<font color=red>没有输入消费密码！请输入消费密码！</font>";
	}else{
		$rs = $db->get_one("SELECT A.title,J.* FROM `{$_pre}join` J LEFT JOIN {$_pre}content A ON J.cid=A.id WHERE J.password='$password'");
		if(!$rs){
			$echo_word="<font color=red>此消费密码不存在！请更换其它消费密码！</font>";
		}elseif(!$rs[ifpay]){
			$echo_word="<font color=red>此订单还没有付款！付款后才能消费！</font> <a href=\"olpay.php?id=$rs[id]&fid=$rs[fid]\" target='_blank'>付款</a>";			
		}elseif($rs[ifsend]){
			$echo_word="<font color=red>此订单已经消费！不可重新消费！</font>";			
		}else{
			$echo_word="<font style='color:blue;font-size:16px;font-weight:bold;'>订单消费成功！</font>";
			$echo_word.="<div style='padding:10px 10px 10px 50px;line-height:20px;text-align:left;'>";
			$echo_word.="商品：<a href=\"bencandy.php?fid=$rs[fid]&id=$rs[cid]\" target='_blank'>$rs[title]</a><br/>";
			$echo_word.="购买数量：<font color=red>$rs[shopnum]</font>份<br/>";
			$echo_word.="消费金额：<font color=red>$rs[totalmoney]</font>元<br/>";
			$echo_word.="</div>";
			$db->query("UPDATE {$_pre}join SET ifsend='1',sendtime='$timestamp' WHERE id='$rs[id]'");/**/
		}
	}
}

require(ROOT_PATH."inc/head.php");
require(getTpl("yz"));
require(ROOT_PATH."inc/foot.php");

?>