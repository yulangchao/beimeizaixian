<?php
header('Content-Type: text/html; charset=gb2312'); 
if(!$lfjid)
{
	die("<A HREF='$webdb[www_url]/do/login.php' onclick=\"clickEdit.cancel('clickEdit_$TagId')\">请先登录</A>");
}
if($act=="do"){
	if(!$lfjuid){
		$power=0;
	}elseif($web_admin){
		$power=2;
	}else{
		$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
		if($rs[uid]==$lfjuid){
			$power=1;
		}else{
			$power=0;
		}
	}
	if($power==0){
		die("你无权操作");
	}

	echo "<A HREF=\"$Murl/post.php?job=postnew&fid=$fid\">新发表</A><br><A HREF=\"$Murl/claim.php?fid=18&id=11\">认领店铺</A><br><A  HREF=\"$Murl/post.php?job=edit&fid=$fid&id=$id\">修改</A><br><A HREF=\"$Murl/post.php?action=del&fid=$fid&id=$id\" onclick=\"return confirm('你确认要删除吗?');\">删除</A><br>";

}
?>