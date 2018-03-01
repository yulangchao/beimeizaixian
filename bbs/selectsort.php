<?php
require(dirname(__FILE__)."/global.php");
$show='';
$fup||$fup=0;
foreach($Fid_db[$fup] AS $key=>$value){
	//$listthis=$Fid_db[$key]?'<div class="fup" onclick="SelectThisSort1('.$key.')"><span>'.$value.'</span></div>':'<div class="fid" onclick="SelectThisSort2('.$key.')"><span>'.$value.'</span></div>';
	$listthis=$Fid_db[$key]?"<div class='fup fid$key' onclick='SelectThisSort1($key,\"$value\")'>$value</div>":"<div class='fid fid$key' onclick='SelectThisSort2($key,\"$value\")'>$value</div>";
	$show.=$listthis."\r\n";
}
echo $show;
exit;
?>