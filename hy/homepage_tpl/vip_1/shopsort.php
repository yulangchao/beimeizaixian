<?php
unset($listdb);
$query=$db->query("SELECT * FROM {$pre}shop_mysort WHERE uid='$uid' AND fup=0 ORDER BY list DESC");
while($rs=$db->fetch_array($query)){
	$ckk=$myfid==$rs[fid]?"style='color:red;'":"";
	$rs['name']="【<a $ckk href='?m=shop&uid=$uid&myfid=$rs[fid]'>$rs[name]</a>】";
	$listdb[]=$rs;
	$query2=$db->query("SELECT * FROM {$pre}shop_mysort WHERE  fup='$rs[fid]' ORDER BY list DESC");
	while($rs2=$db->fetch_array($query2)){
		$ckk=$myfid==$rs2[fid]?"style='color:red;'":"";
		$rs2['name']="&nbsp;&nbsp;|----【<a $ckk href='?m=shop&uid=$uid&myfid=$rs2[fid]'>$rs2[name]</a>】";
		$listdb[]=$rs2;
	}
}
?>
<!--
<?php
print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'>商品分类</span>
	<span class='R'></span>
	<span class='more'></span>
	
	</td>
  </tr>
  <tr>
    <td  class="content">


<!--
EOT;
foreach($listdb as $rs){
print <<<EOT
-->
<li>$rs[name]</li>
<!--
EOT;
}
print <<<EOT
-->
	
	</td>
  </tr>
</table>

   
 
<!--
EOT;
unset($listdb);
?>
-->