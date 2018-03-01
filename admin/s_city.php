<?php
require_once(dirname(__FILE__)."/"."global.php");

if(WEB_LANG!='utf-8'){	
	require_once(ROOT_PATH."inc/class.chinese.php");
	$cnvert = new Chinese("UTF8","GB2312",$queryString,ROOT_PATH."./inc/gbkcode/");
	$queryString = $cnvert->ConvertIT();
}
$queryString=filtrate($queryString);
  if(strlen($queryString) >0) {		
	  $query = $db->query("SELECT name FROM {$pre}{$tabname} WHERE name LIKE '%$queryString%' ORDER BY list DESC LIMIT 30");
		  while ($result = $db->fetch_array($query)) {
		
			  //echo '<li onClick="fill(\''.$result[name].'\');">'.$result[name].'</li>';
			  echo '<li><a href="index.php?lfj=city&job='.$tabname.'&keyword='.$result[name].'">'.$result[name].'</a></li>';
		  }
  }

?>