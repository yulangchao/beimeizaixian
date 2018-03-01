<?php
require_once(dirname(__FILE__)."/global.php");
require_once(Mpath.'inc/function.php');


if(!$fid){
	showerr("FID不存在");
}

$rs=$db->get_one("SELECT admin FROM {$pre}city WHERE fid='$_COOKIE[admin_cityid]'");
$detail=explode(',',$rs[admin]);
if($lfjid && in_array($lfjid,$detail)){
	$web_admin=1;
}

if(!$lfjid){
	refreshto("$webdb[www_url]/do/login.php","你在前台还没登录,请先在前台登录",30);
}

/**
*获取栏目与模块的配置文件
**/

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if( !$web_admin ){
	if($fidDB[allowpost]){
		if( !in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
			showerr("你所在用户组无权发表");
		}
	}elseif($webdb[allowGroupPost]){
		if( !in_array($groupdb[gid],explode(",",$webdb[allowGroupPost])) ){
			showerr("你所在用户组无权发表!");
		}
	}
}



//SEO
$titleDB[title]		= "$fidDB[name] - $webdb[Info_webname] - $titleDB[title]";


if($fidDB[type]){
	showerr("大分类,不允许发表内容");
}




if($_FILES||$postdb[picurl]){
	foreach( $_FILES AS $key=>$value ){

		$i=(int)substr($key,10);
		if(is_array($value)){
			$postfile=$value['tmp_name'];
			$array[name]=$value['name'];
			$array[size]=$value['size'];
		} else{
			$postfile=$$key;
			$array[name]=${$key.'_name'};
			$array[size]=${$key.'_size'};
		}
		if($ftype[$i]=='in'&&$array[name]){

			if($i==1&&!eregi("(gif|jpg|png)$",$array[name])){
				showerr("缩略图,只能上传GIF,JPG,PNG格式的文件,你不能上传此文件:$array[name]");
			}
			$array[path]=$webdb[updir]."/$_pre/$fid";
	
			$array[updateTable]=1;	//统计用户上传的文件占用空间大小
			$filename=upfile($postfile,$array);
			if($i==1){
				$postdb[picurl]="$_pre/$fid/$filename";
				if($webdb[if_gdimg])
				{
					$Newpicpath=ROOT_PATH."$webdb[updir]/$postdb[picurl]";
					gdpic($Newpicpath,"{$Newpicpath}.jpg",300,400,array('fix'=>1));
					gdpic($Newpicpath,"{$Newpicpath}.jpg.jpg",400,400,array('fix'=>1));
					gdpic($Newpicpath,$Newpicpath,400,300,array('fix'=>1));
				}
			}
		}
	}
	if($postdb[picurl]&&!eregi("(gif|jpg|png)$",$postdb[picurl])){
		showerr("缩略图,只能上传GIF,JPG,PNG格式的文件,你不能上传此文件:$array[name]");
	}
}


if($action=="edit"||$action=="postnew")
{
	if(strlen($postdb[title])>150){
		showerr("标题字节个数不能大于150");
	}
	if(strlen($postdb[keywords])>100){
		showerr("关键字字节个数不能大于100");
	}
	if(strlen($postdb[author])>50){
		showerr("作者字节个数不能大于50");
	}
	if(strlen($postdb[copyfrom])>70){
		showerr("来源字节个数不能大于70");
	}
	if(strlen($postdb[copyfromurl])>150){
		showerr("来源网址字节个数不能大于150");
	}

	if(!$postdb[title]){	
		showerr("标题名称不能为空");
	}
	
	if( count($city_DB[name])==1 )$postdb[city_id]=$city_id;
}


