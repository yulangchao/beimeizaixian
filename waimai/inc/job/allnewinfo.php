<?php
if(!function_exists('html')){
die('F');
}


$show=$SQL='';
$rows>50 && $rows=50;
$rows<1 && $rows=10;
$leng<1 && $leng=30;
if(!$webdb[Info_ShowNoYz]){
	$SQL =" AND yz='1' ";
}
$query = $db->query("SELECT * FROM {$_pre}content WHERE city_id='$city_id' $SQL ORDER BY id DESC LIMIT $rows");
while($rs = $db->fetch_array($query)){
	$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);
	$rs[title]=get_word($rs[title],$leng);
	$show.="<div>¡¤<a href='$rs[url]' target='_blank'>$rs[title]</a></div>";
}

if($webdb[RewriteUrl]==1){	//È«¾ÖÎ±¾²Ì¬
	rewrite_url($show);
}

$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);
if($webdb[cookieDomain]){
	echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
}
echo "<SCRIPT LANGUAGE=\"JavaScript\">
parent.document.getElementById('$iframeID').innerHTML='$show';
</SCRIPT>";



?>