<?php
require_once("global.php");

//$webdb[ForbidDelOrder]
if($action=='del'){
	del_order($id);
	refreshto($FROMURL,'',0);
	
}elseif($job=='receive'){
	$rsdb=$db->get_one("SELECT B.*,A.* FROM {$_pre}join A LEFT JOIN {$_pre}content_2 B ON A.id=B.id WHERE A.id='$id'");
	if($rsdb[ifpay]!=1 && $rsdb[order_paytype]!=1 ){	//$rsdb[order_paytype]==1 代表货到付款
		showerr('还没付款,无权操作!');
	}
	$SQL="";
	if($receive=='-1'){
		$SQL=",ifsend=0";
		send_msg($rsdb[cuid],"商城里有客户申请退货了","订单号为:{$id},申请退货了",$rsdb[uid]);
	}

	$db->query("UPDATE {$_pre}join SET receive='$receive'$SQL WHERE id='$id' AND uid='$lfjuid'");

}elseif($action=='back_rmb'){	//余额已付部分款,但没有继续使用其它方式付全款的话.可申请立即退款

	$rs=$db->get_one("SELECT * FROM {$_pre}join WHERE id='$id'");

	if(!$rs[ifpay]&&$rs[rmb]){
		add_rmb($lfjuid,$rs[rmb],0,"商城余额退款");
		$db->query("UPDATE {$_pre}join SET rmb='0' WHERE id='$id' AND uid='$lfjuid'");
		refreshto("$webdb[www_url]/member/rmb.php?job=list",'退款成功,请查看你的帐户余额',3);
	}else{
		showerr('出错了!');
	}	
}

$rows=10;
if(!$page){
	$page=1;
}
$min=($page-1)*$rows;
unset($listdb,$i);

$query = $db->query("SELECT SQL_CALC_FOUND_ROWS A.*,B.* FROM {$_pre}join A LEFT JOIN {$_pre}content_2 B ON A.id=B.id WHERE A.uid='$lfjuid' ORDER BY A.id DESC LIMIT $min,$rows");

$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?job=$job",$rows,$totalNum);

while($rs = $db->fetch_array($query)){

	$id_array=array(intval($rs[cid]));
	$detail = explode(',',$rs[products]);
	foreach($detail AS $value){
		list($pid,$pnum) = explode('=',$value);
		$id_array[] = intval($pid);
		$ordernum[$pid] = intval($pnum);
	}
	$query2 = $db->query("SELECT * FROM {$_pre}content WHERE id IN (".implode(',',$id_array).")");
	while($rs2 = $db->fetch_array($query2)){
		$rs2[ordernum]=$ordernum[$rs2[id]];
		$rs['plist'][]=$rs2;
	}

	$rs[posttime]=date("m-d H:i",$rs[posttime]);

	$rs[editurl]="../join.php?job=edit&id=$rs[id]&fid=$rs[fid]&cid=$rs[cid]' target='_blank";

	$rs[ordershow]='';
	$rs[ActOrder]='';

	if($rs[ifpay]){
		$rs[pay]="<A style='color:red;'>已付</A>";
		$rs[ordershow].='已付款 ';
	}elseif($rs[totalmoney]){
		if($rs[rmb] && (!$webdb[rmb_late_pay] || $lfjdb[rmb]>=$rs[rmb]) ){
			$rs[pay]="已付{$rs[rmb]}元<br><A HREF='../olpay.php?id=$rs[id]' target='_blank'><u>付款</u></A>";			
		}else{
			$rs[pay]="<A HREF='../olpay.php?id=$rs[id]' target='_blank'><u>付款</u></A>";
		}
		$rs[ActOrder].="<A HREF='../olpay.php?id=$rs[id]'>付款</A> ";
		$rs[ordershow].='未付款 ';
	}else{
		$rs[pay]='';
	}
	$rs[send]=$rs[ifsend]?"<A style='color:red;'>已发</A>":"未发";

	if($rs[ifsend]){
		$rs[ordershow].='已发货 ';
	}else{
		$rs[ordershow].='未发货 ';
	}
	
	//管理员除处,禁止删除已付款的订单
	if($rs[ifpay] &&  $webdb['ForbidDelPayOrder']){
		$rs[nodel] = 'none';
	}
	
	//if($rs[ifsend]){
		if($rs[receive]==1){
			$rs[_receive] = "<A HREF='javascript:' click=1 editcode='<A HREF=\"?job=receive&id=$rs[id]&receive=-1\">我要退货</A>' style='color:red;'>已签收</A>";
			$rs[ActOrder].="<A HREF='?job=receive&id=$rs[id]&receive=-1'>退货</A> ";
			$rs[ordershow].='已签收 ';
		}elseif($rs[receive]==-1){
			if($rs[ifsend]){
				$rs[_receive] = "<A HREF='javascript:' click=1 editcode='<A HREF=\"?job=receive&id=$rs[id]&receive=1\">新货签收</A>' title='已申请退货' style='color:blue;'>退货中</A>";
				$rs[ActOrder].="<A HREF='?job=receive&id=$rs[id]&receive=1'>新货签收</A> ";				
			}else{
				$rs[_receive] = "<font color=blue>退货中</font>";	
			}
			$rs[ordershow].='退货中 ';
			
		}elseif($rs[ifsend]){
			$rs[_receive] = "<A HREF='javascript:' click=1 editcode=''>操作</A><div style='display:none;'><A HREF=\"?job=receive&id=$rs[id]&receive=1\">签收</A><br><A HREF=\"?job=receive&id=$rs[id]&receive=-1\" onclick=\"return confirm('你确认要退货吗?\\n不退货,请点击取消');\">退货</A></div>";
			$rs[ActOrder].="<A HREF='?job=receive&id=$rs[id]&receive=1'>签收</A> <A HREF=\"?job=receive&id=$rs[id]&receive=-1\" onclick=\"return confirm('你确认要退货吗?\\n不退货,请点击取消');\">退货</A>";	
		}
	//}
	if($rs[ifsend]){
		$rs[noedit] = 'none';
	}
	$rs[back_rmb]="";
	if(!$rs[ifpay]&&$rs[rmb]){	//余额已付部分款,但没有继续使用其它方式付全款的话.可申请立即退款
		$rs[back_rmb]="<A HREF='?action=back_rmb&id=$rs[id]'>退余额</A><br>";
		$rs[ActOrder].="<A HREF='?action=back_rmb&id=$rs[id]'>退余额</A> ";
	}

	$listdb[]=$rs;
}

require(ROOT_PATH."member/waphead.php");
require(dirname(__FILE__)."/template/waporder.htm");
require(ROOT_PATH."member/wapfoot.php");
?>