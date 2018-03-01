<?php
require(dirname(__FILE__)."/global.php");

if($atc=='uponepic'){
	if(!$upfile_str){
		die('请确认有没有成功上传任何图片！');
	}else{
		$access_token = wx_getAccessToken();
		$wx_api_url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=";
		$path = ROOT_PATH."$webdb[updir]/$dir/$lfjuid/";
		if(!is_dir($path)){
			makepath($path);
		}
		$detail = explode(',',$upfile_str);
		$show='';
		foreach($detail AS $pic){
			if($pic==''){
				continue;
			}
			$name = substr(md5(WEB_ID),-3).'_'.$lfjuid.'_'.rands(4).'.jpg';
			$strcode = file_get_contents($wx_api_url.$pic);
			write_file("$path/$name",$strcode);		
			die("$dir/$lfjuid/$name");
		}
	}
	exit;
}

if($atc=='upmorepic'){
	if(!$upfile_str){
		die('请确认有没有成功上传任何图片！');
	}else{
		$access_token = wx_getAccessToken();
		$wx_api_url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=";
		$path = ROOT_PATH."$webdb[updir]/$dir/$lfjuid/";
		if(!is_dir($path)){
			makepath($path);
		}
		$detail = explode(',',$upfile_str);
		$show='';
		$nowlist||$nowlist=0;
		$i=$nowlist;
		foreach($detail AS $pic){
			if($pic==''){
				continue;
			}
			$i++;
			$name = substr(md5(WEB_ID),-3).'_'.$lfjuid.'_'.rands(4).'.jpg';
			$strcode = file_get_contents($wx_api_url.$pic);
			write_file("$path/$name",$strcode);		

			$show.="<div class='PicList'><input type='text' name='photodb[$i]' value=\"$dir/$lfjuid/$name\"></div>";
		}
		if(!$show){
			die('请确认有没有成功上传任何图片！');
		}else{
			die($show);
		}
	}
	exit;
}
?>