if($action=="postnew"){	//新发表内容

	/*验证码处理*/
	if(!$web_admin&&$groupdb[postNewsYzImg]){
		if(!check_imgnum($yzimg)){
			showerr("验证码不符合");
		}
	}
	

	if($isiframe==1){
		$postdb[jumpurl]='';
	}elseif($isiframe==2){
		$postdb[iframeurl]='';
	}else{
		$postdb[iframeurl]=$postdb[jumpurl]='';
	}

	//设法生成缩略图
	if( !$postdb[picurl] && $file_db=get_content_attachment($postdb[content]) ){
		if( $file_db[0] && eregi("(jpg|gif|png)$",$file_db[0]) && !eregi("sysimage\/file",$file_db[0]) ){
			$postdb[picurl]=$file_db[0];
			if($webdb[if_gdimg]){			
				$postdb[picurl]=str_replace(".","_",$file_db[0]).'.gif';
				$Newpicpath=ROOT_PATH."$webdb[updir]/$postdb[picurl]";
				gdpic(ROOT_PATH."$webdb[updir]/$file_db[0]",$Newpicpath,200,150);
				if(!file_exists($Newpicpath)){
					$postdb[picurl]=$file_db[0];
				}
			}
		}
	}

	if($postdb[picurl]){	
		$postdb[ispic]=1;
	}else{	
		$postdb[ispic]=0;
	}
	
	$postdb[yz]=1;
	if(!$web_admin){
		if( $webdb[Info_GroupPostYZ] && !in_array($groupdb['gid'],explode(",",$webdb[Info_GroupPostYZ])) ){		
			$postdb[yz]=0;
		}
	}

	
	//图片目录转移
	$postdb[content]=move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");
	//获取远程图片
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);
	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//过滤js代码
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//过滤框架代码

	
 	foreach($postdb AS $key=>$value){	
		if($key=='content'){		
			continue;
		}
		$postdb[$key]=filtrate($value);
	}
	
	$db->query("INSERT INTO `{$_pre}content` ( `title` , `mid` , `fid` , `fname` , `city_id` , `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` ,`keywords` , `jumpurl` , `iframeurl` , `ip` ,`author`, `copyfrom`, `copyfromurl`, `pages`) VALUES ('$postdb[title]','1','$fid','$fidDB[name]','$postdb[city_id]','$timestamp','$timestamp','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$postdb[jumpurl]','$postdb[iframeurl]','$onlineip','$postdb[author]','$postdb[copyfrom]','$postdb[copyfromurl]',1)");

	$id=$db->insert_id();

	$db->query("INSERT INTO `{$_pre}content_1` (`id` , `fid` , `uid` , `topic` , `content`) VALUES ('$id', '$fid', '$lfjdb[uid]', '1', '$postdb[content]')");
	//财富处理
	$array = array('title'=>$postdb[title],'fid'=>$fid,'id'=>$id);
	if($postdb[yz]){
		Give_News_money($lfjuid,'yz',$array);
	}
	if($postdb[com]){
		Give_News_money($lfjuid,'com',$array);
	}

	set_user_log(4);	//用户访问日志

 	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id' target='_blank'>查看效果</a> <a href='post.php?fid=$fid'>继续新发表</a> <a href='post.php?job=postmore&fid=$fid&id=$id'>续发第二页</a> <a href='list.php?job=list'>返回列表</a>",300);


}elseif($action=="postmore"){	//续发内容

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('主题不存在！');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID不一致！');
	}
	
	$postdb[subhead] = filtrate($postdb[subhead]);
	
	//图片目录转移
	$postdb[content]=move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");
	//获取远程图片
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);
	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//过滤js代码
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//过滤框架代码
		
	$db->query("INSERT INTO `{$_pre}content_1` (`id` , `fid` , `uid` , `topic` , `subhead` , `content`) VALUES ('$id', '$fid', '$lfjdb[uid]', '0', '$postdb[subhead]', '$postdb[content]')");
	

	if($rsdb[pages]<1){
		$rsdb[pages]=1;
	}
	$rsdb[pages]++;
	$db->query("UPDATE `{$_pre}content` SET pages='$rsdb[pages]' WHERE id='$id'");
	

 	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&page=$rsdb[pages]' target='_blank'>查看效果</a> <a href='post.php?job=postmore&fid=$fid&id=$id'>还要续发</a> <a href='list.php?job=list'>返回列表</a>",300);


}elseif($action=="editorder"){	//修改排序
	
	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.id='$id'");
	if(!$rsdb){
		showerr('内容不存在！');
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("你无权操作");
	}
	foreach($pagedb AS $key=>$value){
		$db->query("UPDATE `{$_pre}content_1` SET orderid='$value' WHERE rid='$key' AND id='$id'");
	}
	refreshto($FROMURL,"修改成功",1);

}elseif($action=="delmore"){	//删除多页

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");

	if($rsdb[fid]!=$fidDB[fid]){	
		showerr("栏目有问题");
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("你无权操作");
	}
	
	$db->query("DELETE FROM `{$_pre}content_1` WHERE rid='$rid' AND topic=0 ");
	delete_attachment($rsdb[uid],$rsdb[content]);
	
	$db->query("UPDATE `{$_pre}content` SET pages=pages-1 WHERE id='$id'");

	refreshto($FROMURL,'删除成功',1);

}elseif($action=="del"){	//删除主题,直接删除,不保留

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid]){	
		showerr("栏目有问题");
	}

	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("你无权操作");
	}


	$db->query("DELETE FROM `{$_pre}content` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}content_1` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}comments` WHERE id='$id' ");
	//财富处理
	Give_News_money($rsdb[uid],'del');
	if($rsdb[levels]){
		//Give_News_money($rsdb[uid],'uncom');
	}
	delete_attachment($rsdb[uid],tempdir($rsdb[picurl]));
	delete_attachment($rsdb[uid],$rsdb[content]);

	refreshto("list.php?job=list",'删除成功',1);
	

}elseif($job=="choosedel"){	//删除内容，可能是单页也有可能是多页

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('主题不存在！');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID不一致！');
	}
	if($rsdb[pages]<2){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=post.php?action=del&fid=$rsdb[fid]&id=$rsdb[id]'>";
		exit;
	}
	$query =$db->query("SELECT * FROM `{$_pre}content_1` A WHERE A.id='$id' ORDER BY orderid ASC,rid ASC");
	while($rs =$db->fetch_array($query)){
		if($rs[topic]){
			$rs[subhead]=$rsdb[title];
		}
		$listdb[]=$rs;
	}
	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/choosedel.htm");
	require(ROOT_PATH."member/foot.php");
	

}elseif($job=="chooseedit"){	//修改内容，可能是单页也有可能是多页

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('主题不存在！');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID不一致！');
	}
	if($rsdb[pages]<2){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=post.php?job=edit&fid=$rsdb[fid]&id=$rsdb[id]'>";
		exit;
	}
	$query =$db->query("SELECT * FROM `{$_pre}content_1` A WHERE A.id='$id' ORDER BY orderid ASC,rid ASC");
	while($rs =$db->fetch_array($query)){
		if($rs[topic]){
			$rs[subhead]=$rsdb[title];
		}
		$listdb[]=$rs;
	}
	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/chooseedit.htm");
	require(ROOT_PATH."member/foot.php");
	
}elseif($job=="editmore"){	//修改文章多页

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");
	if(!$rsdb){
		showerr('内容不存在！');
	}
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content] = editor_replace($rsdb[content]);

	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/postmore.htm");
	require(ROOT_PATH."member/foot.php");
	
}elseif($action=="editmore"){	//修改文章的多页

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");
	if(!$rsdb){
		showerr('内容不存在！');
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("你无权操作");
	}
	
	$postdb[subhead] = filtrate($postdb[subhead]);
	//图片目录转移
	$postdb[content] = move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");

	//获取远程图片
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);

	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/<(script)([^<>]*)>/i','&lt;\\1\\2>',$postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//过滤js代码
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//过滤框架代码
	
	$db->query("UPDATE `{$_pre}content_1` SET subhead='$postdb[subhead]',content='$postdb[content]' WHERE rid='$rid' AND id='$id'");
	
	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&rid=$rid' target='_blank'>查看效果</a> <a href='list.php?job=list'>返回列表</a> <a href='$FROMURL'>继续修改</a>",600);

}elseif($job=="edit"){	//修改文章主题

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id' AND topic=1");

	if($rsdb["jumpurl"]){
		$chooseiframe='2';
		$isiframe[2]=" checked ";
	}elseif($rsdb["iframeurl"]){
		$chooseiframe='1';
		$isiframe[1]=" checked ";
	}else{
		$chooseiframe='0';
		$isiframe[0]=" checked ";
	}

	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content] = editor_replace($rsdb[content]);

	$atc="edit";

	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/post.htm");
	require(ROOT_PATH."member/foot.php");

}elseif($action=="edit"){	//修改文章主题

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	if($isiframe==1){
		$postdb[jumpurl]='';
	}elseif($isiframe==2){
		$postdb[iframeurl]='';
	}else{
		$postdb[iframeurl]=$postdb[jumpurl]='';
	}

	
	//设法生成缩略图
	if( !$postdb[picurl] && $file_db=get_content_attachment($postdb[content]) ){
		if( $file_db[0] && eregi("(jpg|gif)$",$file_db[0]) && !eregi("sysimage\/file",$file_db[0]) ){
			$postdb[picurl]=$file_db[0];
			if($webdb[if_gdimg])
			{
				$postdb[picurl]=str_replace(".","_",$file_db[0]).'.gif';
				$Newpicpath=ROOT_PATH."$webdb[updir]/$postdb[picurl]";
				gdpic(ROOT_PATH."$webdb[updir]/$file_db[0]",$Newpicpath,200,150);
				if(!file_exists($Newpicpath)){
					$postdb[picurl]=$file_db[0];
				}
			}
		}
	}

	if($postdb[picurl]){	
		$postdb[ispic]=1;
	}else{	
		$postdb[ispic]=0;
	}
	
	//图片目录转移
	$postdb[content] = move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");

	//获取远程图片
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);

	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/<(script)([^<>]*)>/i','&lt;\\1\\2>',$postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//过滤js代码
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//过滤框架代码

	foreach($postdb AS $key=>$value){
		if($key=='content'){		
			continue;
		}
		$postdb[$key]=filtrate($value);
	}	
	

	$db->query("UPDATE `{$_pre}content` SET title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',city_id='$postdb[city_id]',iframeurl='$postdb[iframeurl]',jumpurl='$postdb[jumpurl]',author='$postdb[author]',copyfrom='$postdb[copyfrom]',copyfromurl='$postdb[copyfromurl]' WHERE id='$id'");

	$db->query("UPDATE `{$_pre}content_1` SET content='$postdb[content]' WHERE id='$id' AND topic=1");

	set_user_log(5);	//用户访问日志

	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&rid=$rid' target='_blank'>查看效果</a> <a href='list.php?job=list'>返回列表</a> <a href='$FROMURL'>继续修改</a>",600);	

}elseif($job=='postmore'){	//续发多页文章

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('主题不存在！');
	}
	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/postmore.htm");
	require(ROOT_PATH."member/foot.php");

}else{	//新发表文章

	$atc="postnew";

 	$isiframe[0]=" checked ";

	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/post.htm");
	require(ROOT_PATH."member/foot.php");
}

