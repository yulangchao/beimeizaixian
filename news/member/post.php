<?php
require_once(dirname(__FILE__)."/global.php");
require_once(Mpath.'inc/function.php');


if(!$fid){
	showerr("FID������");
}

$rs=$db->get_one("SELECT admin FROM {$pre}city WHERE fid='$_COOKIE[admin_cityid]'");
$detail=explode(',',$rs[admin]);
if($lfjid && in_array($lfjid,$detail)){
	$web_admin=1;
}

if(!$lfjid){
	refreshto("$webdb[www_url]/do/login.php","����ǰ̨��û��¼,������ǰ̨��¼",30);
}

/**
*��ȡ��Ŀ��ģ��������ļ�
**/

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if( !$web_admin ){
	if($fidDB[allowpost]){
		if( !in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
			showerr("�������û�����Ȩ����");
		}
	}elseif($webdb[allowGroupPost]){
		if( !in_array($groupdb[gid],explode(",",$webdb[allowGroupPost])) ){
			showerr("�������û�����Ȩ����!");
		}
	}
}



//SEO
$titleDB[title]		= "$fidDB[name] - $webdb[Info_webname] - $titleDB[title]";


if($fidDB[type]){
	showerr("�����,������������");
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
				showerr("����ͼ,ֻ���ϴ�GIF,JPG,PNG��ʽ���ļ�,�㲻���ϴ����ļ�:$array[name]");
			}
			$array[path]=$webdb[updir]."/$_pre/$fid";
	
			$array[updateTable]=1;	//ͳ���û��ϴ����ļ�ռ�ÿռ��С
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
		showerr("����ͼ,ֻ���ϴ�GIF,JPG,PNG��ʽ���ļ�,�㲻���ϴ����ļ�:$array[name]");
	}
}


if($action=="edit"||$action=="postnew")
{
	if(strlen($postdb[title])>150){
		showerr("�����ֽڸ������ܴ���150");
	}
	if(strlen($postdb[keywords])>100){
		showerr("�ؼ����ֽڸ������ܴ���100");
	}
	if(strlen($postdb[author])>50){
		showerr("�����ֽڸ������ܴ���50");
	}
	if(strlen($postdb[copyfrom])>70){
		showerr("��Դ�ֽڸ������ܴ���70");
	}
	if(strlen($postdb[copyfromurl])>150){
		showerr("��Դ��ַ�ֽڸ������ܴ���150");
	}

	if(!$postdb[title]){	
		showerr("�������Ʋ���Ϊ��");
	}
	
	if( count($city_DB[name])==1 )$postdb[city_id]=$city_id;
}


