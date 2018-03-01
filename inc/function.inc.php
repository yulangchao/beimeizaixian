<?php
/**
*读文件函数
**/
function read_file($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		@flock($handle,LOCK_SH);
		$filedata=@fread($handle,@filesize($filename));
		@fclose($handle);
	}
	return $filedata;
}

/**
*写文件函数
**/
function write_file($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if(!$handle&&!strstr($filename,'cache/')){
		echo "此文件不可写:$filename";
	}
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);	
	if( is_writable($filename) ){
		return 1;
	}else{
		return 0;
	}
}

/**
*图像处理函数
**/
function gdpic($srcFile,$dstFile,$width,$height,$type=''){
	require_once(ROOT_PATH."inc/waterimage.php");
	if(is_array($type)){
		//截取一部分,以满足匹配尺寸
		cutimg($srcFile,$dstFile,$x=$type[x]?$type[x]:0,$y=$type[y]?$type[y]:0,$width,$height,$x2=$type[x2]?$type[x2]:0,$y2=$type[y2]?$type[y2]:0,$scale=$type[s]?$type[s]:100,$fix=$type[fix]?$type[fix]:'');
	}elseif($type==1){
		//成比例的缩放
		ResizeImage($srcFile,$dstFile,$width,$height);
	}else{
		//与尺寸不匹配时.用色彩填充
		gdfillcolor($srcFile,$dstFile,$width,$height);
	}
}

/**
*删除文件,值不为空，则返回不能删除的文件名
**/
function del_file($path){
	if (file_exists($path)){
		if(is_file($path)){
			if(	!@unlink($path)	){
				$show.="$path,";
			}
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						$show.=del_file("$path/$file");
					} else{
						if( !@unlink("$path/$file") ){
							$show.="$path/$file,";
						}
					}
				}
			}
			closedir($handle);
			if(!@rmdir($path)){
				$show.="$path,";
			}
		}
	}
	return $show;
}

function Tblank($string,$msg="内容不能全为空格"){
	$string=str_replace("&nbsp;","",$string);
	$string=str_replace(" ","",$string);
	$string=str_replace("　","",$string);
	$string=str_replace("\r","",$string);
	$string=str_replace("\n","",$string);
	$string=str_replace("\t","",$string);
	if(!$string){
		showerr($msg);
	}
}

/**
*数据表字段信息处理函数
**/
function table_field($table,$field=''){
	global $db;
	$query=$db->query(" SELECT * FROM $table limit 1");
	$num=mysql_num_fields($query);
	for($i=0;$i<$num;$i++){
		$f_db=mysql_fetch_field($query,$i);
		$showdb[]=$f_db->name;
	}
	if($field){
		if(in_array($field,$showdb) ){
			return 1;
		}else{
			return 0;
		}
	}else{
		return $showdb;
	}
}
/**
*判断数据表是否存在
**/
function is_table($table){
	global $db;
	$query = $db->query("SHOW CREATE TABLE $table",'','0');
	if($query){
		return 1;
	}
}