//采集外部图片
function get_outpic($str,$fid=0,$getpic=1){
	global $webdb,$_pre,$lfjuid;
	if(!$getpic){
		return $str;
	}
	preg_match_all("/http:\/\/([^ '\"<>]+)\.(gif|jpg|png)/is",$str,$array);
	$filedb=$array[0];
	foreach( $filedb AS $key=>$value){
		if( strstr($value,$webdb[www_url]) ){
			continue;
		}
		$listdb["$value"]=$value;
	}
	unset($filedb);
	foreach( $listdb AS $key=>$value){
		$filedb[]=$value;
		$name=$lfjuid."_".rands(5)."__".preg_replace("/(.*)\.(jpg|png|gif)$/is",".\\2",$value);
		if(!is_dir(ROOT_PATH."$webdb[updir]/$_pre")){
			makepath(ROOT_PATH."$webdb[updir]/$_pre");
		}
		if(!is_dir(ROOT_PATH."$webdb[updir]/$_pre/$fid")){
			makepath(ROOT_PATH."$webdb[updir]/$_pre/$fid");
		}
		$ck=0;
		if( @copy($value,ROOT_PATH."$webdb[updir]/$_pre/$fid/$name") ){
			$ck=1;
		}elseif($filestr=sockOpenUrl($value)){
			$ck=1;
			write_file(ROOT_PATH."$webdb[updir]/$_pre/$fid/$name",$filestr);
		}
	
		/*加水印*/
		if($ck&&$webdb[is_waterimg]&&$webdb[if_gdimg])
		{
			include_once(ROOT_PATH."inc/waterimage.php");
			$uploadfile=ROOT_PATH."$webdb[updir]/$_pre/$fid/$name";
			imageWaterMark($uploadfile,$webdb[waterpos],ROOT_PATH.$webdb[waterimg]);
		}

		if($ck){
			$str=str_replace("$value","http://www_qibosoft_com/Tmp_updir/$_pre/$fid/$name",$str);
		}
	}
	return $str;
}



?>