if($action=="postnew"){	//�·�������

	/*��֤�봦��*/
	if(!$web_admin&&$groupdb[postNewsYzImg]){
		if(!check_imgnum($yzimg)){
			showerr("��֤�벻����");
		}
	}
	

	if($isiframe==1){
		$postdb[jumpurl]='';
	}elseif($isiframe==2){
		$postdb[iframeurl]='';
	}else{
		$postdb[iframeurl]=$postdb[jumpurl]='';
	}

	//�跨��������ͼ
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

	
	//ͼƬĿ¼ת��
	$postdb[content]=move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");
	//��ȡԶ��ͼƬ
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);
	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���

	
 	foreach($postdb AS $key=>$value){	
		if($key=='content'){		
			continue;
		}
		$postdb[$key]=filtrate($value);
	}
	
	$db->query("INSERT INTO `{$_pre}content` ( `title` , `mid` , `fid` , `fname` , `city_id` , `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` ,`keywords` , `jumpurl` , `iframeurl` , `ip` ,`author`, `copyfrom`, `copyfromurl`, `pages`) VALUES ('$postdb[title]','1','$fid','$fidDB[name]','$postdb[city_id]','$timestamp','$timestamp','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$postdb[jumpurl]','$postdb[iframeurl]','$onlineip','$postdb[author]','$postdb[copyfrom]','$postdb[copyfromurl]',1)");

	$id=$db->insert_id();

	$db->query("INSERT INTO `{$_pre}content_1` (`id` , `fid` , `uid` , `topic` , `content`) VALUES ('$id', '$fid', '$lfjdb[uid]', '1', '$postdb[content]')");
	//�Ƹ�����
	$array = array('title'=>$postdb[title],'fid'=>$fid,'id'=>$id);
	if($postdb[yz]){
		Give_News_money($lfjuid,'yz',$array);
	}
	if($postdb[com]){
		Give_News_money($lfjuid,'com',$array);
	}

	set_user_log(4);	//�û�������־

 	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id' target='_blank'>�鿴Ч��</a> <a href='post.php?fid=$fid'>�����·���</a> <a href='post.php?job=postmore&fid=$fid&id=$id'>�����ڶ�ҳ</a> <a href='list.php?job=list'>�����б�</a>",300);


}elseif($action=="postmore"){	//��������

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('���ⲻ���ڣ�');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID��һ�£�');
	}
	
	$postdb[subhead] = filtrate($postdb[subhead]);
	
	//ͼƬĿ¼ת��
	$postdb[content]=move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");
	//��ȡԶ��ͼƬ
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);
	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���
		
	$db->query("INSERT INTO `{$_pre}content_1` (`id` , `fid` , `uid` , `topic` , `subhead` , `content`) VALUES ('$id', '$fid', '$lfjdb[uid]', '0', '$postdb[subhead]', '$postdb[content]')");
	

	if($rsdb[pages]<1){
		$rsdb[pages]=1;
	}
	$rsdb[pages]++;
	$db->query("UPDATE `{$_pre}content` SET pages='$rsdb[pages]' WHERE id='$id'");
	

 	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&page=$rsdb[pages]' target='_blank'>�鿴Ч��</a> <a href='post.php?job=postmore&fid=$fid&id=$id'>��Ҫ����</a> <a href='list.php?job=list'>�����б�</a>",300);


}elseif($action=="editorder"){	//�޸�����
	
	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.id='$id'");
	if(!$rsdb){
		showerr('���ݲ����ڣ�');
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ����");
	}
	foreach($pagedb AS $key=>$value){
		$db->query("UPDATE `{$_pre}content_1` SET orderid='$value' WHERE rid='$key' AND id='$id'");
	}
	refreshto($FROMURL,"�޸ĳɹ�",1);

}elseif($action=="delmore"){	//ɾ����ҳ

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");

	if($rsdb[fid]!=$fidDB[fid]){	
		showerr("��Ŀ������");
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ����");
	}
	
	$db->query("DELETE FROM `{$_pre}content_1` WHERE rid='$rid' AND topic=0 ");
	delete_attachment($rsdb[uid],$rsdb[content]);
	
	$db->query("UPDATE `{$_pre}content` SET pages=pages-1 WHERE id='$id'");

	refreshto($FROMURL,'ɾ���ɹ�',1);

}elseif($action=="del"){	//ɾ������,ֱ��ɾ��,������

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid]){	
		showerr("��Ŀ������");
	}

	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ����");
	}


	$db->query("DELETE FROM `{$_pre}content` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}content_1` WHERE id='$id' ");
	$db->query("DELETE FROM `{$_pre}comments` WHERE id='$id' ");
	//�Ƹ�����
	Give_News_money($rsdb[uid],'del');
	if($rsdb[levels]){
		//Give_News_money($rsdb[uid],'uncom');
	}
	delete_attachment($rsdb[uid],tempdir($rsdb[picurl]));
	delete_attachment($rsdb[uid],$rsdb[content]);

	refreshto("list.php?job=list",'ɾ���ɹ�',1);
	

}elseif($job=="choosedel"){	//ɾ�����ݣ������ǵ�ҳҲ�п����Ƕ�ҳ

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('���ⲻ���ڣ�');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID��һ�£�');
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
	

}elseif($job=="chooseedit"){	//�޸����ݣ������ǵ�ҳҲ�п����Ƕ�ҳ

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('���ⲻ���ڣ�');
	}elseif($rsdb[fid]!=$fid){
		showerr('FID��һ�£�');
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
	
}elseif($job=="editmore"){	//�޸����¶�ҳ

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");
	if(!$rsdb){
		showerr('���ݲ����ڣ�');
	}
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content] = editor_replace($rsdb[content]);

	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/postmore.htm");
	require(ROOT_PATH."member/foot.php");
	
}elseif($action=="editmore"){	//�޸����µĶ�ҳ

	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE B.rid='$rid'");
	if(!$rsdb){
		showerr('���ݲ����ڣ�');
	}
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("����Ȩ����");
	}
	
	$postdb[subhead] = filtrate($postdb[subhead]);
	//ͼƬĿ¼ת��
	$postdb[content] = move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");

	//��ȡԶ��ͼƬ
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);

	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/<(script)([^<>]*)>/i','&lt;\\1\\2>',$postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���
	
	$db->query("UPDATE `{$_pre}content_1` SET subhead='$postdb[subhead]',content='$postdb[content]' WHERE rid='$rid' AND id='$id'");
	
	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&rid=$rid' target='_blank'>�鿴Ч��</a> <a href='list.php?job=list'>�����б�</a> <a href='$FROMURL'>�����޸�</a>",600);

}elseif($job=="edit"){	//�޸���������

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

}elseif($action=="edit"){	//�޸���������

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_1` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}

	if($isiframe==1){
		$postdb[jumpurl]='';
	}elseif($isiframe==2){
		$postdb[iframeurl]='';
	}else{
		$postdb[iframeurl]=$postdb[jumpurl]='';
	}

	
	//�跨��������ͼ
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
	
	//ͼƬĿ¼ת��
	$postdb[content] = move_attachment($lfjdb[uid],$postdb[content],"{$_pre}/$fid");

	//��ȡԶ��ͼƬ
	$postdb[content] = get_outpic($postdb[content],$fid,$GetOutPic);

	$postdb[content] = En_TruePath($postdb[content]);
	$postdb[content] = preg_replace('/<(script)([^<>]*)>/i','&lt;\\1\\2>',$postdb[content]);
	$postdb[content] = preg_replace('/javascript/i','java script',$postdb[content]);	//����js����
	$postdb[content] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);	//���˿�ܴ���

	foreach($postdb AS $key=>$value){
		if($key=='content'){		
			continue;
		}
		$postdb[$key]=filtrate($value);
	}	
	

	$db->query("UPDATE `{$_pre}content` SET title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',city_id='$postdb[city_id]',iframeurl='$postdb[iframeurl]',jumpurl='$postdb[jumpurl]',author='$postdb[author]',copyfrom='$postdb[copyfrom]',copyfromurl='$postdb[copyfromurl]' WHERE id='$id'");

	$db->query("UPDATE `{$_pre}content_1` SET content='$postdb[content]' WHERE id='$id' AND topic=1");

	set_user_log(5);	//�û�������־

	refreshto("list.php?job=list","<a href='$Mdomain/bencandy.php?fid=$fid&id=$id&rid=$rid' target='_blank'>�鿴Ч��</a> <a href='list.php?job=list'>�����б�</a> <a href='$FROMURL'>�����޸�</a>",600);	

}elseif($job=='postmore'){	//������ҳ����

	$rsdb=$db->get_one("SELECT A.* FROM `{$_pre}content` A WHERE A.id='$id'");
	if(!$rsdb){
		showerr('���ⲻ���ڣ�');
	}
	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/postmore.htm");
	require(ROOT_PATH."member/foot.php");

}else{	//�·�������

	$atc="postnew";

 	$isiframe[0]=" checked ";

	require(ROOT_PATH."member/head.php");
	require(dirname(__FILE__)."/template/post.htm");
	require(ROOT_PATH."member/foot.php");
}

//�ɼ��ⲿͼƬ
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
	
		/*��ˮӡ*/
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