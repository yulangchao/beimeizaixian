<?php
if($webdb['run_time']==1){
	$speed_endtime=explode(' ',microtime());
	$speed_totaltime.="TIME ".number_format((($speed_endtime[0]+$speed_endtime[1]-$speed_headtime)/1),6)." second(s)";
}
if($webdb[AvoidGather]){
	echo "<SCRIPT LANGUAGE='JavaScript'>avoidgather('$webdb[AvoidGatherPre]');</SCRIPT>";
}
require(html("foot",$foot_tpl));

$content=ob_get_contents();
$content=str_replace("<!---->","",$content);
$content=preg_replace("/<!--include(.*?)include-->/is","\\1",$content);
ob_end_clean();
ob_start(); /*方便后面再次调用*/
$content=kill_badword($content);
if($webdb[cookieDomain] && strstr($content,'ewebeditor/ewebeditor.php?')){
	$content=preg_replace("/document.domain([^<]+)/is","",$content);
}
if($webdb[www_url]=='/.'){
	$content=str_replace('/./','/',$content);
}

if($webdb[RewriteUrl]==1&&!$webdb['Html_Type']){	//全站伪静态,$webdb['Html_Type']这个是万能文章真静态
	rewrite_url($content);
	
	//有的空间不支持home目录伪静态
	$content=str_replace('/home/homepage-','/hy/homepage-',$content);
}

//解决火狐不兼容的问题,不然火狐隐藏框架里的页面报错会失效
$content=preg_replace('/<div style="display:none;">([^<]*)<iframe /is','<div style="position:absolute; left:-9999px;"><iframe ',$content);


//恢复数据的时候要用到
$content=str_replace('CKEDITOR.replace(','CK_Editor=CKEDITOR.replace(',$content);

web_cache();	//生成缓存

if($web_admin && $jobs=="show" && !strstr($content,'label_jq.js')){
	$content = str_replace("<body","<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/images/default/label_jq.js'></SCRIPT><SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/images/default/label.js'></SCRIPT><SCRIPT LANGUAGE='JavaScript'>
	\$(document).ready(function(){ \$('.p8label').each(function(){ \$(this).hover(function(){ \$(this).css({'opacity':'0.8','filter':'alpha(opacity=70)'});}, function(){ \$(this).css({'opacity':'0.4','filter':'alpha(opacity=50)'});}).jqResize(\$('div', this))});});
	var admin_url='$webdb[admin_url]';var ch='0';var ch_fid='$ch_fid';var ch_pagetype='$ch_pagetype';var ch_module='$ch_module';var fromurl='$fromurl';var mystyle='$STYLE';</SCRIPT><body",$content);
}

echo $content;

?>