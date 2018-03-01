<?php
defined('ROOT_PATH') or die();
require_once(ROOT_PATH.'inc/class.module.tpl.inc.php');

class module_tpl extends module_tpl_Father{

function module_tpl(&$system){
	$this->sys = $system;
}


//列表页筛选字段
function listfilter_tpl($rs,$field_db,&$tplcode)
{
	$field=$rs[field_name];
	unset($TempSearch_array);
	foreach($field_db AS $key2=>$value2){
		if(!$value2[listfilter]){
			continue;
		}
		if($field!=$key2){
			$TempSearch_array.="'$key2'=>\"\$$key2\",";
		}	
	}
	$show="<!--\r\nEOT;\r\n\$url=get_info_url('',\$fid,\$city_id,\$zone_id,\$street_id,array($TempSearch_array),\$filetype);\r\nprint <<<EOT\r\n--><A HREF='\$url' {\$search_fieldDB[{$rs[field_name]}]['null']}>不限</A> ";


	$detail=explode("\r\n",$rs[form_set]);
	foreach( $detail AS $key=>$value){
		if(!$value){
			continue;
		}
		list($v1,$v2)=explode("|",$value);
		$v2 || $v2=$v1;
		$show .="<!--\r\nEOT;\r\n\$url=get_info_url('',\$fid,\$city_id,\$zone_id,\$street_id,array($TempSearch_array'$rs[field_name]'=>'$v1'),\$filetype);\r\nprint <<<EOT\r\n--> <A HREF='\$url' {\$search_fieldDB[{$rs[field_name]}]['{$v1}']}>$v2</A>";
	}
	@preg_match_all("/<!--{filter}-->(.*?)<!--{\/filter}-->/is",$tplcode,$array);
	foreach((array)$array[1] AS $k=>$v){
		$v=str_replace(array('{title}','{value}'),array($rs[title],$show),$v);
		$array[1][$k]=$v.$array[0][$k];
	}
	$tplcode=str_replace($array[0],$array[1],$tplcode);
}

}

?>