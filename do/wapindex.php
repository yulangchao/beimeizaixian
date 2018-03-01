<?php

require_once(dirname(__FILE__)."/global.php");

/*
if(!is_mobile() && !$lfjuid){
$URL = urlencode($WEBURL);
print<<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title>$titleDB[title]</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
 </head>

 <body>
  <center>手机版内容，只能用手机扫一扫下面的二维码才能查看<br><br>
  <img src="$webdb[www_url]/do/wap2codeimg.php?url=$URL"></center>
 </body>
</html>
EOT;
exit;
}
*/

if($city_DB[domain]&&!$webdb[cookieDomain]){
	showerr('你启用了城市二级域名,请进后台设置一下COOKIE有效域名,否则用户登录前台会不正常!');
}

//mob_goto_url("$webdb[www_url]/3g/wapindex.php?choose_cityID=$city_id");	//手机访问自动跳转

//商铺二级域名如果绑定到根目录的情况做检查处理
if(count($city_DB[name])>1 && $isCityDomain==false && $tempDomain!=$webdb[_www_url]){
	$hyDomain=preg_replace("/http:\/\/([^\.]+)\.(.*)/is","\\1",$WEBURL);
	$rsdb=$db->get_one("SELECT uid FROM {$pre}hy_company WHERE host='$hyDomain'");
	if($rsdb[uid]){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=waphy.php?uid=$rsdb[uid]'>";
		exit;
	}	
}

//如果有二级域名的话,就跳转到二级域名
if($jobs!='show'&&$_domain=$city_DB[domain][$city_id]){
	if(!strstr($WEBURL,$_domain)){
		if(strstr($WEBURL,$webdb[www_url])){
			$url=str_replace($webdb[www_url],$_domain,$WEBURL);
		}else{
			$url=preg_replace("/^http:\/\/([^\/]+)(\/.*|)$/is","$_domain\\2",$WEBURL);
		}
		header("location:$url");exit;
	}
}

web_cache($webdb['wapindex_cache_time']);	//调取缓存，直接显示，下面的代码不再执行

require(ROOT_PATH."data/friendlink.php");


//SEO
$titleDB['title'] = $city_DB['metaT'][$city_id]?seo_eval($city_DB['metaT'][$city_id]):$titleDB['title'];
$titleDB['keywords']	= $city_DB['metaK'][$city_id]?seo_eval($city_DB['metaK'][$city_id]):$titleDB['keywords'];
$titleDB['description'] = $city_DB['metaD'][$city_id]?seo_eval($city_DB['metaD'][$city_id]):$titleDB['description'];



$head_tpl = $foot_tpl = $index_tpl = '';

//城市模板
if($city_DB['tpl'][$city_id]){
	list($_head_tpl,$_foot_tpl,$_index_tpl)=explode("|",$city_DB['tpl'][$city_id]);
	is_file(ROOT_PATH.$_head_tpl) && $head_tpl = ROOT_PATH.$_head_tpl;
	is_file(ROOT_PATH.$_foot_tpl) && $foot_tpl = ROOT_PATH.$_foot_tpl;
	is_file(ROOT_PATH.$_index_tpl) && $index_tpl = ROOT_PATH.$_index_tpl;
}


$head_tpl=html("waphead",'');$foot_tpl=html("wapfoot",'');	//所有用标签的地方都要加上这个，防止与PC版的有冲突。
	
//获取标签内容
$template_file=html('wapmain',$index_tpl);
fetch_label_value(array('pagetype'=>'8','file'=>$template_file,'module'=>'0'));


require($template_file);

/*首页生静态*/
if(count($city_DB[name])<2 && ($jobs!='show'&&$webdb[MakeIndexHtmlTime]>0) || $MakeIndex )
{
	if( $MakeIndex || (time()-@filemtime('wapindex.htm')-$webdb[MakeIndexHtmlTime]*60)>0 ){	
		write_file(ROOT_PATH.'wapindex.htm',$content);
		if($MakeIndex){		
			ob_end_clean();
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/wapindex.htm'>";
			exit;
		}
	}
}

?>