/**
*上传文件
**/
function upfile($upfile,$array){
	global $db,$lfjuid,$pre,$webdb,$groupdb,$lfjdb,$timestamp;
	$FY=strtolower(strrchr(basename($upfile),"."));if($FY&&$FY!='.tmp'){die("<SCRIPT>alert('上传文件有误');</SCRIPT>");}
	$filename=filtrate($array[name]);

	$path=makepath(ROOT_PATH.$array[path]);

	if($path=='false')
	{
		showerr("不能创建目录$array[path]，上传失败",1);
	}
	elseif(!is_writable($path))
	{
		showerr("目录不可写".$path,1);
	}

	$size=abs($array[size]);

	$filetype=strtolower(strrchr($filename,"."));

	if(!$upfile)
	{
		showerr("文件不存在，上传失败",1);
	}
	elseif(!$filetype)
	{
		showerr("文件不存在，或文件无后缀名,上传失败",1);
	}
	else
	{
		if($filetype=='.php'||$filetype=='.asp'||$filetype=='.aspx'||$filetype=='.jsp'||$filetype=='.cgi'){
			showerr("系统不允许上传可执行文件,上传失败",1);
		}

		if( $groupdb[upfileType] )
		{
			if(!in_array($filetype,explode(" ",$groupdb[upfileType])))showerr("你所上传的文件格式为:$filetype,而你所在用户组仅允许上传的文件格式为:$groupdb[upfileType]",1);
		}
		elseif( !in_array($filetype,explode(" ",$webdb[upfileType])) )
		{
			showerr("你所上传的文件格式为:$filetype,而系统仅允许上传的文件格式为:$webdb[upfileType]",1);
		}

		if( $groupdb[upfileMaxSize] && ($groupdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而你所在用户组仅允许上传的文件大小为:{$groupdb[upfileMaxSize]}K",1);
		}
		if( !$groupdb[upfileMaxSize] && $webdb[upfileMaxSize] && ($webdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而系统仅允许上传的文件大小为:{$webdb[upfileMaxSize]}K",1);
		}
	}
	//$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
	//if(eregi("(.jpg|.png|.gif)$",$filetype)){
		$tempname="{$lfjuid}_".date("YmdHms_").rands(5).$filetype;
	//}else{
	//	$tempname="{$lfjuid}_".date("YmdHms_",time()).base64_encode(urlencode($oldname)).$filetype;
	//	$tempname=str_replace('+','%2B',$tempname);
	//}
	//if(strlen($tempname)>250||strstr($tempname,'+')){
	//	$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	//}
	$newfile="$path/$tempname";

	if(@move_uploaded_file($upfile,$newfile))
	{
		@chmod($newfile, 0777);
		$ck=2;
	}
    if(!$ck)
	{
		if(@copy($upfile,$newfile))
		{
			@chmod($newfile, 0777);
			$ck=2;
		}
	}
	if($ck)
	{	

		if(($array[size]+$lfjdb[usespace])>($webdb[totalSpace]*1048576+$groupdb[totalspace]*1048576+$lfjdb[totalspace])){
			//有的用户组不限制空间大小,$array[updateTable]
			if(!$groupdb[AllowUploadMax]){
				unlink($newfile);
				showerr("你的空间不足,上传失败,你可以联系管理员帮你增大空间!",1);
			}
		}
		$db->query("UPDATE {$pre}memberdata SET usespace=usespace+'$size' WHERE uid='$lfjuid' ");

		//对附件做处理,删除冗余的附件.对附件做个记录
		//$url=str_replace("$webdb[updir]/","",$array[path]);
		//$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
		$db->query("INSERT INTO `{$pre}upfile` ( `uid` , `posttime` , `url` , `filename`  ) VALUES ('$lfjuid','$timestamp','$filename','$tempname' )");
		//setcookie("IF_upfile",$timestamp);

		return $tempname;
	}
	else
	{
		showerr("请检查空间问题,上传失败",1);
	}
}

/**
*生成目录
**/
function makepath($path){
	//这个\没考虑
	$path=str_replace("\\","/",$path);
	$ROOT_PATH=str_replace("\\","/",ROOT_PATH);
	$detail=explode("/",$path);
	foreach($detail AS $key=>$value){
		if($value==''&&$key!=0){
			//continue;
		}
		$newpath.="$value/";
		if((eregi("^\/",$newpath)||eregi(":",$newpath))&&!strstr($newpath,$ROOT_PATH)){continue;}
		if( !is_dir($newpath) ){
			if(substr($newpath,-1)=='\\'||substr($newpath,-1)=='/')
			{
				$_newpath=substr($newpath,0,-1);
			}
			else
			{
				$_newpath=$newpath;
			}
			if(!is_dir($_newpath)&&!mkdir($_newpath)&&ereg("^\/",ROOT_PATH)){
				return 'false';
			}
			@chmod($newpath,0777);
		}
	}
	return $path;
}

/**
*取得真实目录
**/
function tempdir($file,$type=''){
	global $webdb;
	if($type=='pwbbs'){
		global $db_attachname;
		if(is_file(ROOT_PATH."$webdb[passport_path]/$db_attachname/thumb/$file")){
			$file="$webdb[passport_url]/$db_attachname/thumb/$file";
		}else{
			$file="$webdb[passport_url]/$db_attachname/$file";
		}
		return $file;
	}elseif($type=='dzbbs'){
		global $_DCACHE;
		$file="$webdb[passport_url]/{$_DCACHE[settings][attachurl]}/$file";
		return $file;
	}elseif( ereg("://",$file)||ereg("^/./",$file) ){
		return $file;
	}elseif($webdb[mirror]&&!file_exists(ROOT_PATH."$webdb[updir]/$file")){	//FTP镜像点
		return $webdb[mirror].((eregi('\/$',$webdb[mirror])||eregi('^\/',$file))?'':'/').$file;
	}else{
		return $webdb[www_url].'/'.$webdb[updir].((eregi('\/$',$webdb[updir])||eregi('^\/',$file))?'':'/').$file;
	}
}

/**
*截取字符
**/
function get_word($content,$length,$more=1) {
	if(WEB_LANG=='utf-8'){
		$content = get_utf8_word($content, $length,$more);
		return $content;
	}

	if(WEB_LANG=='big5'){
		$more=1;	//不这样的话.截取字符容易使用页面乱码
	}

	if(!$more){
		$length=$length+2;
	}
	if($length>10){
		$length=$length-2;
	}
	if($length && strlen($content)>$length){
		$num=0;
		for($i=0;$i<$length-1;$i++) {
			if(ord($content[$i])>127){
				$num++;
			}
		}
		$num%2==1 ? $content=substr($content,0,$length-2):$content=substr($content,0,$length-1);
		$more && $content.='..';
	}
	return $content;
}

/**
*UTF8截取字符
**/
function get_utf8_word($string, $length, $more=1 ,$dot = '..') {
	$more || $dot='';
	if(strlen($string) <= $length) {
		return $string;
	}

	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

	$strcut = '';
	if( 1 ) {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		$_length = $length - 1;
		for($i = 0; $i < $length; $i++) {
			if(ord($string[$i]) <= 127) {
				$strcut .= $string[$i];
			} else if($i < $_length) {
				$strcut .= $string[$i].$string[++$i];
			}
		}
	}

	$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return $strcut.$dot;
}


/**
*过滤安全字符
**/
function filtrate($msg){
	//$msg = str_replace('&','&amp;',$msg);
	//$msg = str_replace(' ','&nbsp;',$msg);
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39;',$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	//$msg = str_replace("\t","   &nbsp;  &nbsp;",$msg);
	//$msg = str_replace("\r","",$msg);
	//$msg = str_replace("   "," &nbsp; ",$msg);
	return $msg;
}

/*过滤不健康的字*/
function replace_bad_word($str){
	global $Limitword;
	@include_once(ROOT_PATH."data/limitword.php");
	foreach( $Limitword AS $old=>$new){
		strlen($old)>2 && $str=str_replace($old,trim($new),$str);
	}
	return $str;
}


/**
*取固定图片大小
**/
function pic_size($pic,$w,$h,$url){
	global $updir,$webdb,$N_path;
	$rand=rands(5);
	$show="<script>
			function resizeimage_$rand(obj) {
				var imageObject;
				var MaxW = $w;
				var MaxH = $h;
				imageObject = obj;
				var oldImage = new Image();
				oldImage.src = imageObject.src;
				var dW = oldImage.width;
				originalw=dW;
				var dH = oldImage.height;
				originalh=dH;
				if (dW>MaxW || dH>MaxH) {
					a = dW/MaxW;
					b = dH/MaxH;
					if (b>a) {
						a = b;
					}
					dW = dW/a;
					dH = dH/a;
				}
				if (dW>0 && dH>0) {
					imageObject.width = dW;
					imageObject.height = dH;
				}
			}
			</script>";
	return "$show<a href='$url' target='_blank'><img onload='resizeimage_$rand(this)' src='$pic' border=0 width='$w' height='$h'></a>";
}

/**
*模板相关函数
**/
function html($html,$tpl=''){
	global $STYLE;
	if($tpl&&strstr($tpl,substr(ROOT_PATH,0,-1))&&file_exists($tpl))
	{
		return $tpl;
	}
	elseif($tpl&&file_exists(ROOT_PATH.$tpl))
	{
		return ROOT_PATH.$tpl;
	}
	elseif(file_exists(ROOT_PATH."template/".$STYLE."/".$html.".htm"))
	{
		return ROOT_PATH."template/".$STYLE."/".$html.".htm";
	}
	elseif(file_exists(ROOT_PATH."template/default/".$html.".htm"))
	{
		return ROOT_PATH."template/default/".$html.".htm";
	}
}

/**
*分页
**/
function getpage($table,$choose,$url,$rows=20,$total=''){
	global $page,$db;
	if(!$page){
		$page=1;
	}
	//当存在$total的时候.就不用再读数据库
	if(!$total && $table){
		$query=$db->get_one("SELECT COUNT(*) AS num  FROM $table $choose");
		$total=$query['num'];
	}
	$totalpage=@ceil($total/$rows);
	$nextpage=$page+1;
	$uppage=$page-1;
	if($nextpage>$totalpage){
		$nextpage=$totalpage;
	}
	if($uppage<1){
		$uppage=1;
	}
	$s=$page-3;
	if($s<1){
		$s=1;
	}
	$b=$s;
	for($ii=0;$ii<6;$ii++){
		$b++;
	}
	if($b>$totalpage){
		$b=$totalpage;
	}
	for($j=$s;$j<=$b;$j++){
		if($j==$page){
			$show.=" <a href='#' class='ck'><font color=red>$j</font></a>";
		}else{
			$show.=" <a href=\"$url&page=$j\" title=\"第{$j}页\">$j</a>";
		}
	}
	$showpage="<a href=\"$url&page=1\" title=\"首页\">首页</A> <a href=\"$url&page=$uppage\" title=\"上一页\">上一页</A>  {$show}  <a href=\"$url&page=$nextpage\" title=\"下一页\">下一页</A> <a href=\"$url&page=$totalpage\" title=\"尾页\">尾页</A> <a href='#'><font color=red>$page</font>/$totalpage/$total</a>";
    if($totalpage>1){
		return $showpage;
	}
}

/**
*页面跳转函数
**/
function refreshto($url,$msg,$time=1){
	global $webdb,$IsMob;
	if($time==0){
		header("location:$url");
	}else{
		if($IsMob && !defined('IS_ADMIN')){
			require(html('waprefreshto'));
			exit;
		}
		require(html('refreshto'));
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}


/**
*警告页面函数
**/
function showerr($showerrMsg,$type=''){ 
	global $webdb,$IsMob,$lfjid,$lfjdb,$ltitle;
	if($IsMob && $type==-2){
		require_once(ROOT_PATH."inc/weixin.jsdk.php"); //监控微信分享事件
		$jssdk = new JSSDK($webdb[wxpay_AppID], $webdb[wxpay_AppSecret]);
		$signPackage = $jssdk->GetSignPackage();
		require(html('wx_close'));
		exit;
	}
	if($IsMob && !defined('IS_ADMIN')){
		if(strstr($showerrMsg,'没登录')||strstr($showerrMsg,'先登录')){
			global $WEBURL,$IsMob;
			if(!$IsMob){
				wx_pc_login();	//请先登录！
			}
			$URL="$webdb[www_url]/do/waplogin.php?fromurl=".urlencode($WEBURL);
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$URL'>";
			exit;
		}
		require(html('wapshowerr'));
		exit;
	}
	require_once(ROOT_PATH."data/level.php");
	if($type==1){
		$showerrMsg=str_replace("'","\'",$showerrMsg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		alert('$showerrMsg');
		if(document.referrer==''&&window.self==window.top){
			window.self.close();
		}else{
			history.back(-1);
		}		
		//-->
		</SCRIPT>";
	}elseif($type==-1){
		$showerrMsg=str_replace("'","\'",$showerrMsg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		alert('$showerrMsg');
		window.self.close();
		//-->
		</SCRIPT>";
	}else{
		//extract($GLOBALS);
		require(ROOT_PATH."template/default/showerr.htm");
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}

 
/**
*取得随机字符
**/
function rands($length,$strtolower=1) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	if($strtolower==1){
		$hash=strtolower($hash);
	}
	return $hash;
}

/**
*简体中文转UTF8编码
**/
function gbk2utf8($text) {
	if(function_exists('iconv')){
		$text = iconv('GBK', 'UTF-8//IGNORE',$text);
		return $text;
	}

	$fp = fopen(ROOT_PATH."inc/gbkcode/gbk2utf8.table","r");
	while(! feof($fp)) {
		list($gb,$utf8) = fgetcsv($fp,10);
		$charset[$gb] = $utf8;
	}
	fclose($fp);		//以上读取对照表到数组备用wl__hd_sg2_02.gif

	//提取文本中的成分，汉字为一个元素，连续的非汉字为一个元素
	preg_match_all("/(?:[\x80-\xff].)|[\x01-\x7f]+/",$text,$tmp);
	$tmp = $tmp[0];
	//分离出汉字
	$ar = array_intersect($tmp, array_keys($charset));
	//替换汉字编码
	foreach($ar as $k=>$v)
    $tmp[$k] = $charset[$v];
	//返回换码后的串
	return join('',$tmp);
}


/**
*加密与解密函数
**/
function mymd5($string,$action="EN",$rand=''){ //字符串加密和解密 
	global $webdb;
	if($action=="DE"){//处理+号在URL传递过程中会异常
		$string = str_replace('QIBO|ADD','+',$string);
	}
    $secret_string = $webdb[mymd5].$rand.'5*j,.^&;?.%#@!'; //绝密字符串,可以任意设定 
	if(!is_string($string)){
		$string=strval($string);
	}
    if($string==="") return ""; 
    if($action=="EN") $md5code=substr(md5($string),8,10); 
    else{ 
        $md5code=substr($string,-10); 
        $string=substr($string,0,strlen($string)-10); 
    }
    //$key = md5($md5code.$_SERVER["HTTP_USER_AGENT"].$secret_string);
	$key = md5($md5code.$secret_string); 
    $string = ($action=="EN"?$string:base64_decode($string)); 
    $len = strlen($key); 
    $code = "";
    for($i=0; $i<strlen($string); $i++){ 
        $k = $i%$len; 
        $code .= $string[$i]^$key[$k]; 
    }
    $code = ($action == "DE" ? (substr(md5($code),8,10)==$md5code?$code:NULL) : base64_encode($code)."$md5code");
	if($action=="EN"){//处理+号在URL传递过程中会异常
		$code = str_replace('+','QIBO|ADD',$code);
	}
    return $code; 
}

function pwd_md5($code){
	return md5($code);
}


function set_cookie($name,$value,$cktime=0){
	global $webdb,$timestamp;
	if($cktime!=0){
		$cktime=$timestamp+$cktime;
	}
	if($value==''){
		$cktime=$timestamp-31536000;
	}
	$S = $_SERVER['SERVER_PORT'] == '443' ? 1:0;
	if($webdb[cookiePath]){
		$path=$webdb[cookiePath];
	}else{
		$path="/";
	}
	$domain=$webdb[cookieDomain];
	setCookie("$webdb[cookiePre]$name",$value,$cktime,$path,$domain,$S);
}

function get_cookie($name){
	global $webdb;
    return $_COOKIE["$webdb[cookiePre]$name"];
}


function add_user($uid,$money,$about=''){
	global $db,$pre,$timestamp,$webdb,$pre,$city_id;
	$money = intval($money);
	if(!$money||!$uid){
		return ;
	}
	plus_money($uid,$money,$moneytype='');
	$about = addslashes($about);
	$webdb[moneylog_num]>0 || $webdb[moneylog_num] = 500;		//只保存每个用户的最近500条记录
	@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$pre}moneylog` WHERE uid='$uid'"));
	if($NUM>$webdb[moneylog_num]){
		$num=$NUM-$webdb[moneylog_num]+1;
		$db->query("DELETE FROM `{$pre}moneylog` WHERE uid='$uid' ORDER BY id ASC LIMIT $num");
	}
	if(!table_field("{$pre}moneylog","city_id")){
		$db->query("ALTER TABLE  `{$pre}moneylog` ADD  `city_id` MEDIUMINT( 7 ) NOT NULL");
		$db->query("ALTER TABLE  `{$pre}moneylog` ADD INDEX (  `city_id` )");
	}
	//添加积分变化日志
	$db->query("INSERT INTO `{$pre}moneylog` ( `uid` , `money` , `about` , `posttime` , `city_id` ) VALUES ('$uid', '$money', '$about', '$timestamp','$city_id')");
}

//增减用户积分
function plus_money($uid,$money,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;

	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs' )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}memberdata SET $moneytype=$moneytype+'$money' WHERE uid='$uid'");
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}members SET extcredits2=extcredits2+'$money' WHERE uid='$uid'");
		}
	}
	else
	{
		$db->query("UPDATE {$pre}memberdata SET money=money+'$money' WHERE uid='$uid'");
	}
}


//sock方式打开远程文件
function sockOpenUrl($url,$method='GET',$postValue='',$Referer='Y'){
	if($Referer=='Y'){
		$Referer=$url;
	}
	$method = strtoupper($method);
	if(!$url){
		return '';
	}elseif(!ereg("://",$url)){
		$url="http://$url";
	}
	$urldb=parse_url($url);
	$port=$urldb[port]?$urldb[port]:80;
	$host=$urldb[host];
	$query='?'.$urldb[query];
	$path=$urldb[path]?$urldb[path]:'/';
	$method=$method=='GET'?"GET":'POST';

	if(function_exists('fsockopen')){
		$fp = fsockopen($host, $port, $errno, $errstr, 30);
	}elseif(function_exists('pfsockopen')){
		$fp = pfsockopen($host, $port, $errno, $errstr, 30);		
	}elseif(function_exists('stream_socket_client')){
		$fp = stream_socket_client($host.':'.$port, $errno, $errstr, 30);	
	}else{
		die("服务器不支持以下函数:fsockopen,pfsockopen,stream_socket_client操作失败!");
	}
	if(!$fp)
	{
		echo "$errstr ($errno)<br />\n";
	}
	else
	{
		$out = "$method $path$query HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Cookie: c=1;c2=2\r\n";
		$out .= "Referer: $Referer\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Connection: Close\r\n";
		if ( $method == "POST" ) {
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$length = strlen($postValue);
			$out .= "Content-Length: $length\r\n";
			$out .= "\r\n";
			$out .= $postValue;
		}else{
			$out .= "\r\n";
		}
		fwrite($fp, $out);
		while (!feof($fp)) {
			$file.= fgets($fp, 256);
		}
		fclose($fp);
		if(!$file){
			return '';
		}
		$ck=0;
		$string='';
		$detail=explode("\r\n",$file);
		foreach( $detail AS $key=>$value){
			if($value==''){
				$ck++;
				if($ck==1){
					continue;
				}
			}
			if($ck){
				$stringdb[]=$value;
			}
		}
		$string=implode("\r\n",$stringdb);
		//$string=preg_replace("/([\d]+)(.*)0/is","\\2",$string);
		return $string;
	}
}

/*统计附件*/
function get_content_attachment($str){
	global $webdb;
	$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[www_url] );
	preg_match_all("/$rule\/([a-z_\.0-9A-Z]+)\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array);
	$filedb=$array[2];
	foreach($filedb AS $key=>$value){
		if(strstr($value,'baidu/dialogs/attachment/'))unset($filedb[$key]);
	}
	if($webdb[mirror]){
		$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[mirror] );
		preg_match_all("/$rule\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array2);
		if( is_array($filedb) ){
			$filedb+=$array2[1];
		}else{
			$filedb=$array2[1];
		}
	}
	return $filedb;
}

/*删除附件*/
function delete_attachment($uid,$str){
	global $webdb,$db,$pre;
	if(!$str||!$uid){
		return ;
	}
	//真实地址还原
	$str=En_TruePath($str,0);

	$filedb=get_content_attachment($str);
	foreach( $filedb AS $key=>$value){
		$name=basename($value);
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		
		if($detail[0]&&$detail[0]==$uid){
			$turepath=ROOT_PATH.$webdb[updir]."/".$value;
			
			if($rs=$db->get_one("SELECT * FROM {$pre}upfile WHERE filename='$name'")){
				if($rs[num]>1){
					$db->query("UPDATE `{$pre}upfile` SET `num`=`num`-1 WHERE filename='$name'");
					continue;
				}
				$db->query("DELETE FROM `{$pre}upfile` WHERE filename='$name'");
			}
			$size=@filesize($turepath);
			$size && @unlink($turepath);
			//删除FTP上的资源
			if(!$size&&$webdb[ArticleDownloadUseFtp]){
				$value && $size=ftp_delfile($value);
			}
			$db->query(" UPDATE {$pre}memberdata SET usespace=usespace-'$size' WHERE uid='$uid' ");
		}
	}
}

/*移动附件*/
function move_attachment($uid,$str,$newdir,$type=''){
	global $webdb;
	if(!$str||!$uid||!$newdir){
		return $str;
	}
	$filedb = get_content_attachment($str);
	foreach($filedb AS $value){
		if(eregi("^".preg_replace("/([^\/]+)(.*)/is","\\1",$newdir),$value)){
			continue;	//同一个目录的文件就不要再移动了
		}
		$name=basename($value);
		$ture_old_path = ROOT_PATH."$webdb[updir]/$value";
		$ture_new_path = ROOT_PATH."$webdb[updir]/$newdir/$name";

		if(is_file($ture_new_path)||!is_file($ture_old_path)){
			continue;
		}
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		if($detail[0] && $detail[0]==$uid){
			
			if(!is_dir(ROOT_PATH.$webdb[updir]."/".$newdir)){			
				makepath(ROOT_PATH.$webdb[updir]."/".$newdir);
			}

			//自动缩小太大张的图片
			if( $webdb[ArticlePicWidth] && $webdb[ArticlePicHeight] && eregi("(gif|png|jpg)$",$ture_old_path) ){
				$img_array=getimagesize($ture_old_path);
				if($img_array[0]>$webdb[ArticlePicWidth]||$img_array[1]>$webdb[ArticlePicHeight]){
					gdpic($ture_old_path,$ture_old_path,$webdb[ArticlePicWidth],$webdb[ArticlePicHeight],1);
				}
			}

			//图片加水印
			if( $type!='small' && $webdb[is_waterimg] && eregi("(gif|png|jpg)$",$ture_old_path) ){			
				include_once(ROOT_PATH."inc/waterimage.php");
				imageWaterMark($ture_old_path,$webdb[waterpos],ROOT_PATH.$webdb[waterimg]);
			}

			if( @rename($ture_old_path,$ture_new_path) ){			
				$str=str_replace("$value","$newdir/$name",$str);
			}
		}
	}
	return $str;
}

//对真实地址做处理
function En_TruePath($content,$type=1,$ifgetplayer=0){
	global $webdb;
	if($type==1)
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=str_replace("$webdb[mirror]","http://www_qibosoft_com/Tmp_updir",$content);
		}
		$content=str_replace("$webdb[www_url]/$webdb[updir]","http://www_qibosoft_com/Tmp_updir",$content);		
		$content=str_replace("$webdb[www_url]","http://www_qibosoft_com",$content);
	}
	else
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=preg_replace("/http:\/\/www_php168_com\/Tmp_updir([-_=\/\.A-Za-z0-9]+)/eis","tempdir('\\1')",$content);
			$content=preg_replace("/http:\/\/www_qibosoft_com\/Tmp_updir([-_=\/\.A-Za-z0-9]+)/eis","tempdir('\\1')",$content);
		}else{
			$content=str_replace("http://www_php168_com/Tmp_updir","$webdb[www_url]/$webdb[updir]",$content);
			$content=str_replace("http://www_qibosoft_com/Tmp_updir","$webdb[www_url]/$webdb[updir]",$content);
		}		
				
		$content=str_replace("http://www_php168_com","$webdb[www_url]",$content);
		$content=str_replace("http://www_qibosoft_com","$webdb[www_url]",$content);
		if($ifgetplayer){

			//针对百度编辑器
			$content=preg_replace("/<embed([^>]+)class=\"edui-faked-video\"([^>]+)\/>/eis","get_play_element('\\2')",$content);
			$content=preg_replace("/\[(mp3|flv|wmv|flash|rmvb),([\d]+),([\d]+)\]([^\[]+)\[\/(mp3|flv|wmv|flash|rmvb)\]/eis","player('\\4','\\2','\\3','true','\\1')",$content);
		}
		//自动补全一些不对称的TABLE,TD,DIV标签
		//$content=check_html_format($content);
	}
	return $content;
}

//针对百度编辑器
function get_play_element($str){
	$str = stripslashes($str);
	preg_match_all("/ ([\w]+)=\"([^\"]+)\"/is",$str,$array);
	$Ar = array('src','width','height','play','vtype');
	foreach($array[1] AS $key=>$value){
		if(in_array($value,$Ar)){
			$$value = $array[2][$key];	
		}
	}
	$code = player($src,$width,$height,$play,$vtype);
	return $code;
}

//获取所有子栏目
function Get_SonFid($table,$fid=0){
	global $db;
	$query = $db->query("SELECT fid,sons FROM $table WHERE fup=$fid");
	while($rs = $db->fetch_array($query)){
		if($rs[sons]){
			$array2=Get_SonFid($table,$rs[fid]);
			if($array2){
				foreach( $array2 AS $key=>$value){
					$array[]=$value;
				}
			}
		}
		$array[]=$rs[fid];
	}
	return $array;
}

//静态网页处理
function Explain_HtmlUrl(){
	global $fid,$aid,$id,$page,$WEBURL;
	$detail=explode("fid-",$WEBURL);
	$detail2=explode(".",$detail[1]);
	$rs=explode("-",$detail2[0]);
	$fid=$rs[0];					//LIST页的fid,bencandy页的fid
	$rs[1] && $$rs[1]=$rs[2];		//可能是LIST页的PAGE,也可能是bencandy页的id
	$rs[3] && $$rs[3]=$rs[4];		//bencandy页的page
}


//获取用户积分
function get_money($uid,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;
	
	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs'&&$webdb[passport_type] )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}memberdata WHERE uid='$uid'");
			return $rs[$moneytype];
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}members WHERE uid='$uid'");
			return $rs[extcredits2];
		}
	}
	else
	{
		if($lfjdb[uid]==$uid)
		{
			return $lfjdb[money];
		}
		else
		{
			$rs=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
			return $rs[money];
		}
	}
}



/*页面显示,强制过滤关键字*/
function kill_badword($content){
	global $webdb,$Limitword;
	if($webdb[kill_badword])
	{
		if(!$content)
		{
			$content=@ob_get_contents();
			$ck++;
		}
		
		@include_once(ROOT_PATH."data/limitword.php");

		foreach( $Limitword AS $key=>$value){
			$content=str_replace($key,$value,$content);
		}
		if($ck)
		{
			ob_end_clean();
			ob_start();
			echo $content;
		}
		else
		{
			return $content;
		}
	}
	else
	{
		return $content;
	}
}

function send_msg($uid,$title,$content,$fromuid=0){
	global $lfjid;
	$fromer = $fromuid?$lfjid:'SYSTEM';
	$array = array(
		'touid' => $uid,
		'fromuid' => $fromuid,
		'title' => $title,
		'content' => $content,
		'fromer' => $fromer,
		);
	pm_msgbox($array);
}

//发站内消息
function pm_msgbox($array){
	global $db,$pre,$timestamp,$webdb,$TB_pre,$TB,$userDB,$db_modes;
	$array[content] = addslashes($array[content]);
	$array[title] = addslashes($array[title]);

	if(strlen($array[title])>130){
		showerr("标题不能大于65个汉字");
	}
	$db->query("INSERT INTO `{$pre}pm` (`touid`,`fromuid`, `username`, `type`, `ifnew`, `title`, `mdate`, `content`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$array[title]', '$timestamp', '$array[content]')");
}



//主要是给发表文章或修改文章时调用
function get_html_url(){
	global $rsdb,$aid,$fidDB,$webdb,$fid,$page,$showHtml_Type,$Html_Type;
	$id=$aid;
	if($page<1){
		$page=1;
	}
	$postdb[posttime]=$rsdb[posttime];

	if($showHtml_Type[bencandy][$id]){
		$filename_b=$showHtml_Type[bencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[bencandy_filename];
	}
	//对于内容页的首页把$page去除
	if($page==1){
		$filename_b=preg_replace("/(.*)(-{\\\$page}|_{\\\$page})(.*)/is","\\1\\3",$filename_b);
	}
	$dirid=floor($aid/1000);
	//对于内容页的栏目小于1000篇文章时,把DIR分目录去除
	if($dirid==0){
		$filename_b=preg_replace("/(.*)(-{\\\$dirid}|_{\\\$dirid})(.*)/is","\\1\\3",$filename_b);
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[list_filename];
	}	
	if($page==1){
		if($webdb[DefaultIndexHtml]==1){
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.html",$filename_l);
		}else{
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.htm",$filename_l);
		}
	}
	eval("\$array[_showurl]=\"$filename_b\";");
	eval("\$array[_listurl]=\"$filename_l\";");
	//自定义了栏目域名
	if($Html_Type[domain][$fid]&&$Html_Type[domain_dir][$fid]){
		$rule=str_replace("/","\/",$Html_Type[domain_dir][$fid]);
		$filename_b=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_b);
		$filename_l=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_l);
		//特别处理一下些自定义内容页文件名的情况.
		if(!eregi("^http:\/\/",$filename_b)){
			$filename_b="$webdb[www_url]/$filename_b";
		}
	}else{
		$filename_b="$webdb[www_url]/$filename_b";
		$filename_l="$webdb[www_url]/$filename_l";
	}

	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

//获取专题页的静态URL地址
function get_SPhtml_url($fidDB,$id='',$posttime=''){
	global $webdb,$showHtml_Type,$Html_Type;
	$page=1;
	$fid=$fidDB[fid];
	$postdb[posttime]=$posttime;
	
	if($showHtml_Type[SPbencandy][$id]){
		$filename_b=$showHtml_Type[SPbencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[SPbencandy_filename];
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[SPlist_filename];
	}
	$filename_b="$webdb[www_url]/$filename_b";
	$filename_l="$webdb[www_url]/$filename_l";
	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

//一般用在内容页的提示!
function Remind_msg($MSG){
	global $rsdb;
	$rsdb[content].= "<SCRIPT LANGUAGE='JavaScript'>
	<!--
	alert('$MSG');
	//-->
	</SCRIPT>";
}


//生成系统模块缓存
function make_module_cache(){
	global $db,$pre;
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){	
		$listdb[$rs['pre']]=$rs;
	}
	write_file(ROOT_PATH."data/module.php",'<?php  $ModuleDB = '.var_export($listdb,true).';?>');
}

//根据IP获取来源地
function ipfrom($ip) {
	if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
		return '';
	}
	if( !is_file(ROOT_PATH.'inc/ip.dat') ){
		return '<a title><A HREF="http://down.qibosoft.com/ip.rar" title="点击下载后,解压放到整站/inc/目录即可">IP库不存在,请点击下载一个!</A></a>';
	}
	if($fd = @fopen(ROOT_PATH.'inc/ip.dat', 'rb')) {

		$ip = explode('.', $ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;

		$BeginNum = 0;
		$EndNum = $ipAllNum;

		while($ip1num > $ipNum || $ip2num < $ipNum) {
			$Middle= intval(($EndNum + $BeginNum) / 2);

			fseek($fd, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fd, 4);
			if(strlen($ipData1) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);

			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}

			$DataSeek = fread($fd, 3);
			if(strlen($DataSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($fd, $DataSeek);
			$ipData2 = fread($fd, 4);
			if(strlen($ipData2) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if($ip2num < 0) $ip2num += pow(2, 32);

			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					fclose($fd);
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}

		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($fd, 3);
			if(strlen($ipSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($fd, $ipSeek);
			$ipFlag = fread($fd, 1);
		}

		if($ipFlag == chr(2)) {
			$AddrSeek = fread($fd, 3);
			if(strlen($AddrSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;

			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($fd, $AddrSeek);

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;
		} else {
			fseek($fd, -1, SEEK_CUR);
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;

			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;
		}
		fclose($fd);

		if(preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
		$ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}

		if(WEB_LANG=='big5'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","BIG5",$ipaddr,ROOT_PATH."./inc/gbkcode/");
			$ipaddr = $cnvert->ConvertIT();
		}elseif(WEB_LANG=='utf-8'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","UTF8",$ipaddr,ROOT_PATH."./inc/gbkcode/");
			$ipaddr = $cnvert->ConvertIT();
		}

		return $ipaddr;
	}
}

function ftp_upfile($source,$file,$ifdel=1){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(ROOT_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$path=dirname($file);
	$detail=explode("/",$path);
	//$pathname=$webdb[FtpDir];
	foreach( $detail AS $key=>$value){
		$pathname.="$value/";
		if(!$ftp->dir_exists($pathname)){
			$ftp->mkd($pathname);
		}
	}
	$ifput=$ftp->upload($source,$file);
	$ftp->close();
	if($ifput){
		$ifdel && unlink($source);
		return "$webdb[mirror]/$file";
	}else{
		return "$webdb[www_url]/$webdb[updir]/$file";
	}
}

function ftp_delfile($file){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(ROOT_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$size = $ftp->size($file,0);
	$ftp->delete($file);
	$ftp->close();
	return $size;
}

//发送手机短信
function sms_send($mob,$content){
	global $webdb;

	if(!@eregi("^http",$webdb[sms_api_url]) || !$webdb[sms_api_code]){
		showerr("系统没有设置好短信接口!");
	}

	$webdb[sms_api_url] = str_replace('$mob',$mob,$webdb[sms_api_url]);

	$detail = explode('=',$webdb[sms_api_url]);
	foreach($detail AS $key=>$value){

		if(!strstr($value,'$content')){
			continue;
		}
		list($txt,$ext)=explode('&',$value);
		$txt = str_replace('$content',$content,$txt);
		if(WEB_LANG=='gb2312' && $webdb[sms_must_utf8]){
			$txt = gbk2utf8($txt);
		}
		$txt = rawurlencode($txt);
		$detail[$key]="$txt&$ext";

	}
	$url = implode('=',$detail);
	
	$code = file_get_contents($url);

	if($code == ''){
		$code = sockOpenUrl($url);
	}
	
	if(strstr($code,trim($webdb[sms_api_code]))){
		return 1;
	}else{
		return 0;
	}
}


/**
自定义模型当中,获取这三个select,radio,checkbox表单中类似“
1|中国
2|美国
”真实值
**/
function SRC_true_value($rs,$rsdb_v){
	if($rs[form_type]=='radio'||$rs[form_type]=='select'){
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if($v1==$rsdb_v&&$v2){
				$rsdb_v=$v2;
				break;
			}
		}
	}elseif($rs[form_type]=='checkbox'){
		$detail2=explode("/",$rsdb_v);
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if(in_array($v1,$detail2)&&$v2){
				foreach( $detail2 AS $key2=>$value2){
					if($value2==$v1){
						$detail2[$key2]=$v2;
						break;
					}
				}
			}
		}
		$rsdb_v=implode(" , ",$detail2);
	}
	return $rsdb_v;
}


//用户登录
function user_login($username,$password,$cookietime){
	global $userDB;
	$rs = $userDB->login($username,$password,$cookietime);
	return $rs;
}

//获取UNIX时间,主要是特别处理变成整数.不加引号08与8会不一样的结果,加引号是正常的
function mk_time($h,$i, $s, $m, $d, $y){
	$time=@mktime(intval($h),intval($i),intval($s),intval($m),intval($d),intval($y));
	return $time;
}


//检测某个关键字是否包含在数组里边
function ifin_array($array,$filename,$ISin=''){
	foreach($array as $key=>$value){
		if(!is_array($value)){
			if(strstr($value,$filename)){
				$ISin=1;
				break;
			}
		}elseif(!$ISin){
			$ISin=ifin_array($array[$key],$filename,$ISin);
		}
	}
	return $ISin;
}


/*讯雷联盟*/
function Thunder_Encode($url) 
{
	$thunderPrefix="AA";
	$thunderPosix="ZZ";
	$thunderTitle="thunder://";
	$thunderUrl=$thunderTitle.base64_encode($thunderPrefix.$url.$thunderPosix);
	return $thunderUrl;
}


/*快车联盟*/
function Flashget_Encode($t_url,$uid) 
{
	$prefix= "Flashget://";
	$FlashgetURL=$prefix.base64_encode("[FLASHGET]".$t_url."[FLASHGET]")."&".$uid;
	return $FlashgetURL;
}

//播放器
function player($url,$width=400,$height=300,$autostart='false',$force=''){
	global $webdb;
	//$urlstring=mymd5($url);
	$urlstring=urlencode($url);
	$string="
<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=playurl&urlstring=$urlstring'></SCRIPT>
<SCRIPT LANGUAGE=\"JavaScript\">
qibo_player(playurl,'$width','$height','$force','$autostart');
</SCRIPT>
";
	return $string;
}


//自动补全一些不对称的TABLE,TD,DIV标签
function check_html_format($string){
	preg_match_all("/<div([^>]*)>/",$string,$array0);
	preg_match_all("/<\/div>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$divNUM=abs($num0-$num1);
	for($i=0;$i<$divNUM;$i++){
		if($num0>$num1){
			$string.="</div>";
		}else{
			$string="<div>$string";
		}
		break;
	}
	preg_match_all("/<td([^>]*)>/",$string,$array0);
	preg_match_all("/<\/td>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tdNUM=abs($num0-$num1);
	for($i=0;$i<$tdNUM;$i++){
		if($num0>$num1){
			$string.="</td>";
		}else{
			$string="<td>$string";
		}
		break;
	}
	preg_match_all("/<table([^>]*)>/",$string,$array0);
	preg_match_all("/<\/table>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tableNUM=abs($num0-$num1);
	for($i=0;$i<$tableNUM;$i++){
		if($num0>$num1){
			$string.="</table>";
		}else{
			$string="<table>$string";
		}
		break;
	}
	if($tdNUM>1||$tdNUM>1||$tableNUM>1){
		$string=check_html_format($string);
	}
	return $string;
}

function get_id_table($id){
	global $Fid_db;
	if(strlen($id)<9){
		return ;
	}
	if(!$Fid_db){
		@include(ROOT_PATH."data/all_fid.php");
	}	
	$tableid=preg_replace("/([0-9]{3})([0-9]{6})/is","\\1",$id);
	$tableid=intval($tableid);
	if(in_array($tableid,$Fid_db[iftable])){
		return $tableid;
	}	
}



function delete_cache_file($fid,$id){
	del_file(ROOT_PATH."cache/jsarticle_cache");
	del_file(ROOT_PATH."cache/label_cache");
	del_file(ROOT_PATH."cache/list_cache");
	del_file(ROOT_PATH."cache/bencandy_cache");
	del_file(ROOT_PATH."cache/showsp_cache");
}

//核对验证码
function check_imgnum($yzimg){
	global $db,$pre,$timestamp,$webdb,$usr_sid;
	$time=$timestamp-1800;	//半小时前的验证码失效.

	if($webdb[YzImg_letter_differ]){	//区别字母大小写
		$SQL=" BINARY ";
	}
	if($db->get_one("SELECT * FROM {$pre}yzimg WHERE $SQL imgnum='$yzimg' AND sid='$usr_sid'")){
		$db->query("DELETE FROM {$pre}yzimg WHERE (imgnum='$yzimg' AND sid='$usr_sid') OR posttime<$time");
		return true;
	}else{
		$db->query("DELETE FROM {$pre}yzimg WHERE sid='$usr_sid' OR posttime<$time");
		return false;
	}
}


//各模块,更新核心设置缓存
function module_write_config_cache($webdbs)
{
	global $db,$_pre,$atc_webdbs;
	
	//checkbox要特别处理
	foreach($atc_webdbs AS $key=>$value){
		if(!$webdbs[$key]){
			$webdbs[$key]='';
		}
	}

	if( is_array($webdbs) )
	{
		foreach($webdbs AS $key=>$value)
		{
			if(is_array($value))
			{
				$webdbs[$key]=$value=implode(",",$value);
			}
			$SQL2.="'$key',";
			$SQL.="('$key', '$value', ''),";
		}
		$SQL=$SQL.";";
		$SQL=str_Replace("'),;","')",$SQL);
		$db->query(" DELETE FROM {$_pre}config WHERE c_key IN ($SQL2'') ");
		$db->query(" INSERT INTO `{$_pre}config` VALUES  $SQL ");	
	}
	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM {$_pre}config");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file(Mpath."data/config.php",$writefile);
}

//发送邮件
function send_mail($email,$title,$content,$ifcheck=1){
	global $webdb;

	if($webdb[MailType]=='smtp'){
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw]){
			if($ifcheck){
				showerr("请先设置邮件服务器");
			}else{
				return false;
			}			
		}

		if($webdb[MailPort]!=25 || @eregi(".qq.com$",$webdb[MailServer])){
			
			if(eregi(".qq.com$",$webdb[MailServer]) && $webdb[MailPort]==25 ){
				$webdb[MailPort]=465;
			}

			require_once(ROOT_PATH."inc/class.phpmailer.php");
			$mail             = new PHPMailer();

			$mail->IsSMTP(); // telling the class to use SMTP

			$mail->CharSet = ( WEB_LANG=='utf-8'?'UTF-8':'GBK' ); //UTF-8设置邮件的字符编码，这很重要，不然中文乱码

			//$mail->AddReplyTo("2244484@qq.com","mckee");//回复地址
			$mail->FromName   = $webdb[webname];

			$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
													   // 1 = errors and messages
													   // 2 = messages only
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = $webdb[MailServer];      // sets GMAIL as the SMTP server
			$mail->Port       = $webdb[MailPort];                   // set the SMTP port for the GMAIL server
			$mail->Username   = $webdb[MailId];  // GMAIL username
			$mail->Password   = $webdb[MailPw];            // GMAIL password

			$mail->From = $webdb[MailId];


			$mail->Subject    = $title;

			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // 当邮件不支持html时备用显示，可以省略

			$mail->MsgHTML($content);

			$mail->AddAddress($email, "");
			//$mail->AddAddress("2211141@qq.com", "John Doe");
			//$mail->IsHTML(true); 

			if(!$mail->Send()) {
				if($ifcheck){
					$failNUM++;
					//showerr('邮件发送失败,请管理员检查服务器配置:'.$mail->ErrorInfo);
				}else{
					$failNUM++;
				}
			} else {
				$succeeNUM++;
			}
		}else{
			require_once(ROOT_PATH."inc/class.mail.php");
			$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
			$smtp->debug = false;
			if($smtp->sendmail($email,$webdb[MailId], $title, $content, "HTML")){
				$succeeNUM++;
			}else{
				$failNUM++;
			}
		}
	}else{
		if(mail($email, $title, $content)){
			$succeeNUM++;
		}else{
			$failNUM++;
		}
	}	
	if($succeeNUM){
		return true;
	}else{
		if($ifcheck){
			showerr('邮件发送失败,请管理员检查服务器配置');
		}else{
			return false;
		}
	}
}


//插件菜单写缓存
function write_hackmenu_cache(){
	global $db,$pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$pre}hack ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if(!$rs[class2]||!$rs[class1]){
			$rs[class1]='other';
			$rs[class2]='插件大全';
		}
		$rs[adminurl]=addslashes($rs[adminurl]);
		$rs[linkname] && $rs[name]=$rs[linkname];
		$rs[name]=addslashes($rs[name]);
		$s="\r\n\$menu_partDB['{$rs[class1]}'][]='{$rs[class2]}';\r\n\$menudb['{$rs[class2]}']['{$rs[name]}']=array('power'=>'{$rs[keywords]}','link'=>'{$rs[adminurl]}');\r\n";
		if($rs[isbiz]){
			$show.="\r\nif(\$IS_BIZPhp168||\$GLOBALS[IS_BIZPhp168]){".$s."}\r\n";
		}else{
			$show.=$s;
		}
	}
	write_file(ROOT_PATH."data/hack.php",$show.'?>');
}


//会员中心模板
function get_member_tpl($type){
	global $webdb;	
	if(!$webdb[style_member]||!is_file($file=ROOT_PATH."member/template/$webdb[style_member]/$type.htm")){
		$file = ROOT_PATH."member/template/default/$type.htm";
	}
	return $file;
}



//全站伪静态
function rewrite_url(&$content){
	$content=preg_replace("/<a([^>]+)href=([\'\"]?)([^\'\"> ]+)([\'\"]?)/eis","rewrite_replace_url('\\3','\\1','\\2','\\4')",$content);
}
function rewrite_replace_url($code3,$code1,$code2,$code4){
	$code3=preg_replace("/(.*)(list|bencandy|listsp|showsp|listall|listhomepage|joinshow)\.php\?(.*)/eis","rewrite_replace_parameter('\\1','\\2','\\3')",$code3);

	$code3=preg_replace("/(.*)\/(home)\/\?(.*)/eis","rewrite_replace_parameter('\\1','\\2','\\3')",$code3);

	//return stripslashes("<a{$code1}href={$code2}{$code3}{$code4}");
	return stripslashes("<a{$code1}href={$code2}").$code3.stripslashes("{$code4}");
}
function rewrite_replace_parameter($path,$filename,$parameter){
	if($filename=='home'){
		$path.='/home/';
		$filename='homepage';
	}elseif($path&&substr($path,-1)!='/'){
		return "{$path}{$filename}.php?{$parameter}";	//对于这种就不能处理的XXlist.php,所以要判断前面是不是/
	}	
	$re='-htm-';
	$filetype='.html';
	$parameter=preg_replace("/^([&]+)(.*)/is","\\2",$parameter);
	$parameter=str_replace(array('&&','&','='),array('&','-','-'),$parameter);
	return "$path$filename$re$parameter$filetype";
}


function select_city($name,$fid='',$value=false){
	global $db,$pre;	
	$show.="<select name='$name'><option value=''>请选择</option>";
	if($value){
		$ck=$fid=='$GLOBALS[city_id]'?'selected':'';
		$show.="<option value='\$GLOBALS[city_id]' $ck>对应城市</option>";
	}
	$query = $db->query("SELECT * FROM {$pre}city ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ck=$fid==$rs[fid]?'selected':'';
		$show.="<option value='$rs[fid]' $ck>$rs[name]</option>";
	}
	$show.="</select>";
	return $show;
}


//城市SEO变量
function seo_eval($string){
	global $city_DB,$fidDB,$city_id,$zone_id,$street_id,$zone_DB,$street_DB;
	$string=str_replace(
		array('{city_name}','{zoon_name}','{street_name}','{sort_name}'),
		array($city_DB['name'][$city_id],$zone_DB['name'][$zone_id],$street_DB['name'][$street_id],$fidDB['name']),
		$string);
	return $string;
}


function delete_home($uid){
	global $db,$pre;
	$db->query("DELETE FROM {$pre}hy_company WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_company_fid WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_friendlink WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_guestbook WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_home WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_news WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_pic WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_picsort WHERE uid='$uid'");
	$db->query("UPDATE {$pre}memberdata SET grouptype=0 WHERE uid='$uid'");
}

//给在线编辑器做特殊字符替换使用的
function editor_replace($content){
	$content = str_replace(
		array('"',"'",'&lt;','&gt;','<','>','&nbsp;'),
		array("&quot;",'&#39;','&amp;lt;','&amp;gt;','&lt;','&gt;',"&amp;nbsp;"),
		$content);
	return $content;
}

//UTF8字符转GBK
function utf82gbk($text){
	if(function_exists('iconv')){
		$text = iconv('UTF-8', 'GBK//IGNORE',$text);
		return $text;
	}
	require_once(ROOT_PATH."inc/class.chinese.php");
	$cnvert = new Chinese("UTF8","GB2312",$text,ROOT_PATH."./inc/gbkcode/");
	$text = $cnvert->ConvertIT();
	unset($cnvert);
	return $text;
}
//判断是不是UTF8字符
function is_utf8($text) { 
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$text) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$text) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$text) == true) 
	{ 
		return true; 
	} 
	else 
	{ 
		return false; 
	}
}

//人民币日志
function add_rmb($uid,$money,$freeze_money,$about=''){
	global $db,$pre,$timestamp,$webdb;
	$money = floatval($money);
	$freeze_money = floatval($freeze_money);
	if( !$uid || ($money==0&&$freeze_money==0) ){
		return ;
	}
	$freeze = 0;
	if($freeze_money == -$money){
		$freeze = 1;	//冻结
	}

	$db->query("UPDATE {$pre}memberdata SET rmb=rmb+$money,rmb_freeze=rmb_freeze+$freeze_money WHERE uid='$uid' ");

	$about = addslashes($about);
	//添加日志
	$db->query("INSERT INTO `{$pre}rmb_consume` ( `uid` , `money` , `about` , `posttime` , `freeze`) VALUES ('$uid', '$money', '$about', '$timestamp' ,'$freeze')");
}

//检查是否为手机访问
function is_mobile(){    
	$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";    
	$regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";    
	$regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";        
	$regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";    
	$regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";    
	$regex_match.=")/i";            
	return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}


//介绍用户注册奖励积分
function propagandize_reg($newuid,$propagandize_man=''){
	global $db,$pre,$onlineip,$timestamp,$userDB,$webdb;
	if($propagandize_man){	//用户注册时手工填写的推荐人
		$rs = $userDB->get_passport($propagandize_man,'name');
		if(!$rs){
			return ;
		}
		if($rs[uid]==$newuid){
			return ;
		}
		$today = date("z",$timestamp);
		$ip=sprintf("%u",ip2long($onlineip));
		$db->query("INSERT INTO `{$pre}propagandize` ( `uid` , `ip` , `day` , `posttime` , `newuid` ,`fromurl`) VALUES ( '$rs[uid]', '$ip', '$today', '$timestamp', '$newuid','注册推荐人')");
		
		//几天以内的同一IP重复注册推荐它人不奖励积分，防止作敝
		$daytime = $today-$webdb['propagandize_LogDay'];
		$ts = $db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}propagandize WHERE day>'$daytime' AND ip='$ip' AND uid='$rs[uid]'");
		if($ts[NUM]<2){
			propagandize_money_reg($newuid);
		}

	}else{	//推荐宣传网址记录下的UID
		$id = get_cookie('propagandize_id');
		if(!$id){
			return ;
		}
		extract($db->get_one("SELECT uid FROM {$pre}propagandize WHERE id='$id'"));
		if(!$uid){
			return ;
		}
		set_cookie('propagandize_id','');
		$db->query("UPDATE {$pre}propagandize SET newuid='$newuid' WHERE id='$id'");
		propagandize_money_reg($newuid);
	}
}
//介绍用户注册奖励积分
function propagandize_money_reg($newuid){
	global $db,$pre,$webdb;
	extract($db->get_one("SELECT uid FROM {$pre}propagandize WHERE newuid='$newuid'"));
	if($uid){
		add_user($uid,$webdb[propagandize_Reg_Money],"推广介绍新用户注册奖励,其uid为:$newuid");
		propagandize_money_reg($uid);
	}
}


//注册页的随机变量处理，防止注册机
function check_rand_reg_num($rand_reg_num){
	global $webdb,$timestamp,$db,$pre;
	if($webdb['rand_reg_num_mktime']<1){
		return true;
	}
	if($webdb['rand_reg_num'] && $rand_reg_num!=$webdb['rand_reg_num']){
		return false;
		//die('系统随机码失效,请返回,刷新一下页面,再重新输入数据,重新提交!');
	}
	if(($timestamp-$webdb['rand_reg_num'])>$webdb['rand_reg_num_mktime']*3600){
		
		$source = 'QWERTYUIOPLKJHGFDSAZXCVBNM';
		for($i=0;$i<rand(1,5);$i++)
		$ck .= $source{mt_rand(0, strlen($source) -1)};
		$webdb['rand_reg_num_inputname']=$ck;
		$webdb['rand_reg_num']=$timestamp;
		$db->query("REPLACE INTO `{$pre}config` (`c_key` ,`c_value` )VALUES ('rand_reg_num', '{$webdb[rand_reg_num]}')");
		$db->query("REPLACE INTO `{$pre}config` (`c_key` ,`c_value` )VALUES ('rand_reg_num_inputname', '{$webdb[rand_reg_num_inputname]}')");
		$writefile="<?php\r\n";
		$query = $db->query("SELECT * FROM {$pre}config");
		while($rs = $db->fetch_array($query)){
			$rs[c_value]=addslashes($rs[c_value]);
			$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
		}
		write_file(ROOT_PATH."data/config.php",$writefile.'?>');
	}
	return true;
}


if (!function_exists('fetch_label_value')) {
	function fetch_label_value($parameter_array) {
		extract($GLOBALS);
		global $label;
		$ch_file = $parameter_array['file'];
		$ch_pagetype = intval($parameter_array['pagetype']);
		$ch_fid = intval($parameter_array['fid']);
		$ch_module = intval($parameter_array['module']);
		require(ROOT_PATH . 'inc/label_module.php');
	} 
}

//页面缓存功能
function web_cache($cacheTime=0){
	global $city_id,$jobs,$MakeIndex,$content,$_pre;
	
	$filename = $_pre ? $_pre : 'index';	
	$Cache_FileName=ROOT_PATH."cache/index_cache/{$city_id}/{$filename}.txt";
	
	
	if(!defined('PAGE_CACHE_TIME')){
		define( 'PAGE_CACHE_TIME' , intval($cacheTime) );		
	}
	
	if($jobs || $MakeIndex || PAGE_CACHE_TIME<1){
		if($jobs=='show' && is_file($Cache_FileName) ){
			@unlink($Cache_FileName);
		}
		return ;
	}

	if($content){	//页面缓存生成
		if( !is_dir(dirname($Cache_FileName)) ){
			makepath(dirname($Cache_FileName));
		}
		write_file($Cache_FileName,$content);
	}else{	//页面缓存调取显示
		if( (time()-filemtime($Cache_FileName))<(PAGE_CACHE_TIME*60) ){
			echo read_file($Cache_FileName);
			exit;
		}	
	}
}


//记录用户的IP与端口访问日志
function set_user_log($tag){
	$typedb = array(
				1=>'注册',
				2=>'登录',
				3=>'退出',
				4=>'发信息',
				5=>'改信息',
				6=>'发评论',
				//7=>'发文章',
				//8=>'改文章',
				9=>'留言本',
				);
	//biz_set_user_log($tag,$typedb);
	global $db,$pre,$onlineip,$lfjuid,$lfjid,$timestamp,$id,$fid,$cid,$WEBURL,$webdb;
	if(!$webdb['if_set_user_log']){
		return ;
	}
	
	if($typedb[$tag]){
		$type = $tag;
		$tag=$typedb[$tag];		
	}
	$port = filtrate($_SERVER['REMOTE_PORT']);
	$about = filtrate("$_SERVER[HTTP_USER_AGENT]\n$_SERVER[HTTP_X_WAP_PROFILE]\n$_SERVER[HTTP_PROFILE]");	
	$db->query("INSERT INTO `{$pre}log` (`systype`, `type`, `tag`, `uid`, `username`, `ip`, `port`, `posttime`, `id`, `fid`,  `cid`,  `about`) VALUES ('$webdb[module_pre]', '$type', '$tag', '$lfjuid', '$lfjid', '$onlineip', '$port', '$timestamp', '$id', '$fid', '$cid', '$about')");
	if($webdb['set_user_log_day']>1){
		$time = $timestamp-3600*24*$webdb['set_user_log_day'];	//只保留几天内的数据
		$db->query("DELETE FROM `{$pre}log` WHERE `posttime`<$time");	
	}
}



//记录微信访问客户openId
function set_weixin_openid(){
	global $webdb;
	if(!$webdb['wxpay_AppID'] || !$webdb['wxpay_AppSecret'] || !$webdb['wxpay_ID'] || !$webdb['wxpay_ApiKey']){
		return ;
	}
	if( $openId=get_cookie('WeiXin_OpenId') ){
		return $openId;
	}	
	require_once ROOT_PATH."inc/olpay/weixin/lib/WxPay.Api.php";
	require_once ROOT_PATH."inc/olpay/weixin/WxPay.JsApiPay.php";
	
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();
	set_cookie('WeiXin_OpenId',$openId,12*3600);
	return $openId;
}


//电脑版提示扫一扫微信登录，自身公众号接口
function wx_pc_login(){
	global $pre,$db,$webdb,$timestamp,$usr_sid,$WXID;
	$md5code = mymd5("$timestamp\t$usr_sid",'EN');
	$db->query("REPLACE INTO  `{$pre}login_check` ( `usr` ,`posttime` ) values ( '$usr_sid', '$timestamp')");
	$WXID || $WXID='NO';
	$URL = urlencode("$webdb[www_url]/do/2codelogin.php?WXID=$WXID&md5code=$md5code");
	require html('ckecklogin');
	exit;
}


//微信接口用来POST数据
function http_Curl($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	//curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	//curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
    if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
		if (curl_errno($curl)) {
			echo 'Errno'.curl_error($curl);
		}
    curl_close($curl);
    return $output;
}

//获取微信的权限参数
function wx_getAccessToken($mustSelf=false){
	global $webdb;
	
	if($mustSelf){	//用回自身的接口资料
		require(ROOT_PATH.'data'.WEB_DIR.'/config.php');
	}
	$mymd5 = $webdb['WXFmymd5'] ? $webdb['WXFmymd5'] : $webdb[mymd5] ;
	$path = $webdb['WXFweb_dir'] ? $webdb['WXFweb_dir'] : $webdb[web_dir] ;
    $data = json_decode(file_get_contents(ROOT_PATH."cache{$path}/access_token{$mymd5}.json"));
    if ($data->expire_time < time() ||$mustSelf) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$webdb[wxpay_AppID]&secret=$webdb[wxpay_AppSecret]";
      $res = json_decode(http_Curl($url));
      $access_token = $res->access_token;
      if ($mustSelf==false && $access_token) {
        $data->expire_time = time() + 1800;
        $data->access_token = $access_token;
        $fp = fopen(ROOT_PATH."cache{$path}/access_token{$mymd5}.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
}

//检查是否有关注公众号
function wx_check_attention($openid){
	if(!$openid){
		return false;
	}
	$ac=wx_getAccessToken();
	$s=json_decode( http_Curl("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$ac&openid=$openid&lang=zh_CN"));
	if($s->subscribe==1){
		return true;
	}else{
		return false;
	}
}

//给微信用户发信息
function send_wx_msg($openid,$content,$array=array()){
	if($openid=='')return ;

	if($array[type]=='image'){
		$data="{
		\"touser\":\"$openid\",
		\"msgtype\":\"image\",
		\"image\":
		{
			 \"media_id\":\"$array[id]\"
		}
		}";
	}elseif($array[type]=='voice'){
		$data="{
		\"touser\":\"$openid\",
		\"msgtype\":\"voice\",
		\"voice\":
		{
			 \"media_id\":\"$array[id]\"
		}
		}";
	}elseif($array[type]=='video'){
		$array[thumb_media_id] || $array[thumb_media_id]=$array[id];
		$data="{
		\"touser\":\"$openid\",
		\"msgtype\":\"video\",
		\"video\":
		{
			 \"media_id\":\"$array[id]\",
			 \"thumb_media_id\":\"$array[thumb_media_id]\",
			 \"title\":\"$array[title]\",
			 \"description\":\"$array[description]\"
		}
		}";		
	}else{
		if(WEB_LANG!='utf-8'){
			$content = gbk2utf8($content);
		}	
		strstr($content,'"') && $content = addslashes($content);
		$data="{
		\"touser\":\"$openid\",
		\"msgtype\":\"text\",
		\"text\":
		{
			 \"content\":\"$content\"
		}
		}";
	}
	$ac=wx_getAccessToken();
	$string = http_Curl("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$ac",$data);
	if(strstr($string,'ok')){
		return true;
	}else{
		return false;
	}
}


//微信第三方公众号注册帐号用到
function reg_new_member($username,$data){
	global $userDB,$timestamp,$onlineip,$db;

	$username = str_replace(array('|',' ','',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^"),'',$username);
	
	$username = filtrate($username);
	$weixin_id = filtrate($data['openid']);
	$icon = filtrate($data['icon']);
	$sex = intval($data['sex']);
	$address = filtrate("$data[address]");
	$groupid=8;
		
	$username = get_word($username,40,0);	//帐号不能太长
	if($userDB->check_userexists($username)){	//检查用户名是否已存在
		showerr('当前用户名已经存在了，请更换一个！');
	}
		
	$password = rands(10);
	$email = rands(10).'@123.cn';
		
	$array = array(
			'username'=>$username,
			'password'=>$password,
			'email'=>$email,
			'groupid'=>intval($groupid),
			'icon'=>$icon,
			'yz'=>1,
			'lastvist'=>$timestamp,
			'lastip'=>$onlineip,
			'regdate'=>$timestamp,
			'regip'=>$onlineip,
			'sex'=>$sex,
			'address'=>$address,
			'weixin_api'=>$weixin_id,
	);

	//用户注册
	$uid = $userDB->register_user($array);
	if($uid<1){
		showerr($uid);
	}
	$array[uid] = $uid;
	$array[username] = $username;
	
	return $array;
}


function numbers_format($strnum,$num=2){
	return str_replace(',','',number_format($strnum,$num));
}

function is_weixn_open(){//是否在微信客户端浏览器中打开
    if(strstr($_SERVER['HTTP_USER_AGENT'],"MicroMessenger")){
		return true;
	}else{
		return false;
	}
}
?>