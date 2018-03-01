<?php
require("global.php");

if(!$lfjuid){
	showerr("请先在前台登录");
}

if($action=='get')
{
	if(!$atc_passwd){
		showerr("请输入充值卡密码");
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
  请输入当前点卡的面值：
  <input type="text" name="atc_money" size=10 />元
  </label>
  <label>
  <input type="submit" name="Submit" value="提交" />
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
		showerr("资料有误！");
	}
	if($rsdb[ifsell]){
		showerr("本充值卡已使用过,请不要重复充值");
	}
	$db->query("UPDATE {$pre}moneycard SET ifsell='1',uid='$lfjuid',username='$lfjid',posttime='$timestamp' WHERE id='$rsdb[id]'");

	add_user($lfjuid,$rsdb[moneycard],'充值卡(点卡)充值');

	refreshto("$webdb[www_url]/","恭喜你,充值成功",2);
}
elseif($action=='yu_er'){

	$post_rmb = intval($post_rmb);
	if($post_rmb<1){
		showerr("支付金额必须大于1元！");
	}elseif($post_rmb>$lfjdb[rmb]){
		showerr("支付金额不能大于你当前帐户可用余额！");
	}

	$floor = floor($post_rmb/10);
	$num=$post_rmb*$webdb[alipay_scale] + $floor*$webdb[alipay_give_scale];
	
	add_rmb($lfjuid,-$post_rmb,0,'帐户余额充值积分');
	add_user($lfjuid,$num,'帐户余额充值积分');

	refreshto("$webdb[www_url]/","恭喜你,充值成功",2);
}

$lfjdb[money]=get_money($lfjdb[uid]);

require(ROOT_PATH."inc/head.php");
require(html("moneycard"));
require(ROOT_PATH."inc/foot.php");

?>