<?php
require_once(dirname(__FILE__)."/global.php");

if($atc=='changestyle'){
	$stylename=$stylename?filtrate($stylename):"default";
	if($stylename=='nostyle'){
		$stylename='';
	}
	$db->query("UPDATE `{$_pre}company` SET webstyle='$stylename' WHERE uid='$lfjuid'");
	exit;
}

$companys=$db->get_one("SELECT * FROM {$pre}hy_company WHERE uid='$lfjuid'");

$dir=opendir(Mpath."webstyle/template/");
$showstyle='';
while( $file=readdir($dir) ){
	if( is_dir(Mpath."webstyle/template/$file")&&$file!='.'&&$file!='..'&&$file!='.svn'){
		$styleimg=$Murl."/webstyle/template/".$file."/1.png";
		require_once(Mpath."webstyle/template/".$file."/style.php");
		$choosestyle=($companys[webstyle]==$file)?"class='choose'":"";
		$showstyle.="<li $choosestyle><div class='img'><span onclick=\"set_style('$file',\$(this))\" ><a href='javascript:;'title='点击选择当前风格'><img src='$styleimg'/></a></span></div><div class='name'>$webstyledb[name]</div><div class='set'><a href='javascript:;' onclick=\"set_style('$file',\$(this))\">选择当前风格</a></div></li>\r\n";
	}
}

$clear_style=$companys[webstyle]?"class='choose'":"";

require(ROOT_PATH."member/head.php");
require(dirname(__FILE__)."/"."template/setstyle.htm");
require(ROOT_PATH."member/foot.php");
exit;
?>