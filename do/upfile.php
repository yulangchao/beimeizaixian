<?php
require_once (dirname(__FILE__).'/global.php');
if(!$lfjid){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
		 <body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">';
	die("<A HREF='$webdb[www_url]/do/login.php' target='_blank'  onclick='window.self.close();'>请先在前台登录</A>");
}
print <<<EOT

<html>
<head>
<title>$titleDB[title]</title>
<meta name='keywords' content=''>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
body{
	margin:0px;
	padding:0px;
	font-size:9pt;
	background:#FFF;
	position:relative;
}
input { vertical-align:middle;}
input,select{color:navy;font:normal 12px/120% Tahoma,Verdana,sans-serif; height:19px;}
input,textarea,hr{color:navy;font:normal 12px/120% Verdana,sans-serif;padding:2px;border:1px solid #ddd; height:27px;}
.global_file { width:150px;}
.btn { background-color:#e1e1e1; border-radius:3px; padding:0 12px; border:1px solid #ccc; line-height:18px; font-size:12px; font-weight:bold; color:#404040; cursor:pointer;position:absolute;right:0;top:0;}
.btn:hover { color:#fff; border:1px solid #1b57aa;background-color:#1b57aa;}
.notes{text-align:center;line-height:30px;}
</style>
<SCRIPT LANGUAGE='JavaScript'>
<!--
if('$showDomain'=='1'){
	if('$webdb[cookieDomain]'!='')document.domain = '$webdb[cookieDomain]';
}else{
	window.onerror=function (){
		url = '$WEBURL';
		url +=url.indexOf('?')>0?'&':'?';
		window.location.href=url+'showDomain=1';
		return true;
	};
	obj = (self==top) ? window.opener : window.parent ;
	obj.document.body;
}
//-->
</SCRIPT>
</head>
<body>
EOT;
if($postfile){

	//其中..与/开头都是不允许的
	if( !ereg("^[0-9a-z_/]+$",$dir)||ereg("^/",$dir) ){
		$dir="other";
	}
	$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
	$array[path]=$webdb[updir]."/".$dir;
	$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
	
	$array[updateTable]=1;	//统计用户上传的文件占用空间大小
	$filename=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
	//删除用户反复上传的图片
	if($ISone){
		delete_attachment($lfjuid,tempdir("$oldfile"));
	}
	$newfile="$dir/$filename";
	echo "上传成功,<A HREF='?fn=$fn&dir=$dir&label=$_GET[label]&ISone=$_GET[ISone]&oldfile=$newfile&showDomain=$showDomain'>你可以继续或重新上传</A>";
	$fn || $fn="upfile";
	$weburl=tempdir($newfile);
	echo "<script>
				if(self==top){
					window.opener.$fn('$newfile','$array[name]','$array[size]','$_GET[label]','$weburl');
					window.self.close();
				}else{
					window.parent.$fn('$newfile','$array[name]','$array[size]','$_GET[label]','$weburl');
				}
		 </script>";
			
	exit;
}
print <<<EOT
<form name="form1" method="post" action="" enctype="multipart/form-data">
  <input id="postfile" type="file" name="postfile" class="global_file">
  <input  type="submit" name="Submit" value="上传" class="btn" >
  <input type="hidden" name="action" value="uploadfile">
  <input type="hidden" name="showDomain" value="$showDomain">
  <input type="hidden" name="oldfile" value="$oldfile">
</form>
</body>
</html>

EOT;
?>